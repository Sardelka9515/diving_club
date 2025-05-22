<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Carbon\Carbon;
use Dom\Comment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function index(Request $request)
    {
        // 設置基本查詢
        $query = Report::with(['comment', 'user', 'comment.user']);

        // 狀態過濾
        $status = $request->get('status', 'all');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // 搜尋過濾
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('details', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('comment', function ($q) use ($search) {
                        $q->where('content', 'like', "%$search%");
                    })
                    ->orWhereHas('comment.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        // 評論ID過濾
        if ($request->filled('comment_id')) {
            $query->where('comment_id', $request->comment_id);
        }

        // 獲取統計資料
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'auto_resolved' => Report::where('status', 'auto_resolved')->count(),
            'today' => Report::whereDate('created_at', Carbon::today())->count(),
        ];

        // 排序和分頁
        $reports = $query->latest()->paginate(20);

        return view('admin.reports.index', compact('reports', 'status', 'stats'));
    }

    /**
     * Mark the report as resolved.
     *
     * @param  \App\Models\Report  $report
     */
    public function resolve(Report $report)
    {
        // 修改舉報狀態為已處理
        $report->status = 'resolved';
        $report->save();

        // 如果評論存在，清除評論的舉報標記
        if ($report->comment) {
            $report->comment->is_reported = false;
            $report->comment->save();
        }

        return back()->with('success', "舉報 #{$report->id} 已處理完成，評論檢舉標記已清除");
    }

    public function reopen(Report $report)
    {
        // 修改狀態為待處理
        $report->status = 'pending';
        $report->save();

        // 如果評論存在，將評論標記為被舉報
        if ($report->comment) {
            $report->comment->is_reported = true;
            $report->comment->save();
        }

        return back()->with('success', "舉報 #{$report->id} 已重新開啟");
    }
}

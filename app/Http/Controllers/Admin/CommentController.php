<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        // 設置基本查詢
        $query = Comment::withCount('reports')
            ->with(['user:id,name', 'activity:id,title']);

        // 狀態過濾
        $status = $request->get('status', 'all');

        if ($status === 'published') {
            $query->where('status', 'approved')
                ->where('is_visible', true)
                ->where('is_reported', false);
        } elseif ($status === 'hidden') {
            $query->where('is_visible', false);
        } elseif ($status === 'reported') {
            $query->where('is_reported', true);
        } elseif ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        }

        // 檢舉過濾
        if ($request->has('reported') && $request->reported == 'true') {
            $query->where('is_reported', true);
        }

        // 最近過濾
        if ($request->has('recent') && $request->recent == 'true') {
            $query->where('created_at', '>=', Carbon::now()->subHours(24));
        }

        // 搜尋過濾
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('activity', function ($q) use ($search) {
                        $q->where('title', 'like', "%$search%");
                    });
            });
        }

        // 獲取統計資料
        $stats = [
            'total' => Comment::count(),
            'published' => Comment::where('status', 'approved')->where('is_visible', true)->where('is_reported', false)->count(),
            'hidden' => Comment::where('is_visible', false)->count(),
            'reported' => Comment::where('is_reported', true)->count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'today' => Comment::where('created_at', '>=', Carbon::today())->count(),
        ];

        // 排序和分頁
        $comments = $query->latest()->paginate(20);

        return view('admin.comments.index', compact('comments', 'status', 'stats'));
    }

    // 切換評論可見性
    public function toggleVisibility(Comment $comment)
    {
        $comment->is_visible = !$comment->is_visible;
        $comment->save();

        $status = $comment->is_visible ? '顯示' : '隱藏';
        return back()->with('success', "評論 #{$comment->id} 已設為{$status}");
    }

    // 清除檢舉標記
    public function clearReported(Comment $comment)
    {
        $comment->is_reported = false;
        $report = Report::where('comment_id', $comment->id)->first();
        if ($report) {
            $report->delete();
        }

        $comment->save();

        return back()->with('success', "評論 #{$comment->id} 已清除檢舉標記");
    }

    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->route('admin.comments.index')->with('success', '評論已更新。');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        if ($comment->parent_id === null) {
            Comment::where("parent_id", $comment->id)->delete();
        }

        $report = Report::where('comment_id', $comment->id)->first();
        if ($report) {
            $report->delete();
        }

        return redirect()->route('admin.comments.index')->with('success', '評論已刪除。');
    }

    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);

        return back()->with('success', '評論已核准。');
    }

    public function reject(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('success', '評論已拒絕。');
    }
}

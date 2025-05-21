<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
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
        $status = $request->get('status', 'pending');
        
        $reports = Report::with(['user', 'comment.user', 'comment.activity'])
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);
            
        return view('admin.reports.index', compact('reports', 'status'));
    }

    /**
     * Mark the report as resolved.
     *
     * @param  \App\Models\Report  $report
     */
    public function resolve(Report $report)
    {
        $report->update(['status' => 'resolved']);
        
        return back()->with('success', '舉報已標記為已處理。');
    }

    /**
     * Mark the report as rejected.
     *
     * @param  \App\Models\Report  $report
     */
    public function reject(Report $report)
    {
        $report->update(['status' => 'rejected']);
        
        return back()->with('success', '舉報已標記為已駁回。');
    }
}
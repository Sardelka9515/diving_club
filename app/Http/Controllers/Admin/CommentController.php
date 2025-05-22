<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        $reported = $request->get('reported') == 'true';
        $recent = $request->get('recent') == 'true';

        $comments = Comment::with(['user', 'activity'])
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('activity', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            })
            ->when($reported, function ($query) {
                return $query->whereHas('reports');
            })
            ->when($recent, function ($query) {
                return $query->where('created_at', '>=', now()->subDay());
            })
            ->latest()
            ->paginate(15);

        return view('admin.comments.index', compact('comments', 'status'));
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

<?php

namespace App\Http\Controllers;

use App\Notifications\NewComment;
use App\Notifications\CommentReported;
use App\Models\User;
use App\Models\Comment;
use App\Models\Activity;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'status' => 'approved'
        ]);

        $activity->comments()->save($comment);

        // 在 store 方法和 reply 方法結尾添加
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewComment($comment)); // 或 $reply
        }

        return back()->with('success', '評論已成功發布！');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return back()->with('success', '評論已更新。');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        // Check if it's a parent comment with replies
        if ($comment->parent_id === null) {
            // Delete all replies first
            Comment::where('parent_id', $comment->id)->delete();
        }

        $report = Report::where('comment_id', $comment->id)->first();
        if ($report) {
            $report->delete();
        }

        $comment->delete();

        return back()->with('success', '評論已刪除。');
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'activity_id' => $comment->activity_id,
            'parent_id' => $comment->id,
            'status' => 'approved'
        ]);

        $reply->save();

        // 在 store 方法和 reply 方法結尾添加
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewComment($comment)); // 或 $reply
        }

        return back()->with('success', '回覆已送出。');
    }
    public function report(Request $request, Comment $comment)
    {
        $request->validate([
            'reason' => 'required|string|in:dislike,harassment,self_harm,violence_hate,regulated_goods,nudity,fraud_spam,false_info,other',
            'details' => 'nullable|string|max:1000',
        ]);

        // 檢查是否已經舉報過
        $existingReport = Report::where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existingReport) {
            return back()->with('error', '您已經舉報過此評論');
        }

        $report = new Report([
            'comment_id' => $comment->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        $report->save();

        // 發送通知給管理員
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new CommentReported($report));
        }

        return back()->with('success', '感謝您的舉報，我們會盡快處理');
    }
    
    /**
     * Display the member's comments
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function memberComments(Request $request)
    {
        $query = Comment::where('user_id', Auth::id());

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Handle type filter (comment or reply)
        if ($request->filled('type')) {
            if ($request->type === 'comment') {
                $query->whereNull('parent_id');
            } elseif ($request->type === 'reply') {
                $query->whereNotNull('parent_id');
            }
        }

        // Handle search
        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        // Get the comments with relations
        $comments = $query->with(['activity:id,title', 'parent.user:id,name', 'parent:id,content,user_id'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('member.comments', compact('comments'));
    }
}

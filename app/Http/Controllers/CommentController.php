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
            foreach ($comment->replies as $reply) {
                Report::where('comment_id', $reply->id)->delete();
                $reply->delete();
            }
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

        // 建立舉報記錄
        $report = new Report([
            'comment_id' => $comment->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'pending', // 預設為待處理狀態
        ]);

        $report->save();

        // 更新評論的舉報標記
        $comment->is_reported = true;
        $comment->save();

        // 發送通知給管理員
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new CommentReported($report));
        }

        return back()->with('success', '感謝您的舉報，我們會盡快處理');
    }

    public function toggleVisibility(Comment $comment)
    {
        // 確保只有自己的評論可以切換可見性
        $this->authorize('update', $comment);

        $comment->is_visible = !$comment->is_visible;
        $comment->save();

        $status = $comment->is_visible ? '顯示' : '隱藏';

        return back()->with('success', "評論已設為{$status}");
    }

    /**
     * 修改會員評論列表方法以支援新狀態過濾
     */
    public function memberComments(Request $request)
    {
        $query = Comment::where('user_id', Auth::id());

        // 處理狀態過濾
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_visible', true)->where('is_reported', false);
            } elseif ($request->status === 'hidden') {
                $query->where('is_visible', false);
            } elseif ($request->status === 'reported') {
                $query->where('is_reported', true);
            }
        }

        // 處理類型過濾
        if ($request->filled('type')) {
            if ($request->type === 'comment') {
                $query->whereNull('parent_id');
            } elseif ($request->type === 'reply') {
                $query->whereNotNull('parent_id');
            }
        }

        // 處理搜尋
        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        // 獲取評論並帶相關關聯
        $comments = $query->with(['activity:id,title', 'parent.user:id,name', 'parent:id,content,user_id'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('member.comments', compact('comments'));
    }
}

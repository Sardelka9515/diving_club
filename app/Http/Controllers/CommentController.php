<?php

namespace App\Http\Controllers;

use App\Notifications\NewComment;
use App\Models\User;
use App\Models\Comment;
use App\Models\Activity;
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
}

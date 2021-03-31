<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\Comment;

class CommentsController extends Controller
{
    public function store(Request $request, $id)
    {
        $message = Message::find($id);
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->user_id = Auth::id();
        $comment->message_id = $message->id;
        $comment->save();
        return redirect('/messages/show/'. $id);
    }
}

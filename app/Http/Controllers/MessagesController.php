<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Message;
use App\Models\User;

class MessagesController extends Controller
{
    public function index()
    {
        return view('messages.index');
    }

    public function create()
    {
        return view('messages.create');
    }

    public function store(Request $request)
    {
        
        $message = new Message();
        $form = $request->all();
        $message->user_id = Auth::id();
        unset($form['_token']);
        $message->fill($form)->save();
        return redirect('/messages/index')
            ->with('message', '投稿が完了しました。');
    }
}

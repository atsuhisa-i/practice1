<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Message;
use App\Models\User;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::with('user')->orderBy('created_at', 'desc')->get();
        return view('messages.index', ['messages' => $messages]);
    }

    public function create()
    {
        return view('messages.create');
    }

    public function store(Request $request)
    {
        $alert = [
            'title.required' => 'タイトルは必ず入力してください。',
            'content.required' => 'メッセージは必ず入力してください。',
        ];

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required'
        ], $alert);
        
        if($validator->fails()){
            return redirect('/messages/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $message = new Message();
            $form = $request->all();
            $message->user_id = Auth::id();
            unset($form['_token']);
            $message->fill($form)->save();
            return redirect('/messages/index')
                ->with('message', '投稿が完了しました。');
        }
    }

    public function show($id)
    {
        $message = Message::find($id);
        return view('messages.show')->with('message', $message);

    }
}

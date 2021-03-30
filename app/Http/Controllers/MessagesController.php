<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        }elseif($request->has('image')){
            $message = new Message();
            $message->title = $request->title;
            $message->content = $request->content;
            $getfile = $request->file('image');
            $path = Storage::disk('public')->putFile('', $getfile);
            $message->user_id = Auth::id();
            $message->image = $path;
            $message->save();
            return redirect('/messages/index')
                ->with('message', '投稿が完了しました。');
        }else{
            $message = new Message();
            $form = $request->all();
            $message->user_id = Auth::id();
            $message->fill($form)->save();
            return redirect('/messages/index')
                ->with('message', '投稿が完了しました。');
        }
    }

    public function show($id)
    {
        $message = Message::find($id);
        Storage::disk('local')->exists('public/storage/'.$message->image);
        return view('messages.show')->with('message', $message);

    }

    public function edit($id)
    {
        $message = Message::find($id);
        return view('messages.edit')->with('message', $message);
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);

        $alert = [
            'title.required' => 'タイトルは必ず入力してください。',
            'content.required' => 'メッセージは必ず入力してください。',
        ];

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required'
        ], $alert);
        
        if($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }elseif(request('image')){
            $message->title = $request->title;
            $message->content = $request->content;
            $getfile = $request->file('image');
            $path = Storage::disk('public')->putFile('', $getfile);
            $message->user_id = Auth::id();
            $message->image = $path;            
            $message->save();
            return redirect('/messages/index')
                ->with('message', '更新が完了しました。');
        }else{
            $message = new Message();
            $form = $request->all();
            $message->user_id = Auth::id();
            $message->fill($form)->save();
            return redirect('/messages/index')
                ->with('message', '更新が完了しました。');
        }
    }

    public function destroy($id)
    {
        $message = Message::find($id);
        $message->delete();
        return redirect('/messages/index');
    }
}

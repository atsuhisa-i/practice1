<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Message;
use App\Models\User;
use App\Models\Comment;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $search_name = $request->input('search_name');
        $search_title = $request->input('search_title');

        if(!empty($search_name) && !empty($search_title)){
            $user_name = User::where('name', 'like', '%'.$search_name.'%')->get();
            foreach($user_name as $user){
                $user_id = $user->id;
            }
            $messages = Message::where('user_id', "$user_id")
                                ->where('title', 'like', '%'.$search_title.'%')
                                ->with('user')->orderby('created_at', 'desc')->paginate(5);
        }
        else if(!empty($search_title) && empty($search_name)){
            $messages = Message::where('title', 'like', '%'.$search_title.'%')
                                ->with('user')
                                ->orderby('created_at', 'desc')
                                ->paginate(5);
        }
        else if(!empty($search_name) && empty($search_title)){
            $user_name = User::where('name', 'like', '%'.$search_name.'%')->get();
            // get()で取得した値はコレクションインスタンスのため、foreachを使用して各値を取得する必要あり。
            foreach($user_name as $user){
                $user_id = $user->id;
            }
            $messages = Message::where('user_id', "$user_id")
                                ->with('user')
                                ->orderby('created_at', 'desc')
                                ->paginate(5);
        }
        else{
            $messages = Message::with('user')
                                ->orderby('created_at', 'desc')
                                ->paginate(5);
        }
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
        $comments = Message::find($id)->comments;
        // 下記はreturn view('messages.show', compact('comments', 'message'));という記述も可能（上記のfind()で取得した値をビューに渡す）。
        // 他の同様の箇所も変更可能。
        return view('messages.show', ['comments' => $comments])->with('message', $message);
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

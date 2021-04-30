<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Validator;
use SplFileObject;
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

    public function download(Request $request)
    {
        $messages = Message::get()->toArray();
        $columns =[
            ["id", "title", "content", "image", "user_id", "created_at", "updated_at"]
        ];
        $data = array_merge($columns, $messages);
        
        $response = new StreamedResponse(function() use ($request, $data){
            $stream = fopen('php://output', 'w');
            //TRANSLITは、変換ができない文字が含まれていた場合に文字化けが起こるので、これで強制的に変換。
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            foreach($data as $key => $value){
                fputcsv($stream, $value);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="test.csv"');

        return $response;
    }

    public function import(Request $request)
    {
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        $uploaded_file = $request->file('csv_file');
        $file_path = $request->file('csv_file')->path($uploaded_file);
        $file = new SplFileObject($file_path);
        $file->setFlags(SplFileObject::READ_CSV);

        $array = [];
        $row_count = 1;

        foreach($file as $row)
        {
            
            if($row === [null])continue;

            if($row_count > 1)
            {
                $title = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
                $content = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
                $image = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
                $user_id = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
                $created_at = mb_convert_encoding($row[4], 'UTF-8', 'SJIS');
                $updated_at = mb_convert_encoding($row[5], 'UTF-8', 'SJIS');

                $csvimport_array = [
                    'title' => $title,
                    'content' => $content,
                    'image' => $image,
                    'user_id' => $user_id,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at
                ];

                array_push($array, $csvimport_array);
            }
            $row_count++;
        }
        ddd($array);
        $array_count = count($array);

        if($array_count < 500){
            Message::insert($array);
        }else{
            $array_partial = array_chunk($array, 500);
            $array_partial_count = count($array_partial);
            for($i = 0; $i <= $array_partial_count - 1; $i++){
                Message::insert($array_partial[$i]);
            }
        }

        return redirect('/messages/index')
                ->with('message', 'インポートが完了しました。');
    }

    public function json(Request $request)
    {
        $messages = Message::get();
        //下記の['messages' => $messages]の右横に記載の200, [], JSON_UNESCAPED_UNICODEは、日本語を
        //そのまま出力したい場合に記載する（JavaScriptで使用する場合は必要ない）。
        return response()->json(['messages' => $messages], 200, [], JSON_UNESCAPED_UNICODE);

        //上記はlaravelのjsonメソッドを使用した書き方で、下記はPHPの関数を使用したもの。
        //日本語をそのまま出力したい場合は、$messagesの右横に、JSON_UNESCAPED_UNICODEを記載。
        // return json_encode($messages);

    }

    public function json_import(Request $request)
    {
        $uploaded_file = $request->file('json_file');
        $file_path = $request->file('json_file')->path($uploaded_file);
        $json = file_get_contents($file_path); //JSONデータを全て文字列に読み込む
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $json_arr = json_decode($json, true); //JSONデータを連想配列にする。第2引数のtrueが無いと連想配列にならない。
        $collapsed_arr = Arr::collapse($json_arr); //配列の配列を一次元の配列へ展開。これが無いとArray to string conversionのエラーになる。
        $array_count = count($collapsed_arr);

        if($array_count < 500){
            Message::insert($collapsed_arr);
        }else{
            $array_partial = array_chunk($collapsed_arr, 500);
            $array_partial_count = count($array_partial);
            for($i = 0; $i <= $array_partial_count - 1; $i++){
                Message::insert($array_partial[$i]);
            }
        }

        return redirect('/messages/index')
                ->with('message', 'インポートが完了しました。');
        
    }
}

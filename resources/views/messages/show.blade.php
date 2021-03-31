@extends('layouts.app')

@section('content')
<div>
    <table border="1">
        <tr><th>タイトル</th><td>{{$message->title}}</td></tr>
        <tr><th>投稿者</th><td>{{$message->user->name}}</td></tr>
        <tr><th>内容</th><td>{{$message->content}}</td></tr>
        @if(empty($message->image))
            <tr><th>画像</th><td>画像はありません。</td></tr>
        @else
            <tr><th>画像</th><td><img src="{{ Storage::url($message->image)}}"></td></tr>
        @endif
    </table>
    @if($message->user_id == Auth::id())
        <div>
            <button><a href="/messages/edit/{{$message->id}}">編集</a></button>
            <form method="post" action="/messages/delete/{{$message->id}}">
                @csrf
                {{method_field('DELETE')}}
                <input type="submit" value="削除" onclick='return confirm("削除してもよろしいですか？");'>
            </form>
        </div>
    @endif
    <form action="/comment/store/{{$message->id}}" method="POST">
        @csrf
        <div>
            <label for="comment">コメントする</label><br>
            <textarea name="comment" id="comment" cols="80" rows="10" placeholder="ここにコメントを記入してください。"></textarea>
        </div>
        <div>
            <button type="submit">送信</button>
        </div>
    </form>
    <div>
        <div>コメント一覧</div>
        @foreach($comments as $comment)
            <div>
                <div>名前　　：{{$comment->user->name}}</div>
                <div>コメント：{{$comment->comment}}</div>
                <div>送信日時：{{$comment->created_at}}</div>
            </div>
        @endforeach
    </div>
    <div>
        <a href="/messages/index">投稿一覧に戻る</a>
    </div>

</div>
@endsection
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
            <tr><th>画像</th><td>{{$message->image}}</td></tr>
        @endif
    </table>
    @if($message->user_id == Auth::id())
        <div>
            <button><a href="#">編集</a></button>
            <button><a href="#">削除</a></button>
        </div>
    @endif
    <div>
        <div>コメント一覧</div>
        <div></div>
    </div>
    <div>
        <a href="/messages/index">投稿一覧に戻る</a>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('content')
<div>
    <h3>登録内容</h3>
    <table border="1">
        <tr><th>ID</th><td>{{$user->id}}</td></tr>
        <tr><th>名前</th><td>{{$user->name}}</td></tr>
        <tr><th>E-mail</th><td>{{$user->email}}</td></tr>
    </table>
</div>
<br>
<div>
    <button><a href="/user/edit/{{Auth::id()}}">編集</a></button>
    <form method="post" action="/user/delete/{{Auth::id()}}">
        @csrf
        {{method_field('DELETE')}}
        <input type="submit" value="削除" onclick='return confirm("削除してもよろしいですか？");'>
    </form>
</div>
<br>
<div>
    <a href="/messages/index">投稿一覧に戻る</a>
</div>
@endsection
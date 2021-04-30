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
<div>
    <a href="/group/index">登録グループ一覧</a>
</div>
<br>
<div>
    <a href="/messages/index">投稿一覧に戻る</a>
</div>
<a href="/user/download">登録済みユーザー情報のダウンロード</a>
<div>CSVファイルのインポート</div>
<form action="/user/import" method="POST" enctype="multipart/form-data">
    <input type="file" name="svc_file" id="svc_file">
    <div>
        <button type="submit">保存</button>
    </div>
</form>
@endsection
@extends('layouts.app')

@section('content')
<div>
    <a href="/group/create">新しくグループを作成する</a>
</div>

<h2>登録グループ一覧</h2>
<div>
    <table border=1>
        <tr><th>グループ名</th><th>詳細</th></tr>
        @foreach($groups as $group)
        <tr><td>{{$group->name}}</td><td><a href="group/show/{{$group->id}}">確認</a></td></tr>
        @endforeach
    </table>
</div>



@endsection
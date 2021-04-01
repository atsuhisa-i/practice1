@extends('layouts.app')

@section('content')
@if(session('message'))
<div>
    {{ session('message') }}
</div>
@endif
<div>
    <div class="inner">
        <button type="button">
            <a href={{route('create')}}> 投稿の新規作成</a>
        </button>
    </div>
</div>
<form>
    <div>
        <label for="search">タイトル検索：
            <input type="search" name="search" id="search" value="{{request('search')}}" placeholder="キーワードを入力">
        </label>
        <input type="submit" value="検索">
    </div>  
</form>
@if($messages->isEmpty())
<p>投稿がありません。</p>
@else
<table border="1">
    <tr>
        <th>id</th><th>投稿者</th><th>タイトル</th><th>作成日</th><th>更新日</th><th>処理</th>
    </tr>
    @foreach($messages as $message)
        <tr>
            <td>{{$message->id}}</td>
            <td>{{$message->user->name}}</td>
            <td>{{$message->title}}</td>
            <td>{{$message->created_at}}</td>
            <td>{{$message->updated_at}}</td>
            <td>
                <button><a href="/messages/show/{{$message->id}}">詳細</a></button>                
            </td>
        </tr>
    @endforeach
</table>
<p>
    {{ $messages->links('vendor.pagination.bootstrap-4')}}
</p>
@endif
@endsection
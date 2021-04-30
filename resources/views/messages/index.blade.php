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
</div><br>
<form action="/messages/index" method="GET">
    <div>
        <div>検索フォーム</div>
        <label for="search_name">投稿者：
            <input type="search" name="search_name" id="search_name" value="{{request('search_name')}}">        
        </label>
        <label for="search_title">タイトル：
            <input type="search" name="search_title" id="search_title" value="{{request('search_title')}}">
        </label>
        <input type="submit" value="検索">
        <!-- <label for="search">タイトル検索：
            <input type="search" name="search" id="search" value="{{request('search')}}" placeholder="キーワードを入力">
        </label>
        <input type="submit" value="検索"> -->
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
            <td>{{$message->created_at->format('Y年m月d日(D)H:i')}}</td>
            <td>{{$message->updated_at->format('Y年m月d日(D)H:i')}}</td>
            <td>
                <button><a href="/messages/show/{{$message->id}}">詳細</a></button>                
            </td>
        </tr>
    @endforeach
</table>
<p>
    <!-- 検索結果を保持したままページネーションをしたい場合、下記のようにappends(request()->input())を加える。 -->
    {{ $messages->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
</p>
<div>
    <a href="/messages/download">各投稿の内容をダウンロード</a>
</div>
<div>
    <a href="/messages/json">各投稿の内容をjson形式で出力</a>
</div>
<div>csvファイルのインポート</div>
<form action="/messages/import" method="POST" enctype="multipart/form-data">
@csrf
    <input type="file" name="csv_file" id="csv_file">
    <div>
        <button type="submit">保存</button>
    </div>
</form>
<div>jsonファイルのインポート</div>
<form action="/messages/json_import" method="POST" enctype="multipart/form-data">
@csrf
    <input type="file" name="json_file" id="json_file">
    <div>
        <button type="submit">保存</button>
    </div>
</form>

@endif
@endsection
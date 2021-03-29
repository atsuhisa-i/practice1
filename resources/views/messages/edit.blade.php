@extends('layouts.app')
@section('content')
<div class="m-container">
    @if(count($errors) > 0)
    <p>入力に問題があります。再入力してください。</p>
    @endif
    <form action='/messages/update/{{$message->id}}' method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        @if($errors->has('title'))
        <tr><th>ERROR：</th><td>{{$errors->first('title')}}</td></tr>
        @endif
        <div>
            <label for="title">タイトル</label><br>
            <input type="text" name="title" id="title" value="{{$message->title}}" class="">
        </div>
        @if($errors->has('content'))
        <tr><th>ERROR：</th><td>{{$errors->first('content')}}</td></tr>
        @endif
        <div>
            <label for="content">メッセージ</label><br>
            <textarea name="content" id="content" cols="100" rows="10" placeholder="ここにメッセージを記入してください。">{{$message->content}}</textarea>
        </div>
        <div>
            <label for="image">画像（任意）</label><br>
            <input type="file" name="image" value="{{$message->image}}" accept="image/png,image/jpeg,image/gif">
        </div>
        <div>
            <button　type="submit">更新</button>
        </div>
    </form>
    <div>
        <a href="/messages/show/{{$message->id}}">詳細ページへ戻る</a>
    </div>    
</div>
@endsection

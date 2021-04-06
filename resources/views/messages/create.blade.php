@extends('layouts.app')

@section('content')
<div class="m-container">
    @if(count($errors) > 0)
    <p>入力に問題があります。再入力してください。</p>
    @endif
    <form action="{{ url('/messages/store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($errors->has('title'))
        <tr><th>ERROR：</th><td>{{$errors->first('title')}}</td></tr>
        @endif
        <div>
            <label for="title">タイトル</label><br>
            <input type="text" name="title" id="title" value="{{old('title')}}" class="">
        </div>
        @if($errors->has('content'))
        <tr><th>ERROR：</th><td>{{$errors->first('content')}}</td></tr>
        @endif
        <div>
            <label for="content">メッセージ</label><br>
            <textarea name="content" id="content" value="{{old('content')}}" cols="100" rows="10" placeholder="ここにメッセージを記入してください。"></textarea>
        </div>
        <div>
            <label for="image">画像（任意）</label><br>
            <input type="file" name="image" accept="image/png,image/jpeg,image/gif">
        </div>
        <br>
        <div>
            <button　type="submit">新規投稿</button>
        </div>
    </form>
    <br>
    <div>
        <a href="/messages/index">投稿一覧に戻る</a>
    </div>    
</div>
@endsection
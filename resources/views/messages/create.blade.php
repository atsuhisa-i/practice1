@extends('layouts.app')

@section('content')
<div class="m-container">
    <form action="{{ url('/messages/store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="title">タイトル</label><br>
            <input type="text" name="title" id="title" class="">
        </div>
        <div>
            <label for="content">メッセージ</label><br>
            <textarea name="content" id="content" cols="100" rows="10" placeholder="ここにメッセージを記入してください。"></textarea>
        </div>
        <div>
            <label for="image">画像（任意）</label><br>
            <input type="file" name="image" accept="image/png,image/jpeg,image/gif">
        </div>
        <div>
            <button　type="submit">新規投稿</button>
        </div>
    </form>    
</div>
@endsection
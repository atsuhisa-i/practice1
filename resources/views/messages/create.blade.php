@extends('layouts.app')

@section('content')
<div class="m-container">
    <form action="#" method="POST">
        <div>
            <label for="title">タイトル</label><br>
            <input type="text" id="title" class="">
        </div>
        <div>
            <label for="message">メッセージ</label><br>
            <textarea name="message" id="message" cols="100" rows="10">ここにメッセージを記入してください。</textarea>
        </div>
        <div>
            <button　type="submit">新規投稿</button>
        </div>
    </form>    
</div>
@endsection
@extends('layouts.app')

@section('content')
<div>
    <div class="inner">
        <button type="button">
            <a href={{route('create')}}> 投稿の新規作成</a>
        </button>
    </div>
</div>

@endsection
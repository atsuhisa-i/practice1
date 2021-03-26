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

@endsection
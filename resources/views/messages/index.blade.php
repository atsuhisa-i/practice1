@extends('layouts.app')

@section('content')
<div>
    <button type="button">
        <a href={{route('create')}}> 投稿の新規作成</a>
    </button>
</div>

@endsection
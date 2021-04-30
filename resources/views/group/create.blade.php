@extends('layouts.app')

@section('content')
<div>
    <div>
        <label for="group_name">グループ名</label>
        <!-- formタグにformタグを入れ子にすることはできないため、inputタグにform要素を記入し、該当するid要素をもつ
        formタグと関連付けすることで、formタグの入れ子を解消。 -->
        <input type="text" name="name" id="group_name" value="{{old('name')}}" form="group_form">
    </div>
    <form action="/group/create" method="GET">
        <div>
            <label for="member">メンバー</label>
            <p>{{$current_user->name}}</p>
            <input type="hidden" name="user_id[]" value="{{Auth::id()}}" form="group_form">
        </div>
        <div>追加したいユーザーを検索</div>
        <label for="search_user"></label>
        <input type="search" name="search_user" id="search_user" value="{{request('search_user')}}">
        <input type="submit" value="検索">
    </form>
    @if($users->isEmpty())
    <p>該当するユーザーがいません。</p>
    @else
    <table border="1">
        <tr>
            <th>id</th><th>ユーザー名</th><th>追加</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>
                    <input type="checkbox" name="user_id[]" value="{{$user->id}}" form="group_form">
                </td>
            </tr>
        @endforeach
    </table>
    @endif
    <form action="{{ url('/group/store') }}" method="POST" id="group_form">
    @csrf
        <button type="submit">グループ作成</button>
    </form>
</div>
@endsection
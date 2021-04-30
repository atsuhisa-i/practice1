<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Models\Group;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::get();
        return view('group.index', ['groups' => $groups]);
    }

    public function show($id)
    {
        $group = Group::find($id);
    }

    public function create(Request $request)
    {
        $current_user = Auth::user();
        $search_name = $request->input('search_user');
        if(!empty($search_name)){
            $users = User::where('name', 'like', '%'.$search_name.'%')
                           ->whereNotIn('id', [Auth::id()])
                           ->get();
        }
        else{
            $users = User::whereNotIn('id', [Auth::id()])
            ->get();
        }
        return view('group.create', ['users' => $users, 'current_user' => $current_user]);
    }

    public function store(Request $request)
    {
        $alert = [
            'name.required' => 'グループ名を入力してください。',
            'user_id.required' => '追加するユーザーを選択してください。',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'user_id' => 'required'
        ], $alert);

        if($validator->fails()){
            return redirect('/group/create')
                    ->withErrors($validator)
                    ->withInput();
        }else{
            $group = new Group();
            $group->name = $request->name;
            unset($request['_token']);
            $group->save();
            $group->users()->attach($request->user_id);
            return redirect('/group/index')
                ->with('message', 'グループの作成が完了しました。');
        }
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}

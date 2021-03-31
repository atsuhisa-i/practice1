<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        return view('/user/show')->with('user', $user);
    }

    public function edit($id)
    {
        $user = Auth::user();
        return view('/user/edit')->with('user', $user);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validator->fails()){
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect('/user/show/'. $id)
                ->with('message', '変更が完了しました。');
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $user->delete();
        return redirect('/');
    }
}
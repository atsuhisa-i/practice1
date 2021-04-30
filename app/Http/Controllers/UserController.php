<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;
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

    public function download(Request $request)
    {
        $users = User::get()->toArray();
        $columns = [
            ['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']
        ];
        $data = array_merge($columns, $users);
        $response = new StreamedResponse(function() use ($request, $data){
            $stream = fopen('php://output', 'w');
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            foreach($data as $key => $value){
                fputcsv($stream, $value);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="user.csv"');

        return $response;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Psr\Http\Message\ResponseInterface;

class AdministratorsController extends Controller
{
    public function add(Request $req){

        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = new User();

        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = '12345678';

        $user->save();

        return response()->json(['success'=> true, 'message'=> 'New Admin is successfully Created', 'pass'=> '12345678']);
    }

    public function update(Request $req){
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = User::where('id', $req->id)->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User not found']);
        }

        $user->update([
            'name' => $req->name,
            'email'=> $req->email
        ]);

        return response()->json(['success'=> true, 'message'=> 'User Successfully Updated']);
    }

    public function delete(Request $req){
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = User::where('id', $req->id)->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User not found']);
        }

        $user->delete();

        return response()->json(['success'=> true, 'message'=> 'User is successfully deleted']);
    }

    public function changepass(Request $req){
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = User::where('id', $req->id)->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User not found']);
        }

        $user->update([
            'password'=> Hash::make($req->password)
        ]);

        return response()->json(['success'=> true, 'message'=> 'User password has been changed successfully']);
    }

    public function list(){
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $users = User::where('user_type', 'Admin')->get();

        return response()->json(['success'=> true, 'data'=> $users]);
    }
    public function transferstatus(Request $req){
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = User::where('id', Auth::id())->first();
        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User not found']);
        }

        if(Hash::check($req->password, $user->password)){
            $user->update([
                'user_type' => 'Admin',
            ]);

            $newSuperAdmin = User::where('id', $req->id)->first();

            $newSuperAdmin->update([
                'user_type'=> 'Super Admin',
            ]);

            return response()->json(['success'=> true, 'message'=> 'Super Admin Status is successfully transferred']);
        }else{
            return response()->json(['success'=> false, 'message'=> 'You entered an incorrect password']);
        }
    }

    private function levelCheck(){
        $check = Auth::id();

        $checkUser = User::where('id', $check)->where('user_type', 'Super Admin')->first();

        if(!$checkUser){
            return false;
        }

        return true;
    }


}

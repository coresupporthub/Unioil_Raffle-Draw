<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\Tools;

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

        $response = ['success'=> true, 'message'=> 'New Admin is successfully Created', 'pass'=> '12345678'];
        Tools::Logger($req, ['Add New Administrator', "Successfully added {$req->name} in the admin list"], $response);

        return response()->json($response);
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

        $response = ['success'=> true, 'message'=> 'User Successfully Updated'];
        Tools::Logger($req, ['Update Administrator', "Update admin details"], $response);

        return response()->json($response);
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

        $response = ['success'=> true, 'message'=> 'User is successfully deleted'];
        Tools::Logger($req, ['Delete Admin', "Admin has been deleted"], $response);

        return response()->json($response);
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

        $response = ['success'=> true, 'message'=> 'User password has been changed successfully'];
        Tools::Logger($req, ['Change Password Admin', "Admin {$user->name}'s password is changed"], $response);

        return response()->json($response);
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

            $response = ['success'=> true, 'message'=> 'Super Admin Status is successfully transferred'];
            Tools::Logger($req, ['Super Admin Status Transfer', "Super Admin Status is transferred to {$newSuperAdmin->name}"], $response);

            return response()->json($response);
        }else{
            $response = ['success'=> false, 'message'=> 'You entered an incorrect password'];
            Tools::Logger($req, ['Super Admin Status Transfer', "Super Admin Status Transfer Failed: Incorrect Admin Password"], $response);
            return response()->json($response);
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

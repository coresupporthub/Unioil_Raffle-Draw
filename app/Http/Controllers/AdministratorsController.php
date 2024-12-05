<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\Tools;
use Illuminate\Http\JsonResponse;
class AdministratorsController extends Controller
{
    public function add(Request $req): JsonResponse{

        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = new User();

        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = '12345678';

        $user->save();

        $response = ['success'=> true, 'message'=> 'New Admin is successfully Created', 'pass'=> '12345678'];
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];

        Tools::Logger($request, $req->all(), ['Add New Administrator', "Successfully added $req->name in the admin list"], $response);

        return response()->json($response);
    }

    public function update(Request $req): JsonResponse{
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

        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];

        Tools::Logger($request, $req->all(), ['Update Administrator', "Update admin details"], $response);

        return response()->json($response);
    }

    public function delete(Request $req): JsonResponse{
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = User::where('id', $req->id)->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User not found']);
        }

        $user->delete();

        $response = ['success'=> true, 'message'=> 'User is successfully deleted'];
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        Tools::Logger($request , $req->all(), ['Delete Admin', "Admin has been deleted"], $response);

        return response()->json($response);
    }

    public function changepass(Request $req): JsonResponse{
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
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        Tools::Logger($request, $req->all(), ['Change Password Admin', "Admin {$user->name}'s password is changed"], $response);

        return response()->json($response);
    }

    public function list(): JsonResponse{
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $users = User::where('user_type', 'Admin')->get();

        return response()->json(['success'=> true, 'data'=> $users]);
    }
    public function transferstatus(Request $req): JsonResponse{
        if(!$this->levelCheck()){
            return response()->json(['success'=> false, 'message'=> 'You are not eligible for this action']);
        }

        $user = User::where('id', Auth::id())->first();
        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User not found']);
        }
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        if(Hash::check($req->password, $user->password)){
            $newSuperAdmin = User::where('id', $req->id)->first();

            if(!$newSuperAdmin){
                return response()->json(['success'=> false, 'message'=> 'No Admin Found']);
            }

            $newSuperAdmin->update([
                'user_type'=> 'Super Admin',
                'backup_automate' => $user->backup_automate
            ]);

            $user->update([
                'user_type' => 'Admin',
                'backup_automate' => null
            ]);

            $response = ['success'=> true, 'message'=> 'Super Admin Status is successfully transferred'];

            Tools::Logger($request, $req->all(), ['Super Admin Status Transfer', "Super Admin Status is transferred to {$newSuperAdmin->name}"], $response);

            return response()->json($response);
        }else{
            $response = ['success'=> false, 'message'=> 'You entered an incorrect password'];
            Tools::Logger($request, $req->all(), ['Super Admin Status Transfer', "Super Admin Status Transfer Failed: Incorrect Admin Password"], $response);
            return response()->json($response);
        }
    }

    private function levelCheck(): bool{
        $check = Auth::id();

        $checkUser = User::where('id', $check)->where('user_type', 'Super Admin')->first();

        if(!$checkUser){
            return false;
        }

        return true;
    }


}

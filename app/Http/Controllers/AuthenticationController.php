<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Services\Tools;
use App\Jobs\SendVerification;

class AuthenticationController extends Controller
{
    public function signin(Request $req)
    {
        $data = [
            'email' => $req->email,
            'password' => $req->password
        ];

        if (Auth::attempt($data)) {
            $req->session()->regenerate();
            $user = User::where('id', Auth::id())->first();

            $verificationCode = Tools::genCode(6, 'numeric');

            SendVerification::dispatch($user->email, $verificationCode);

            $user->update([
                'verification_code' => $verificationCode
            ]);

            return response()->json(['success' => true, 'message' => 'Authentication is successful']);
        } else {
            return response()->json(['success' => false, 'message' => "Email and password does not match"]);
        }
    }

    public function getauth(Request $req){
        $auth = Auth::id();

        $user = User::where('id', $auth)->first();

        return response()->json(['auth'=> $user]);
    }

    public function verifyuser(Request $req){
        $code = $req->code1. $req->code2. $req->code3 . $req->code4 . $req->code5. $req->code6;

        $user = User::where('id', Auth::id())->first();

        if($user->verification_code == $code){
            $user->update([
                'authenticated'=> 'true',
                'verification_code' => ''
            ]);

            return response()->json(['success'=> true, 'message'=> 'User authentication verified']);
        }else{
            return response()->json(['success'=> false, 'message'=> 'Verification code does not match']);
        }
    }

    public function logout(Request $req){
        $user = User::where('id', Auth::id())->first();

        $user->update([
            'verification_code' => null,
            'authenticated' => 'false'
        ]);

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return response()->json(['success'=> true]);
    }

    public function resendcode(Request $req){

        $user = User::where('id', Auth::id())->first();

        $verificationCode = Tools::genCode(6, 'numeric');

        SendVerification::dispatch($user->email, $verificationCode);

        return response()->json(['status'=> true, 'message'=> 'Verification Code Resent']);
    }
}

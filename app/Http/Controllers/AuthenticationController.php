<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Services\Tools;
use App\Jobs\SendVerification;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\Magic;
class AuthenticationController extends Controller
{
    public function signin(Request $req)
    {
        $data = [
            'email' => $req->email,
            'password' => $req->password
        ];

        $check = User::where('email', $req->email)->first();

        if($check){
            if(Magic::MAX_LOGIN_ATTEMPT > $check->login_attemt){
                $check->update([
                    'login_attempt' => $check->login_attempt + 1
                ]);
            }else{
                return response()->json(['success'=> false, "You have reached your max login attempt with incorrect password"]);
            }
        }

        if (Auth::attempt($data)) {
            $req->session()->regenerate();

            $user = User::where('id', Auth::id())->first();

            if($user->authenticated == 'true'){
                return response()->json(['status'=> true, 'message'=> 'Authentication is successful', 'redirect'=> true]);
            }

            $verificationCode = Tools::genCode(6, 'numeric');

            SendVerification::dispatch($user->email, $verificationCode);

            $user->update([
                'verification_code' => $verificationCode
            ]);

            return response()->json(['success' => true, 'message' => 'Authentication is successful', 'redirect'=> false]);
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
                'verification_code' => null
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

        if(Magic::MAX_VERIFY_RESEND > $user->resend_attempt){

            $verificationCode = Tools::genCode(6, 'numeric');

            $user->update([
                'resend_attempt' => $user->resend_attempt + 1,
                'verification_code' => $verificationCode
            ]);

            SendVerification::dispatch($user->email, $verificationCode);

            return response()->json(['success'=> true, 'message'=> 'Verification Code Resent']);
        }else{
            return response()->json(['success'=> false, 'message' => 'You have reach your resend limit']);
        }

    }

    public function getadmindetails(Request $req){
        $user = User::where('id', Auth::id())->first();

        return response()->json(['info'=> $user]);
    }

    public function changepassword(Request $req){
        if($req->confirmPassword != $req->newPassword){
            return response()->json(['success'=> false, 'message'=> 'New Password and Confirm Password does not match']);
        }

        $user = User::where('id', Auth::id())->first();

        if(Hash::check($req->currentPassword, $user->password)){
            $user->update([
                'password'=> Hash::make($req->newPassword)
            ]);

            return response()->json(['success'=> true, 'message'=> 'Password Successfully Changed']);
        }else{
            return response()->json(['success'=> false, 'message'=> 'Cannot Validate: Incorrect Current Admin Password']);
        }
    }

    public function updateadmin(Request $req){
        $user = User::where('id', Auth::id())->first();

        $user->update([
            'name'=> $req->name,
            'email' => $req->email
        ]);

        return response()->json(['success'=> true, 'message'=> 'Admin Details Updated']);
    }
}

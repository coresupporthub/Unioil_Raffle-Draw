<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Services\Tools;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCode;
class AuthenticationController extends Controller
{
    public function signin(Request $req){
        $data = [
            'email'=> $req->email,
            'password' => $req->password
        ];

        if(Auth::attempt($data)){
            $req->session()->regenerate();
            $user = User::where('id',Auth::id())->first();

            $verificationCode = Tools::genCode(6, 'numeric');

            Mail::to($user->email)->send(new VerificationCode($verificationCode));

            $user->update([
                'verification_code' => $verificationCode
            ]);

            return response()->json(['success'=> true, 'message' => 'Authentication is successful']);
        }else{
            return response()->json(['success'=> false, 'message' => "Email and password does not match"]);
        }
    }
}

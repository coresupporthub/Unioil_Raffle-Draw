<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Services\Tools;
use App\Jobs\SendVerification;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\Magic;
use Illuminate\Http\JsonResponse;
class AuthenticationController extends Controller
{
    public function signin(Request $req): JsonResponse
    {
        $data = [
            'email' => $req->email,
            'password' => $req->password
        ];
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        if (Auth::attempt($data)) {
            $req->session()->regenerate();

            $user = User::where('id', Auth::id())->first();

            if(!$user){
                return response()->json(['success'=> false, 'message'=> 'No User Found']);
            }

            if ($user->authenticated == 'true') {
                return response()->json(['success' => true, 'message' => 'Authentication is successful', 'redirect' => true]);
            }

            $verificationCode = Tools::genCode(6, 'numeric');

            SendVerification::dispatch($user->email, $verificationCode);

            $user->update([
                'verification_code' => $verificationCode
            ]);

            $response = ['success' => true, 'message' => 'Authentication is successful', 'redirect' => false];

            Tools::Logger($request,$req->all(), ['Login Attempt', "Successfully logged in to the admin dashboard"], $response);

            return response()->json($response);
        } else {

            $check = User::where('email', $req->email)->first();
            $response = ['success' => false, 'message' => "Email and password does not match", 'redirect' => false];
            if ($check) {
                if (Magic::MAX_LOGIN_ATTEMPT > $check->login_attempt) {
                    $check->update([
                        'login_attempt' => $check->login_attempt + 1
                    ]);
                } else {
                    $response = ['success' => false, 'message'=>"You have reached your max login attempt with incorrect password or email", 'redirect' => false];
                    Tools::Logger($request, $req->all(), ['Login Attempt', "Has Reached Attempt Maximum Limit"], $response);

                    return response()->json($response);
                }
                Tools::Logger($request, $req->all(), ['Login Attempt', "Failed to logged in to the admin dashboard"], $response, $check->id);
            }

            return response()->json($response);
        }
    }

    public function getauth(Request $req): JsonResponse
    {
        $auth = Auth::id();

        $user = User::where('id', $auth)->first();

        return response()->json(['auth' => $user]);
    }

    public function verifyuser(Request $req): JsonResponse
    {
        $code = $req->code1 . $req->code2 . $req->code3 . $req->code4 . $req->code5 . $req->code6;

        $user = User::where('id', Auth::id())->first();
        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'No User Found']);
        }
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        if ($user->verification_code == $code) {
            $user->update([
                'authenticated' => 'true',
                'verification_code' => null
            ]);

            $response = ['success' => true, 'message' => 'User authentication verified'];
            Tools::Logger($request, $req->all(), ['Email Verification Attempt', "Email has been successfully Verified"], $response);

            return response()->json($response);
        } else {

            if($user->verification_attempt < Magic::MAX_VERIFICATION_ATTEMPT){
                $user->update([
                    'verification_attempt' => $user->verification_attempt + 1,
                ]);
            }else{

                $response = ['success' => false, 'message' => 'You have reached your maximum verification attempts'];
                Tools::Logger($request, $req->all(), ['Email Verification Attempt', "You have reached your maximum verification attempts"], $response);

                return response()->json($response);
            }

            $response = ['success' => false, 'message' => 'Verification code does not match'];
            Tools::Logger($request, $req->all(), ['Email Verification Attempt', "Verification Does Not Match"], $response);

            return response()->json($response);
        }
    }

    public function logout(Request $req): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'No User Found']);
        }

        $user->update([
            'verification_code' => null,
            'authenticated' => 'false'
        ]);
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];

        $response = ['success' => true];
        Tools::Logger($request, $req->all(), ['User Logged Out', "User has successfully Logged Out"], $response);

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return response()->json($response);
    }

    public function resendcode(Request $req): JsonResponse
    {

        $user = User::where('id', Auth::id())->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'No User Found']);
        }

        if (Magic::MAX_VERIFY_RESEND > $user->resend_attempt) {

            $verificationCode = Tools::genCode(6, 'numeric');

            $user->update([
                'resend_attempt' => $user->resend_attempt + 1,
                'verification_code' => $verificationCode
            ]);

            SendVerification::dispatch($user->email, $verificationCode);

            return response()->json(['success' => true, 'message' => 'Verification Code Resent']);
        } else {
            return response()->json(['success' => false, 'message' => 'You have reach your resend limit']);
        }
    }

    public function getadmindetails(Request $req): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();

        return response()->json(['info' => $user]);
    }

    public function changepassword(Request $req): JsonResponse
    {
        if ($req->confirmPassword != $req->newPassword) {
            return response()->json(['success' => false, 'message' => 'New Password and Confirm Password does not match']);
        }

        $user = User::where('id', Auth::id())->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'No User Found']);
        }

        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        if (Hash::check($req->currentPassword, $user->password)) {
            $user->update([
                'password' => Hash::make($req->newPassword)
            ]);

            $response = ['success' => true, 'message' => 'Password Successfully Changed'];
            Tools::Logger($request, $req->all(), ['Admin Change Password', "Admin has changed the account password"], $response);

            return response()->json($response);
        } else {
            return response()->json(['success' => false, 'message' => 'Cannot Validate: Incorrect Current Admin Password']);
        }
    }

    public function updateadmin(Request $req): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        if(!$user){
            return response()->json(['success'=>false, 'message'=> 'No User Found']);
        }
        $user->update([
            'name' => $req->name,
            'email' => $req->email
        ]);

        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        $response = ['success' => true, 'message' => 'Admin Details Updated'];
        Tools::Logger($request, $req->all(), ['Update Admin Details', "Admin has changed the account informations"], $response);

        return response()->json($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

use function PHPUnit\Framework\directoryExists;

class BackupController extends Controller
{
    public function automate(Request $req): JsonResponse
    {
        $authorized = $this->authorized();
        if(!$authorized){
            return response()->json(['success'=> false, 'message' => 'You have not enough access to proceed with the request']);
        }

        $user = User::where('id', Auth::id())->where('user_type', "Super Admin")->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User Not Found']);
        }

        $user->update([
            'backup_automate' => $req->status
        ]);

        return response()->json(['success'=> true, 'message'=> 'Automatic Back up Updated']);
    }

    public function initiate(Request $req): JsonResponse{
        $authorized = $this->authorized();
        if(!$authorized){
            return response()->json(['success'=> false, 'message' => 'You have not enough access to proceed with the request']);
        }

        $user = User::where('id', Auth::id())->where('user_type', "Super Admin")->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'User Not Found']);
        }

        try {
            Artisan::call('backup:run', ['--only-db' => true]);

            $output = Artisan::output();
            return response()->json(['success' => true, 'message' => 'Backup has been initiated', 'output' => $output]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Backup initiation failed', 'error' => $e->getMessage()]);
        }
    }

    public function list(Request $req): JsonResponse
    {
        $directory = (string) config('APP_NAME');
        $files = Storage::allFiles($directory);

        $filesWithBase64 = array_map(function ($file) {

            $fileContent = (string) Storage::get($file);


            $mimeType = 'application/zip';

            $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

            return [
                'path' => $file,
                'base64' => $base64,
            ];
        }, $files);

        return response()->json([
            'success' => true,
            'files' => $filesWithBase64,
        ]);
    }
    private function authorized(): bool
    {
        $user = User::where('id', Auth::id())->where('user_type', 'Super Admin')->first();

        if(!$user){
            return false;
        }

        return true;
    }
}

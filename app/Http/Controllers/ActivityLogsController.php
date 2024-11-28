<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLogs;
use Illuminate\Http\JsonResponse;
class ActivityLogsController extends Controller
{
    public function list(): JsonResponse{
        $logs = ActivityLogs::join('users', 'users.id', '=', 'activity_logs.user_id')->select('activity_logs.*', 'users.name')->get();

        return response()->json(['success'=> true, 'logs'=> $logs]);
    }

    public function details(string $id): JsonResponse{
        $logs = ActivityLogs::where('act_id', $id)->join('users', 'users.id', '=', 'activity_logs.user_id')->select('activity_logs.*', 'users.name')->first();

        if(!$logs){
            return response()->json(['success'=> false, 'No Activity Logs Found']);
        }
        return response()->json(['success'=> true, 'logs'=> $logs]);
    }
}

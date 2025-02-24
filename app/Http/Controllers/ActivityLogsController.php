<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLogs;
use Illuminate\Http\JsonResponse;
class ActivityLogsController extends Controller
{
    public function list(Request $request): JsonResponse{
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search')['value'];

        $query = ActivityLogs::join('users', 'users.id', '=', 'activity_logs.user_id')->select('activity_logs.*', 'users.name')
                ->orderBy('activity_logs.created_at', 'desc');

        if (!empty($search)) {
            $query->where('code', 'like', "%$search%")
                ->orWhere('entry_type', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        }

        $totalRecords = ActivityLogs::count();

        $filteredRecords = $query->count();

        $logs = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $logs
        ]);
    }

    public function details(string $id): JsonResponse{
        $logs = ActivityLogs::where('act_id', $id)->join('users', 'users.id', '=', 'activity_logs.user_id')->select('activity_logs.*', 'users.name')->first();

        if(!$logs){
            return response()->json(['success'=> false, 'No Activity Logs Found']);
        }
        return response()->json(['success'=> true, 'logs'=> $logs]);
    }
}

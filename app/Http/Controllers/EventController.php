<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function getMonthEvents(Request $request) {
        $response = [];

        $user = Auth::user();

        $today = Carbon::parse($request->today);
        $endOfMonth = $today->copy()->endOfMonth();
        $today->startOfMonth();
        while(! $endOfMonth->isSameDay($today)) {
            if(!$today->isWeekend()) {
                $tasks = $user->tasks()->where('date', $today->toDateString())
                    ->get();
                $hour = $tasks->sum('hours');
                $minutes = $tasks->sum('minutes');
                $hour = $hour + ($minutes / 60);

                $status = $hour <= config('timesheet.req_hours') ? 'PENDING' : 'COMPLETE';

                $response[] = [
                    'date'  =>  $today->toDateString(),
                    'hour'  =>  $hour,
                    'req_hour'  =>  config('timesheet.req_hours'),
                    'status'    =>  $status,
                    'color'     =>  $hour < config('timesheet.req_hours') ? 'red' : 'royalblue'
                ];
            }


            $today->addDay();
        }

        return response()->json($response);
    }
}

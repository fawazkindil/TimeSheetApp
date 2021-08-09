<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class EventController extends Controller
{
    public function getMonthEvents(Request $request) {
        $response = [];

        $user = Auth::user();

        $today = Carbon::parse($request->today);
        $endOfMonth = $today->copy()->endOfMonth();
        $today->startOfMonth();



        while( $endOfMonth->isSameMonth($today)) {
            if(!$today->isWeekend()) {
                $tasks = $user->tasks()->where('date', $today->toDateString())
                    ->get();
                $hour = $tasks->sum('hours');
                $minutes = $tasks->sum('minutes');
                $hour = $hour + ($minutes / 60);

                $req_hour = config('timesheet.req_hours');
                $color = 'red';
                $leave = $user->leaves()->where('date', $today->toDateString())->first();
                $status = '';
                if(isset($leave)) {
                    if($leave->type == 'full') {
                        $req_hour = 0;
                        $color = 'black';
                        $status = 'ONLEAVE';
                    } else if($leave->type == 'half') {
                        $req_hour = $req_hour / 2;
                    }
                }

                $response[] = [
                    'date'  =>  $today->toDateString(),
                    'hour'  =>  $hour,
                    'req_hour'  =>  $req_hour,
                    'status'    =>  $status !== 'ONLEAVE' ? ($hour < $req_hour ? 'PENDING' : 'COMPLETE') : 'ONLEAVE',
                    'color'     =>  $status !== 'ONLEAVE' ? ($hour < $req_hour ? 'red' : 'royalblue') : $color
                ];
            }


            $today->addDay();
        }

        return response()->json($response);
    }

    public function updateLeave(Request $request) {
        $date = $request->date;
        $type = $request->type;
        $user = Auth::user();

        if($type) {
            $user->leaves()->updateOrCreate(
                ['date'  =>  $date],
                ['type'  =>  $type]
            );
        } else {
            $user->leaves()->where('date', $date)->delete();
        }

        return response()->json('OK');
    }
}

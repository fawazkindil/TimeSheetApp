<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TaskController extends Controller
{
    public function list(Request $request) {
        $compactValues = [];

        $date = Carbon::parse($request->date);
        $compactValues[] = 'date';

        $user = Auth::user();
        $tasks = $user->tasks()->where('date', $date->toDateString())
                    ->get();
        $compactValues[] = 'tasks';

        $totalHours = $tasks->sum('hours');
        $totalMinutes = $tasks->sum('minutes');
        $totalHours = $totalHours + (intval($totalMinutes / 60));
        $totalMinutes = $totalMinutes % 60;
        $compactValues[] = 'totalHours';
        $compactValues[] = 'totalMinutes';

        return view('task.list',compact($compactValues));
    }

    public function add(Request $request) {
        $compactValues = [];
        $date = Carbon::parse($request->date);
        $compactValues[] = 'date';
        return view('task.add', compact($compactValues));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'hour' => 'required_without:minute',
            'minute' => 'required_without:hour',
            'description'   =>  '',
            'date'  => ''
        ]);

        $user = Auth::user();
        $user->tasks()->create([
            'date'  =>  $data['date'],
            'hours'  =>  $data['hour'] == null ? 0 : $data['hour'],
            'minutes'   =>  $data['minute'] == null ? 0 : $data['minute'],
            'description'   =>  $data['description']
        ]);

        Alert::success('Saved', 'New Task Added');

        return redirect()->route('task.list', ['date' => $data['date']]);
    }
}

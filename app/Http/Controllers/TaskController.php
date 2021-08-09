<?php

namespace App\Http\Controllers;

use App\Models\Task;
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

        $leave = $user->leaves()->where('date', $date->toDateString())->first();
        $leaveType = $leave ? $leave->type : '';
        $compactValues[] = 'leaveType';

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

    public function getEditData(Request $request) {
        $id = $request->id;
        $task = Task::find($id);
        return response()->json($task);
    }

    public function updateTask(Request $request) {
        $id = $request->id;
        $hour = $request->hour;
        $minute = $request->minute;
        $description = $request->description;

        $task = Task::find($id);
        $task->update([
            'hours' =>  $hour,
            'minutes'   =>  $minute,
            'description'   => $description
        ]);
        Alert::success('Saved', 'Task Edited');
        return response('OK');
    }

    public function delete(Request $request) {
        $id = $request->id;
        $task = Task::find($id);
        $task->delete();
        Alert::success('Deleted', 'Task Deleted');
        return response('OK');
    }
}

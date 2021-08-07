@extends('layouts.app')
@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <x-full-frame>

        <div class="w-full max-w-md flex flex-row justify-between items-center">
            <a class="text-blue-700" href="{{route('timesheet')}}">Back To Timesheet</a>
        </div>
        <div class="w-full max-w-md rounded shadow border p-4 bg-white">
            <div class="text-xl font-bold">
                {{$date->format('l')}}, {{$date->format('d / m / Y')}} ({{$totalHours}} {{Illuminate\Support\Str::plural('Hour', $totalHours)}}@if($totalMinutes !== 0) {{$totalMinutes}} {{Illuminate\Support\Str::plural('Minute', $totalMinutes)}}@endif)
            </div>
            <div class="flex flex-row justify-between items-center my-2">
                <p class="font-semibold">
                    Researcher : {{Auth::user()->name}}
                </p>
                <a href="{{route('task.add')}}{{'?date='.$date->format('Y-m-d')}}" class="border-2 border-blue-500 hover:bg-blue-600 hover:text-white rounded text-blue-500 px-2">
                    Add Task
                </a>
            </div>
            <div class="p-4 overflow-auto w-full">
                <table class="w-full">
                    <thead class="font-bold">
                        <tr class="border-b-2 border-gray-600">
                            <th>
                                Description
                            </th>
                            <th>
                                Time
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @forelse ($tasks as $task)
                            <tr class="border-b border-gray-100">
                                <td class="py-1">
                                    {{$task->description}}
                                </td>
                                <td class="py-1">
                                    <div class="flex flex-row items-center">
                                        <div class="bg-blue-400 text-white text-xs rounded px-1 py-1 mb-1">
                                            {{$task->hours . ' hours'}}
                                        @if ($task->minutes !== 0)
                                            <br>{{$task->minutes.' minutes'}}
                                        @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-1">
                                    <div class="flex flex-row items-center">
                                        <button type="button" class="px-1 border-2 border-green-500 rounded text-green-500">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="px-1 border-2 border-red-500 rounded text-red-500">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty

                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- <div>
                <input type="number" step="1" min="1" max="8" name="hour" class="w-1/3 border border-gray-200 rounded px-2 py-1" placeholder="Hour">
                <input type="number" step="1" min="1" max="60" name="minute" class="w-1/3 border border-gray-200 rounded px-2 py-1" placeholder="Minute">
            </div> --}}
        </div>
    </x-full-frame>
@endsection
@push('after-script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush

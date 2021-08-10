@extends('layouts.app')
@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <x-full-frame>

        <div class="w-full max-w-md flex flex-row justify-between items-center">
            <a class="text-blue-700" href="{{route('task.list')}}{{'?date='.$date->format('Y-m-d')}}">Back To Task List</a>
        </div>
        <div class="w-full max-w-md rounded shadow border p-4 bg-white">
            <div class="text-xl font-bold">
                {{$date->format('l')}}, {{$date->format('d / m / Y')}}
            </div>
            <div class="flex flex-row justify-between items-center my-2">
                <p class="font-semibold">
                    Researcher : {{Auth::user()->name}}
                </p>
            </div>
            <form action="{{route('task.store')}}" method="POST" onsubmit="$('#submitButton').prop('disabled', true)">
                @csrf
                <div class="text-center">
                    <input type="hidden" name="date" value="{{$date->toDateString()}}">
                    <input type="number" step="1" min="1" max="8" name="hour" value="{{old('name')}}" class="w-1/3 border border-gray-200 rounded px-2 py-1" placeholder="Hour">
                    <input type="number" step="1" min="1" max="60" name="minute" value="{{old('minute')}}" class="w-1/3 border border-gray-200 rounded px-2 py-1" placeholder="Minute">
                </div>
                <textarea name="description" id="description" value="{{old('description')}}" cols="30" rows="10" class="mt-2 w-full border border-gray-200 rounded px-2 py-1" placeholder="Task Description (optional)"></textarea>
                <div class="text-center">
                    <button type="submit" id="submitButton" class="px-2 py-1 bg-blue-600 hover:bg-blue-300 text-white hover:text-blue-900 rounded">Add Task</button>
                </div>
            </form>
        </div>
    </x-full-frame>
@endsection
@push('after-script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush

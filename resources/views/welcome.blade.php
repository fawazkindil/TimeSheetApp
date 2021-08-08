@extends('layouts.app')
@section('content')
@guest
    <div class="w-screen h-screen flex flex-row justify-center items-center bg-gray-100">
        <div class="mx-auto mt-10">
            <x-login-form></x-login-form>
        </div>
    </div>
@endguest
@auth
<div class="w-screen h-screen flex flex-row justify-center items-center bg-gray-100">
    <div class="mx-auto mt-10">
        <a class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow-lg" href="{{route('timesheet')}}">Go To Timesheet</a>
    </div>
</div>
@endauth
@endsection

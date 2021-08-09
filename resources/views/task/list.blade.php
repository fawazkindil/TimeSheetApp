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
                {{$date->format('l')}}, {{$date->format('d / m / Y')}} ( {{$totalHours}} {{Illuminate\Support\Str::plural('Hour', $totalHours)}}@if($totalMinutes !== 0) {{$totalMinutes}} {{Illuminate\Support\Str::plural('Minute', $totalMinutes)}}@endif )
            </div>
            <div class="flex flex-row justify-between items-center my-2">
                <p class="font-semibold">
                    Researcher : {{Auth::user()->name}}
                </p>
                <div class="flex flex-col text-sm font-semibold">
                    <input type="hidden" name="date" value="{{$date->format('Y-m-d')}}">
                    <label class="inline-flex items-center mt-3">
                        <input type="checkbox" name="leave[]" value="full" @if($leaveType == 'full') checked @endif class="form-checkbox h-5 w-5 text-gray-600"><span class="ml-2 text-gray-700">On Leave</span>
                    </label>
                    <label class="inline-flex items-center mt-3">
                        <input type="checkbox" name="leave[]" value="half" @if($leaveType == 'half') checked @endif class="form-checkbox h-5 w-5 text-gray-600"><span class="ml-2 text-gray-700">Half Day</span>
                    </label>
                </div>
                <a href="{{route('task.add')}}{{'?date='.$date->format('Y-m-d')}}" class="border-2 border-blue-500 hover:bg-blue-600 hover:text-white rounded text-blue-500 px-2">
                    Add Task
                </a>
            </div>
            <div class="p-4 overflow-auto w-full" id="listDiv">
                @include('task.partial.task-table', compact('tasks'))
            </div>
        </div>
    </x-full-frame>
@endsection

@push('modal')
    <div class="px-4 py-2">
        <div class="text-center">
            <div class="font-bold text-lg">Edit Task</div>
            <input type="hidden" name="editId">
            <input type="hidden" name="date" value="{{$date->toDateString()}}">
            <span class="font-semibold text-sm">Hour</span>
            <input type="number" step="1" min="1" max="8" name="hour" class="w-1/3 border border-gray-200 rounded px-2 py-1" placeholder="Hour">
            <span class="font-semibold text-sm">Minute</span>
            <input type="number" step="1" min="1" max="60" name="minute" class="w-1/3 border border-gray-200 rounded px-2 py-1" placeholder="Minute">
        </div>
        <textarea name="description" id="description" cols="30" rows="10" class="mt-2 w-full border border-gray-200 rounded px-2 py-1" placeholder="Task Description (optional)"></textarea>
        <div class="text-center">
            <button type="button"onclick="submitEditData();" class="px-2 py-1 bg-blue-600 hover:bg-blue-300 text-white hover:text-blue-900 rounded">Edit Task</button>
        </div>
    </div>
@endpush

@push('after-script')
    <script>
        var timeOut;
        var timer;
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('input[name="leave[]"]').click(function() {
                var checked = $(this).prop('checked');
                $('input[name="leave[]"]').prop('checked', false);
                if(checked) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
                submitLeave();
            });
        });

        function submitLeave() {
            if(timeOut) {
                clearTimeout(timer);
            }
            timeOut = true;
            timer = setTimeout((() => {
                $.ajax({
                    url: '{{route('event.updateLeave')}}',
                    type: 'post',
                    data: {
                        date: $('input[name="date"]').val(),
                        type: $('input[name="leave[]"]:checked').val()
                    },
                    success: function(response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            titleText: 'Leave Saved',
                            text: '',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            showCloseButton: true,
                        });
                        timeOut = false;
                        console.log(response);
                    },
                    error: function() {
                        console.log(response);
                    }
                });
            }),500);
        }

        function populateEditTask(id) {
            $.ajax({
                url: '{{route('task.getEditData')}}',
                type: 'post',
                data: {
                    id: id
                },
                success: function(response) {
                    $('input[name="hour"]').val(response.hours);
                    $('input[name="minute"]').val(response.minutes);
                    $('input[name="editId"]').val(response.id);
                    $('textarea[name="description"]').val(response.description);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function submitEditData() {
            $.ajax({
                url: '{{route('task.updateTask')}}',
                type: 'post',
                data: {
                    id: $('input[name="editId"]').val(),
                    hour: $('input[name="hour"]').val(),
                    minute: $('input[name="minute"]').val(),
                    description: $('textarea[name="description"]').val()
                },
                success: function(response) {
                    if(response == 'OK') {
                        window.location.reload();
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function deleteTask(id) {
            Swal.fire({
                title: 'Confirm Deletion?',
                showCancelButton: true,
                confirmButtonText: `Delete`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route('task.delete')}}',
                        type: 'post',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            window.location.reload();
                        }
                    });

                }
            });
        }

    </script>
@endpush

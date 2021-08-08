@extends('layouts.app')
@push('after-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .fc-day-sat:not(.fc-col-header-cell),
        .fc-day-sun:not(.fc-col-header-cell) {
                background-color: lavenderblush !important;
            }
        .fc-event{
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <x-full-frame>
        <div class="text-2xl font-extrabold my-4">
            TIMESHEET
        </div>
        <div id='calendar' class="w-full max-w-xl rounded shadow border p-4 bg-white"></div>
    </x-full-frame>
@endsection
@push('after-script')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var events;
        var calendar;
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    eventContent: function(args) {
                        let icon = document.createElement('i')
                        if(args.event.extendedProps.hour < 8) {
                            icon.classList.add('fas');
                            icon.classList.add('fa-thumbs-down');
                            args.event.color = '#ffff';
                        } else {
                            icon.classList.add('fas');
                            icon.classList.add('fa-thumbs-up');
                        }
                        let arrayOfDomNodes = [ icon ]
                        return { domNodes: arrayOfDomNodes }
                    },
                    // dateClick: function(info) {
                    //     window.location.href = '{{route('task.list')}}' + '?date=' + info.dateStr;
                    // },
                    eventClick: function(info) {
                        window.location.href = '{{route('task.list')}}' + '?date=' + moment(info.event.start).format('YYYY-MM-DD');
                    }
                });
            today = moment();
            getMonthData(today);

            calendar.render();
        });

        function toggleTaskModal() {

        }

        function getMonthData(today) {
            $.ajax({
                url: '{{route('event.month')}}',
                type: 'post',
                data: {
                    today: today.format('YYYY-MM-DD')
                },
                beforeSend: function() {

                },
                success: function(response) {
                    response.forEach(function(event) {
                        calendar.addEvent(event);
                    });
                    $('.fc-prev-button').off().click(handler);
                    $('.fc-next-button').off().click(handler);
                    $('.fc-today-button').off().click(handler);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function handler() {
            calendar.getEvents().forEach(function(event) {
                event.remove();
            });
            var date = moment(calendar.getDate().toISOString());
            getMonthData(date);
        }
    </script>
@endpush

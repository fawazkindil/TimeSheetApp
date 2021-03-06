<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name')}}</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('after-styles')

    <style>
        body {
            font-family: 'Lato';
        }
    </style>
</head>
<body id="app">
    @include('sweetalert::alert')
    <x-modal id="modal" style="display: none;">
        @stack('modal')
    </x-modal>
    @auth
    <div class="absolute top-0 right-0 p-4 font-semibold cursor-pointer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    this.closest('form').submit();">
                <div class="px-8 font-semibold py-2 hover:bg-gray-200 text-center">
                    <span>
                        {{ __('Log Out') }}
                    </span>
                </div>
            </a>
        </form>
    </div>
    @endauth

    @yield('content')

</body>

    <!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script>
    function toggleModal() {
        $('#modal').toggle();
    }
</script>
@stack('after-script')
</html>

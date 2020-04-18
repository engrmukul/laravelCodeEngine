<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <title>LCE - {{Session::get('MODULE_LANG')}}</title>
        <link rel="shortcut icon" type="image/png" href="{{asset('assets/img/lce.png')}}"/>
        @include('includes.assets')
        @php(date_default_timezone_set('Asia/Dhaka'))
    </head>
    <body>
        <div id="wrapper">
            @include('includes.sidebar')
            <div id="page-wrapper" class="gray-bg">
                @include('includes.header')
                @include('includes.notifications')
                <!-- @include('includes.password_notify') -->

                <!-- MAIN CONTAINER AREA -->
                <div class="wrapper wrapper-content">
                    @yield('content')
                </div>
                @include('includes.flash-message')
                @include('includes.modal')
                @include('includes.footer')
            </div>
        </div>
    </body>
    <script src="{{asset('assets/js/apsisScript.js')}}"></script>

</html>

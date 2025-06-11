<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rosalina Express') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet">

    <link rel="icon" href="{{asset('assets/images/logo-rosalina.png')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
          id="main-font-link"/>
    <link rel="stylesheet" href="{{asset('assets/fonts/phosphor/duotone/style.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/fonts/tabler-icons.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/fonts/feather.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/fonts/material.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/style-preset.css')}}"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
</head>
<body>
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
@include('layouts.sidebars.sidebar_nav')
@include('layouts.headers.headers_nav')
<div class="pc-container">
    <div class="pc-content">
        @yield('content')
    </div>
</div>
@include('layouts.footers.footers_nav')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('DataTables/datatables.min.js')}}"></script>

<!-- Required Js -->
<script src="{{asset('assets/js/plugins/popper.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/simplebar.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/icon/custom-font.js')}}"></script>
<script src="{{asset('assets/js/script.js')}}"></script>
<script src="{{asset('assets/js/theme.js')}}"></script>
<script src="{{asset('assets/js/plugins/feather.min.js')}}"></script>

{{--Mascara--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset("js/jquery.mask.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("js/mascara.js") }}" type="text/javascript"></script>

<script>
    layout_change('light');
</script>

<script>
    font_change('Roboto');
</script>

<script>
    change_box_container('false');
</script>

<script>
    layout_caption_change('true');
</script>

<script>
    layout_rtl_change('false');
</script>

<script>
    preset_change('preset-1');
</script>

<script src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/js/pages/dashboard-default.js')}}"></script>
@stack('js')

</body>
</html>




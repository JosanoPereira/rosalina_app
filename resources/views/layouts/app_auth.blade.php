<!doctype html>
<html lang="en">
<!-- [Head] start -->
<head>
    <title>Rosalina Express</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta
        name="description"
        content="Berry is trending dashboard template made using Bootstrap 5 design framework. Berry is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies."
    />
    <meta
        name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard"
    />
    <meta name="author" content="codedthemes"/>

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

    @stack('css')
</head>
<body>
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>

<div class="auth-main">
    <div class="auth-wrapper v3">
        @yield('content')
    </div>
</div>

<script src="{{asset('assets/js/plugins/popper.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/simplebar.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/icon/custom-font.js')}}"></script>
<script src="{{asset('assets/js/script.js')}}"></script>
<script src="{{asset('assets/js/theme.js')}}"></script>
<script src="{{asset('assets/js/plugins/feather.min.js')}}"></script>

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

@stack('js')

</body>
</html>

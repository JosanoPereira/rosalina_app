<!doctype html>
<html lang="en">
<!-- [Head] start -->
<head>
    <title>Login | Berry Dashboard Template</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="description"
        content="Berry is trending dashboard template made using Bootstrap 5 design framework. Berry is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies."
    />
    <meta
        name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard"
    />
    <meta name="author" content="codedthemes" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{asset('assets/images/favicon.svg')}}" type="image/x-icon"/>
    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
          id="main-font-link" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="{{asset('assets/fonts/phosphor/duotone/style.css')}}"/>
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{asset('assets/fonts/tabler-icons.min.css')}}"/>
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{asset('assets/fonts/feather.css')}}"/>
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome.css')}}"/>
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{asset('assets/fonts/material.css')}}"/>
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/style-preset.css')}}"/>

    @stack('css')
</head>
<!-- [Head] end -->
<!-- [Body] Start -->
<body>
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<div class="auth-main">
    <div class="auth-wrapper v3">
        @yield('content')
    </div>
</div>
<!-- [ Main Content ] end -->
<!-- Required Js -->
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
<!-- [Body] end -->
</html>

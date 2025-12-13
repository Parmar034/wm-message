<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="WM Login page">
    <meta name="author" content="my-lapo-qr-system">
    <meta name="keyword" content="qr,qrcode,system,management">
    <title>WM-Message - Login</title>
    <link rel="manifest" href="{{ asset('assets/favicon/manifest.json') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ asset('vendors/simplebar/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendors/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style2.css') }}">
    <!-- Main styles for this application-->
    {{-- <link href="{{ asset('css/style.css') }}" rel="stylesheet"> --}}
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{ asset('css/examples.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <style>
        .parsley-errors-list {
            color: red;
            list-style-type: none;
            padding: 10px 0 0 !important;
        }
    </style>
    <style>
        :root {
            --theme-orange-red: #0060AA;
            --theme-light-color: #0060AA3f;
            --theme-dark-color: #0060AA7f;
        }
    </style>
</head>

<body>
    @yield('content')

    <!-- CoreUI and necessary plugins-->
    {{-- <script src="{{ asset('vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script> --}}
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('vendors/simplebar/js/simplebar.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });
    </script>
    <script>
        $('#show_password').click(function() {
            var attr = $('#password').attr('type');
            if (attr == 'password') {
                $('#password').prop('type', 'text');
                $('#hide-eye').hide();
            } else {
                $('#password').prop('type', 'password');
                $('#hide-eye').show();
            }
        });
    </script>
    <script src="{{ asset('js/parsley/parsley.min.js') }}"></script>
    @yield('script')
</body>

</html>

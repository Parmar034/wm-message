<!DOCTYPE html>

<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="my-lapo-qr-system">
    <meta name="author" content="my-lapo-qr-system">
    <meta name="keyword" content="my-lapo-qr-system">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keyword" content="qr,qrcode,system,management">
    <title>WM-Message</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/animation/css/animate.min.css') }}">
    <!-- vendor css -->

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ asset('vendors/simplebar/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendors/simplebar.css') }}">
    <!-- Main styles for this application-->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/style.css') }}" rel="stylesheet"> --}}
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    {{-- <link href="{{ asset('css/examples.css') }}" rel="stylesheet"> --}}

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- <link href="{{ asset('css/jquery.toastr.min.css') }}" type="text/css" rel="stylesheet"> -->


    <link href="{{ asset('vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    {{-- <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/chart-morris/css/morris.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
        type="text/css" />


    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" /> --}}


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css">
    <!-- <link href="{{ asset('css/summernote.css') }}" rel="stylesheet"> -->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css'>


    <script type="text/javascript">
        var admin_url = "{{ url('/') }}/admin/";
    </script>

    <script>
        var BASE_URL = '{{ url('/') }}';
    </script>
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

        input[type="number"][name="phone"]::-webkit-inner-spin-button,
        input[type="number"][name="phone"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"][name="phone"] {
            -moz-appearance: textfield;
        }

        %loading-skeleton {
            color: transparent;
            appearance: none;
            -webkit-appearance: none;
            background-color: #eee;
            border-color: #eee;

            &::placeholder {
                color: transparent;
            }
        }

        @keyframes loading-skeleton {
            from {
                opacity: .4;
            }

            to {
                opacity: 1;
            }
        }

        .skeleton-box {
            display: inline-block;
            height: 1.2em;
            background: #e0e0e0;
            border-radius: 4px;
            animation: skeleton-loading 1.2s infinite linear alternate;
        }

        .skeleton-box.icon {
            width: 2em;
            height: 2em;
            margin-right: 0.5em;
        }

        @keyframes skeleton-loading {
            0% {
                background-color: #e0e0e0;
            }

            100% {
                background-color: #f5f5f5;
            }
        }

        .loading-skeleton {
            pointer-events: none;
            animation: loading-skeleton 1s infinite alternate;

            img {
                filter: grayscale(100) contrast(0%) brightness(1.8);
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p,
            li,
            td,
            th,
            .btn,
            label,
            .form-control {
                @extend %loading-skeleton;
            }
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current{color: white !important;}
    </style>
</head>

<body class="layout-8">
    @include('layouts.backend.sidebar')
    @include('layouts.backend.navbar')
    <div class="pcoded-main-container">
        @yield('main_content')
    </div>
    @include('layouts.backend.footer')
    <div class="modal" tabindex="-1" id="help_modal" style="top: 30%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Help</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="help-text"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- </div> --}}
    <!-- CoreUI and necessary plugins-->

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"
        integrity="sha512-cJMgI2OtiquRH4L9u+WQW+mz828vmdp9ljOcm/vKTQ7+ydQUktrPVewlykMgozPP+NUBbHdeifE6iJ6UVjNw5Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- <!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --> --}}
    {{-- <script src="{{ asset('js/jquery.dataTables.min.js')}}"></script> --}}


    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script> -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
    <!-- <script src="{{ asset('vendors/simplebar/js/simplebar.min.js') }}"></script> -->
    <!-- Plugins and scripts required by this view-->
    <!-- <script src="{{ asset('vendors/chart.js/js/chart.min.js') }}"></script> -->
    <script src="{{ asset('vendors/@coreui/chartjs/js/coreui-chartjs.js') }}"></script>
    <script src="{{ asset('vendors/@coreui/utils/js/coreui-utils.js') }}"></script>
    <!-- <script src="{{ asset('js/main.js') }}"></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- <script src="{{ asset('js/toastr/toastr.min.js') }}"></script> -->
    <script src="{{ asset('js/inputmask/inputmask.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    {{-- <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script> --}}
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/inputMask/bootstrap_input_mask.min.js') }}"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js"></script>
    <!-- <script src="{{ asset('js/summernote.js') }}"></script> -->
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> --}}



    <script src="https://use.fontawesome.com/7ad89d9866.js"></script>


    {{-- charts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>
    <script src="{{ asset('assets/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script>
        $(document).ready(function() {
            // Function to handle screen size changes
            function handleScreenSizeChange() {
                var windowWidth = $(window).width();
                var element = $('.pcoded-navbar');
                if (windowWidth <= 992) {
                    element.removeClass('navbar-collapsed');
                } else {
                    element.removeClass('navbar-collapsed');
                }
            }

            // Call handler initially
            handleScreenSizeChange();

            // Attach handler to resize event
            $(window).resize(handleScreenSizeChange);
        });
    </script>
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
        window.assetPath = "{{ asset('assets/images/user/img-demo_1041.jpg') }}";
    </script>
    <script>
        $(document).ready(function() {
            // Apply input masking to the phone input field
            $('.imput-mask').inputmask('(999) 999-9999');
            $('.input-money').maskMoney();
            $('.input-money-price').inputmask("currency", {
                prefix: '$ ',
                alias: 'numeric',
                rightAlign: false,
                autoUnmask: true
            });
            $('.phone-imput-mask').inputmask('9999999999');

        });
        $(document).ready(function() {
            // Apply input masking to the phone input field
            $('.select2').select2({
                placeholder: "Select Category"
            });
            $('#artical_tags').select2({
                placeholder: "Select Tag"
            });
            $('.select2').select2({
                placeholder: "Select"
            });
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>


    <script src="{{ asset('js/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('js/passwordcdn/cdnjs.cloudflare.com_ajax_libs_zxcvbn_4.4.2_zxcvbn.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart-morris/js/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart-morris/js/morris.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/js/jquery-ui.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/pages/chart-morris-custom.js') }}"></script> --}}

    <script src="{{ asset('assets/datepicker/bootstrap-datepicker.min.js') }}"></script>

    @yield('script')
    @yield('side-bar-script')
    <script>
        $(function() {
            $('input, select, textarea').on('input change', function() {
                // Only toggle if the element has is-valid or is-invalid
                if ($(this).hasClass('is-valid') || $(this).hasClass('is-invalid')) {
                    let parent = $(this).parent();
                    if ($(this).val()) {
                        parent.find('.alert.text-danger').remove();
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    } else {
                        $(this).removeClass('is-valid').addClass('is-invalid');
                    }
                }
            });
        });
    </script>
</body>

</html>

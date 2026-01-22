<link rel="icon" href="{{ asset('uploads/logo/favicon.png') }}" type="image/ico" />
<!-- Bootstrap -->
<link href="{{ asset('backend/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- Font Awesome -->
<link href="{{ asset('backend/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<!-- NProgress -->
<link href="{{ asset('backend/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
<!-- iCheck -->
<link href="{{ asset('backend/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('bootstrap-icons-1.11.3/font/bootstrap-icons.css') }}">
<!-- bootstrap-progressbar -->
{{-- <link href="{{ asset('backend/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet"> --}}
<!-- JQVMap -->
{{-- <link href="{{ asset('backend/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/> --}}
<!-- bootstrap-daterangepicker -->
{{-- <link href="{{ asset('backend/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet"> --}}

<!-- Custom Theme Style -->
<link href="{{ asset('backend/build/css/custom.min.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<style>
    .select2-selection { overflow: hidden; }
    .select2-selection__rendered { white-space: normal; word-break: break-all; }
</style>

@yield('css')

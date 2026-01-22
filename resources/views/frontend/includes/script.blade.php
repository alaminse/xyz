<script src="{{ asset('frontend/bootstrap-5.3.3/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('frontend/bootstrap-5.3.3/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend/bootstrap-5.3.3/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/bootstrap-5.3.3/js/toastr.min.js') }}"></script>
    @stack('scripts')

    @livewireScripts

    <script>
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif

        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
    </script>

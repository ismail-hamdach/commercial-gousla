<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('../assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('../assets/vendor/fonts/circular-std/style.css ')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('../assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('../assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/vendor/datatables/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/vendor/datatables/css/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/vendor/datatables/css/select.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/vendor/datatables/css/fixedHeader.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/libs/css/toast.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/libs/css/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/date-1.1.2/datatables.min.css"/>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    @yield('css_order_page')
    @yield('css_product')
    @livewireStyles()
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <title>Gousla aziz </title>
</head>
<body>
    <div class="dashboard-main-wrapper">
        @include('Dashboard.layouts.sidebar')
        
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                @yield('content')
            </div>
        </div>
        @include('Dashboard.layouts.header')
    </div>

    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="{{ asset('../assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('../assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('../assets/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('../assets/vendor/multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('../assets/libs/js/main-js.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('../assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('../assets/vendor/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('../assets/vendor/datatables/js/data-table.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>  
    <script src="{{ asset('../assets/libs/js/toast.js') }}"></script> 
    <script src="{{ asset('../assets/libs/js/bootstrap-tagsinput.min.js') }}"></script> 
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/date-1.1.2/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.1/moment.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    @yield('js_index_page')
    @yield('js_user_page') 
    @yield('js_role_page') 
    @yield('js_client_page')
    @yield('js_company_page')
    @yield('js_product_page')
    @yield('js_order_page')
    @yield('js_client')
    @yield('js_product')
    @yield('js_orderDetails_page')
    @livewireScripts()
</body>

</html>
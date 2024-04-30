<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Sarala:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/lesson.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    @yield('style')

    <style>
        /* Custom styles can be added here */
        .small-text {
            font-size: 12px; /* Adjust as needed */
        }
        /* Define smaller font size for small screens */
        @media (max-width: 576px) {
            .small-text {
                font-size: 9px; /* Adjust as needed */
            }
        }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
    </div>

    @include('navbar')

    <!-- Main Sidebar Container -->
    @yield('sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @if(isset($school))
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <img alt="Avatar" class="table-avatar rounded-circle" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px; display: inline-block; vertical-align: middle;">
                                        <h5 style="display: inline-block; vertical-align: middle;">{{ $school->name }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <h3 class="m-0">@yield('page_title')</h3>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">@yield('breadcrumb1')</a></li>
                            <li class="breadcrumb-item">@yield('breadcrumb2')</li>
                            <li class="breadcrumb-item active">@yield('breadcrumb3')</li>
                        </ol>
                    </div><!-- /.col -->
                    @auth
                        @if(!auth()->user()->isProfileComplete())
                            <!-- Display a button to complete the profile for school owner -->
                            <div class="text-center mt-4 complete_profile">
                                <a href="{{ route('profile') }}" class="btn btn-danger">Complete Your Profile</a>
                            </div>
                        @endif
                    @endauth
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
                <div id="message" class="col-md-6 small-text ml-0"></div>
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2024 <a href="{{ route('/') }}">CarRental</a>.</strong>
        All rights reserved.
    </footer>

</div><!-- ./wrapper -->



<script src="{{ mix('js/app.js') }}"></script>
<!-- Add this to the head of your Blade file -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- jQuery -->
<!-- <script src="plugins/jquery/jquery.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.j')}}s"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/js/location.js')}}"></script>
<script src="{{asset('assets/js/profile_edit.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<!-- <script src="plugins/summernote/summernote-bs4.min.js"></script> -->
<!-- overlayScrollbars -->
<!-- <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script> -->
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
<script src="{{asset('dist/js/lesson.js')}}"></script>
<script>
    var buyConnectsRoute = '{{ route("buy_connects") }}';
    var profileRoute = '{{ route("profile") }}';
    var buyPackageRoute = '{{ route("buy_package") }}';
    var contactSupportRoute = '{{ route("contact_support") }}';
</script>

<script src="{{asset('dist/js/school_connects.js')}}"></script>
@yield('scripts')
<script>
 $(document).ready(function() {
        $('#searchForm').submit(function(e) {
            e.preventDefault();
            var term = $('#searchInput').val();

            $.ajax({
                url: "{{ route('search') }}",
                type: "GET",
                data: { term: term },
                success: function(response) {
                    // Redirect to a new page with search results
                    window.location.href = "/search-results?term=" + term;
                },
                error: function(xhr, status, error) {
                    var errorMessage = "An error occurred.";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $('#errorMessage').text(errorMessage).show();
                }
            });
        });
    });



</script>

<script>
    $(document).ready(function () {
        // Check if there is a success or error message in the page
        var successMessage = '{{ session("success") }}';
        var errorMessage = '{{ session("error") }}';

        // Function to display success or error messages
        function displayMessage(message, messageType) {
            var alertDiv = $('<div class="alert ' + messageType + ' alert-dismissible fade show" role="alert">' + message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><br>');

            // Append the alert to the body
            $('#message').append(alertDiv);

            // Fade in the alert
            alertDiv.fadeIn(1000, function () {
                // Delay for 6 seconds
                alertDiv.delay(6000).fadeOut(1000, function () {
                    // Remove the alert after fading out
                    $(this).remove();
                });
            });
        }

        // Display success message if available
        if (successMessage) {
            displayMessage(successMessage, 'alert-success');
        }

        // Display error message if available
        if (errorMessage) {
            displayMessage(errorMessage, 'alert-danger');
        }
    });
</script>
</body>
</html>

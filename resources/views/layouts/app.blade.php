<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.cs')}}s">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.cs')}}s">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
  @yield('style')
  <style>
    #school_list_container {
        position: absolute;
        width: calc(100% - 2px);
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        border-top: none;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .school_item {
        padding: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .school_item:hover {
        background-color: #f0f0f0;
    }
    .table-responsive .table {
        opacity: 1;
        transition: opacity 0.6s ease-in-out;
    }

    .collapse:not(.show) .table {
        opacity: 0;
        transition: opacity 0.6s ease-in-out;
    }

    .card-body {
        padding: 15px;
    }

    .admin-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.3s;
    }

    .admin-card:hover {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .user-profile {
        text-align: center;
        margin-bottom: 15px;
    }

    .user-profile img {
        border-radius: 50%;
        max-width: 100%;
        height: auto;
    }

    .users-list-name {
        display: block;
        font-size: 16px;
        margin-top: 10px;
        color: #333;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-info {
        background-color: #17a2b8;
    }

    .users-list-date {
        display: block;
        font-size: 12px;
        color: #777;
        margin-top: 5px;
    }

    .user-permissions {
        margin-top: 15px;
    }

    .details-heading {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .toggle-icon {
        margin-left: 5px;
    }

    .collapsed-details {
        display: none;
        margin-top: 10px;
    }

    .detail-item {
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .action-icons {
        margin-top: 15px;
    }

    .action-icon {
        font-size: 20px;
        color: #333;
        transition: color 0.3s;
    }

    .action-icon:hover {
        color: #007bff;
    }

    .modal-footer button {
        padding: 8px 15px;
    }

    #no-admin {
        color: #777;
        margin: 20px 0;
    }

    .detail-item {
        display: flex;
        margin-bottom: 8px;
    }

    .detail-label {
        font-weight: bold;
        margin-right: 8px;
    }

    .detail-value {
        color: #6c757d;
    }
    
    .toggle-icon {
        transition: transform 0.3s ease; /* Adjust the duration and easing function as needed */
    }

    .collapsed-details {
        display: none;
    }


  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
    @include('navbar')
  <!-- /.navbar -->

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
            <img alt="Avatar" class="table-avatar rounded-circle" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px;">

            @endif
            <h3 class="m-0">@yield('page_title')</h3>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">@yield('breadcrumb1')</a></li>
              <li class="breadcrumb-item ">@yield('breadcrumb2')</li>
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
        <div id="message" class="col-md-6"></div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="{{route('/')}}">CarRental</a>.</strong>
    All rights reserved.
    <!-- <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div> -->
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


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

@yield('scripts')


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

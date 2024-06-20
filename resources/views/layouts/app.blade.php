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
    


    @yield('style')

    <!-- Additional CSS for styling improvements -->
<style>
   
</style>
</head>
<body class="hold-transition sidebar-mini dark-mode control-sidebar-slide-open layout-fixed layout-navbar-fixed">
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
                        @if(isset($school) && auth()->user()->school)
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <img alt="Avatar" class="table-avatar rounded-circle" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px; display: inline-block; vertical-align: middle;">
                                        <h5 style="display: inline-block; vertical-align: middle;">{{ $school->name }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <h4 class="mt-2">@yield('page_title')</h4>
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
<footer class="main-footer bg-dark text-light py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <strong>&copy; <span id="currentYear"></span> <a href="{{ route('/') }}" class="text-light">Central School System</a>.</strong>
                All rights reserved.
            </div>
            <div class="col-md-6 text-md-right">
                <p class="mb-2 mb-md-0">Contact us at: <a href="mailto:support@centralschoolsystem.com" class="text-light">support@centralschoolsystem.com</a></p>
                <div class="social-icons mb-2">
                    <a href="https://www.facebook.com" target="_blank" class="text-light mr-3">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.twitter.com" target="_blank" class="text-light mr-3">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com" target="_blank" class="text-light mr-3">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://www.instagram.com" target="_blank" class="text-light">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                <div>
                    <a href="{{ route('privacy_policy') }}" class="text-light mr-3">Privacy Policy</a>
                    <a href="{{ route('terms_conditions') }}" class="text-light">Terms and Conditions</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>



<!-- JavaScript to update the year -->
<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>


    <div class="modal fade" id="yStudyConnectModal" tabindex="-1" aria-labelledby="yourStudyConnectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title" id="yourStudyConnectModalLabel">Top Up Study Connects</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="connects-message" style="display:none;"></div>
                <div class="alert alert-danger" id="connects-error" style="display:none;"></div>
               
                <div id="connectsForm">
                <div class="form-group">
                    <label for="yourConnectsAmountSuccess">Select Number of Connects:</label>
                    <select class="form-control" name="yourConnectsAmountSuccess" id="yourConnectsAmountSuccess">
                        <option value="500">90 Connects - ₦500</option>
                        <option value="1000">210 Connects - ₦1000</option>
                        <option value="2000">450 Connects - ₦2000</option>
                        <option value="3000">1000 Connects - ₦3000</option>
                    </select>
                </div>
                <button id="yourConfirmBuySucessConnectsBtn" class="btn btn-success">Top Up Connects</button>

                </div>
            </div>
            <div class="modal-footer" id="conect-modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary" id="confirmPlayBtn">Continue</button> -->
            </div>
        </div>
    </div>
</div>
</div><!-- ./wrapper -->



<script src="{{ mix('js/app.js') }}"></script>
<!-- Add this to the head of your Blade file -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- jQuery -->
<!-- <script src="plugins/jquery/jquery.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<!-- jQuery UI 1.11.4 -->
<!-- <script src="plugins/jquery-ui/jquery-ui.min.js"></script> -->
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
<script src="{{asset('dist/js/scrolable_view_fav.js')}}"></script>
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
                
                // Hide the form and display the error message
                $('#searchForm').hide();
                $('#errorMessage').text(errorMessage).fadeIn();

                // Show the form again after 3 seconds
                setTimeout(function() {
                    $('#errorMessage').fadeOut(function() {
                        $('#searchForm').show();
                    });
                }, 3000);
            }
        });
    });

    // Button click event to close the search block
    $('[data-widget="navbar-search-submit"]').click(function() {
        $('#searchForm').submit(); // Trigger form submission
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

<script>
     $(document).on('click', '#yourConfirmBuySucessConnectsBtn', function() {
        const yourConnectsAmountSuccess = $('#yourConnectsAmountSuccess').val(); // Get selected connects amount
        console.log('Selected Connects Amount:', yourConnectsAmountSuccess);

        if (yourConnectsAmountSuccess) {
            buyConnects(yourConnectsAmountSuccess); // Call buyConnects function with selected amount
        } else {
            console.error('Selected Connects Amount is empty or invalid');
        }
    });

    // Function to handle buying connects via AJAX
    function buyConnects(selectedConnectsAmount) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Perform AJAX request to buy more connects with selected price value
        $.ajax({
            url: buyConnectsRoute,
            method: 'POST',
            data: {
                amount: selectedConnectsAmount,
                _token: csrfToken
            },
            success: function(response) {
                // Handle success response
                console.log('Buy Connects Response:', response); // Log the response for debugging

                if (response && response.redirect_url) {
                    window.location.href = response.redirect_url; // Redirect to the specified URL
                } else {
                    console.error('Invalid response format');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
                console.error('Error buying connects:', error);
            }
        });
    }
</script>
<script>
$(document).ready(function() {
    // Handle keyup event on search input
    $('#searchQuery').keyup(function() {
        var query = $(this).val().trim(); // Get the search query
        if (query.length >= 3) { // Only perform search if query is at least 3 characters long
            // Send AJAX request to fetch students/wards
            $.ajax({
                url: '{{ route("find-wards") }}', // Define your route for searching
                method: 'GET',
                data: { query: query }, // Pass the search query as data
                success: function(response) {
                    // Process the JSON response
                    var html = '';
                    $.each(response, function(index, ward) {
                        html += '<div class="card ward-card" style="cursor:pointer" data-student-id="' + ward.id + '">';
                        html += '<div class="card-body">';
                        html += '<div class="d-flex">';
                        html += '<div class="profile-picture mr-3">';
                        if (ward.profile_picture) {
                            var profilePictureUrl = "{{ asset('storage/') }}" + '/' + ward.profile_picture;
                            html += '<img src="' + profilePictureUrl + '" alt="Profile Picture" class="rounded-circle" style="width: 72px;">';
                        } else {
                            html += '<i class="fas fa-camera fa-5x rounded-circle"></i>';
                        }
                        html += '</div><br><br>';
                        html += '<div class="ward-info" data-ward-id="'+ward.id+'">';
                        html += '<h5 class="card-title">' + ward.full_name + '</h5>';
                        html += '<p class="card-text">School: ' + ward.school_name + '</p>';
                        html += '<p class="card-text">Class: ' + ward.class_name + '</p>';
                        html += '</div>';
                        html += '</div>'; // Close d-flex div
                        html += '</div>'; // Close card-body div
                        html += '</div>'; // Close card div
                    });
                    // Update the wards list with the generated HTML
                    $('#wardsList').html(html);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            // If query is less than 3 characters, clear the wards list
            $('#wardsList').html('');
        }
    });

    // Attach click event handler to each ward card
    $(document).on('click', '.ward-card', function() {
        var wardInfo = $(this).html(); // Get the HTML content of the clicked card
        var studentId = $(this).data('student-id'); // Get the student ID from the data attribute
        console.log(wardInfo)
        // Set the ward info and student ID in the modal
        $('#wardInfo').html(wardInfo).attr('data-student-id', studentId);
        $('#confirmModal').modal('show'); // Show the modal
    });

    $('#confirmAddWard').click(function() {
    var studentId = $('#wardInfo').data('student-id');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Disable the button and show the loading spinner
    $('#confirmAddWard').prop('disabled', true);
    $('#addWardBtnText').hide();
    $('#addWardBtnSpinner').show();

    $.ajax({
        url: '{{ route("add-ward") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: { student_id: studentId },
        success: function(response) {
            // Display success message
            $('#add-ward-message').html('Student added as ward successfully!.').fadeIn().delay(3000).fadeOut();
            setTimeout(function() {

        $('#confirmModal').modal('hide'); // Show the modal
                location.reload();
            }, 3000);
        },
        error: function(xhr, status, error) {
            // Display error message
            console.log(xhr.responseText);
            $('#add-ward-error').html('Failed to add ward. Please try again.').fadeIn().delay(3000).fadeOut();
        },
        complete: function() {
            // Enable the button and hide the loading spinner
            $('#confirmAddWard').prop('disabled', false);
            $('#addWardBtnText').show();
            $('#addWardBtnSpinner').hide();
        }
    });
});


   
});
function confirmRemoveWard(wardId, wardName, wardClass) {
    // Populate modal with ward details
    var modalBody = 'Are you sure you want to remove ' + wardName + ' from class ' + wardClass + ' as a ward?';
    $('#wardDetails').html(modalBody);

    // Open modal
    $('#confirmRemoveModal').modal('show');

    // Attach click event handler to remove button in modal
    $('#confirmRemoveButton').click(function() {
        // Hide the button text and show the spinner icon
        $('#removeWardBtnText').html('<i class="fas fa-spinner fa-spin"></i>'); // Replace text with spinner icon

        // Send an AJAX request to remove the ward
        $.ajax({
            url: '/remove-ward/' + wardId,
            method: 'DELETE', // Assuming you're using DELETE method to remove the ward
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token in the headers
            },
            success: function(response) {
                // Display success message
                $('#ward-message').html('Ward removed successfully.').fadeIn().delay(3000).fadeOut();
                // Reload the page after 3 seconds
                setTimeout(function() {
                    location.reload();
                }, 3000);
            },
            error: function(xhr, status, error) {
                // Display error message
                console.log(xhr.responseText)
                $('#ward-error').html('Failed to remove ward. Please try again.').fadeIn().delay(3000).fadeOut();
            },
            complete: function() {

                $('#confirmRemoveModal').modal('hide');
                // Show the button text again after the AJAX request completes
                $('#removeWardBtnText').html('Remove Ward'); // Restore original text
            }
        });
    });
}


</script>
<script>
    
    document.addEventListener("DOMContentLoaded", function() {
        const courseLinks = document.querySelectorAll('.course-link');

        courseLinks.forEach(link => {
            link.addEventListener('click', function() {
                const target = this.dataset.target;

                // Close all other course collapses
                const allCourseCollapses = document.querySelectorAll('.collapse.show');
                allCourseCollapses.forEach(collapse => {
                    if (collapse.id !== target) {
                        $(collapse).collapse('hide');
                    }
                });
            });
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.show-more').forEach(function(showMoreLink) {
        showMoreLink.addEventListener('click', function(event) {
            event.preventDefault();
            var lessonId = this.getAttribute('data-lesson-id');
            document.getElementById('fullDescription' + lessonId).style.display = 'block';
        });
    });

    document.querySelectorAll('.show-less').forEach(function(showLessLink) {
        showLessLink.addEventListener('click', function(event) {
            event.preventDefault();
            var lessonId = this.getAttribute('data-lesson-id');
            document.getElementById('fullDescription' + lessonId).style.display = 'none';
        });
    });
});

</script>
</body>
</html>

@extends('layouts.app')

@section('page_title')
<b>{{  $section->name  }}</b>
@endsection

@section('title')
Central School System - {{$section->name }}- Class Section
@endsection

@section('style')
<style>
    .complete_profile {
        display: none;
    }

    .profile_pic_style {
        cursor: pointer;
        position: absolute;
        bottom: -10px;
        right: 100px;
    }

    .qualification-container {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }

    .admin-card {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .admin-card .card-body {
        padding: 15px;
    }

    .user-permissions {
        display: flex;
        flex-direction: column;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }

    .details-heading {
        font-size: 16px;
        margin-bottom: 10px;
    }

      /* Background overlay for expanded details */
      .collapsed-details {
        height: 100%; /* Adjust the maximum height for scrollable area */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #fff; /* Text color for details */
    }

    /* Toggle button style */
    .toggle-details-btn {
        margin-top: 10px;
        padding: 8px 12px;
        background-color: #17a2b8; /* Your desired button color */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .toggle-details-btn i {
        margin-left: 5px;
    }

    /* Styling for detail labels and values */
    .detail-item {
        margin-bottom: 8px;
    }

    .detail-label {
        font-weight: bold;
    }

    .detail-value {
        color: #fff; /* Text color for detail values */
    }
    .toggle-icon {
        transition: transform 0.3s ease; /* Adjust the duration and easing function as needed */
    }




    
</style>
@endsection

@section('breadcrumb2')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3', "$section->name")

@section('content')
@include('sidebar')

<div class="row">
    <div class="col-md-12">
        <!-- USERS LIST -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$section->name}}</h3>

                <div class="card-tools">
                    
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                   <!-- Button to add admin -->
                   <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                       <sup class="text-success">{{$section->getPotentialStudents()->count()}}</sup> <i class="fas fa-user-plus"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu" style="width: 250px;">
                        @forelse($section->getPotentialStudents() as $potentialStudent)
                            <a href="#" class="dropdown-item d-flex align-items-center make-student-link" data-user-id="{{ $potentialStudent->id }}" data-section-id="{{ $section->id }}" data-toggle="modal" data-target="#confirmModal{{ $potentialStudent->id }}">
      
                                @if($potentialStudent->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $potentialStudent->profile->profile_picture) }}" alt="User Image" width="40px" class="mr-3 rounded-circle">
                                @else
                                    <i class="fas fa-camera fa-lg mr-3"></i> <!-- You can replace this with your camera icon -->
                                @endif
                                <div>
                                    <span class="font-weight-bold">{{ $potentialStudent->profile->full_name }}</span>
                                    <br>
                                    <small class="text-muted">{{ $potentialStudent->email }}</small>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            

                        @empty
                            <a href="#" class="dropdown-item">No potential Students found.</a>
                        @endforelse
                    </div>


                    <div class="modal" tabindex="-1" role="dialog" id="confirmModal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-success" id="section-message" style="display:none"></div>
                                    <div class="alert alert-danger" id="section-error" style="display:none"></div>
                                    <p></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                </div>
            </div>
            <!-- /.card-header -->
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div id="admin-message" class='alert alert-success' style="display:none"></div>
                <div id="admin-error" class='alert alert-danger' style="display:none"></div>
                <ul class="users-list clearfix">
                    
                    @forelse($section->students as $student)
                    <li class="col-md-3 col-6">
                        <div class="card admin-card" data-admin-id="{{ $student->id }}" data-admin-name="{{ $student->profile->full_name }}">
                            <div class="card-body">
                                <div class="user-profile">
                                    @if($student->profile->profile_picture)
                                        <img src="{{ asset('storage/' . $student->profile->profile_picture) }}" alt="User Image" width="150px">
                                    @else
                                        <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image" width="150px">
                                    @endif
                                    <a class="users-list-name" href="#" data-admin-name="{{ $student->profile->full_name }}">
                                        {{ $student->profile->full_name }} <br>
                                        <span class="text-success">{{ $student->userClassSection->code }}</span>
                                    </a>
                                </div>
                                <span class="users-list-date">{{ \Carbon\Carbon::parse($student->created_at)->diffForHumans() }}</span>

                                @if ($student->id !== auth()->id())
                                    <div class="user-permissions">
                                    <style>
                                    #student-details-{{ $student->id }} {
                                        /* display: none; */
                                        margin-top: 10px;
                                        position: absolute;
                                        /* background-color: #f9f9c6; */
                                        border: 1px solid #ccc;
                                        padding: 10px;
                                        z-index: 1000;
                                        width: 100%;
                                        height: 100%;
                                        max-width: calc(100% - 20px);
                                    }
                                </style>
                                        <h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="student-details-{{ $student->id }}">
                                            Details <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                        <div id="student-details-{{ $student->id }}" class="collapsed-details">
                                        <h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="student-details-{{ $student->id }}">
                                        Details <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                            <!-- Your existing details content here -->
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Email:</strong></span>
                                                <p class="detail-value small-text">{{ $student->email }}</p>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Phone:</strong></span>
                                                <p class="detail-value small-text">{{ $student->profile->phone_number ?? 'N/A' }}</span>
                                </p>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Gender:</strong></span>
                                                <p class="detail-value small-text">{{ $student->profile->gender }}</p>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Date of Birth:</strong></span>
                                                <p class="detail-value small-text">{{ $student->profile->date_of_birth ?? 'N/A' }}</span>
                                </p>
                                        </div>

                                        <!-- Action icons with improved styling -->
                                        <div class="action-icons">
                                            <a href="#" class="mr-2" title="View Student Data">
                                                <i class="action-icon fas fa-eye text-primary"></i>
                                            </a>
                                            <a href="#" class="mr-2" title="Message Student">
                                                <i class="action-icon fas fa-envelope text-success"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>

                        @empty
                        <p id="no-admin" class="p-3">No Students found for this Class Section.</p>
                        @endforelse
                    </ul>
                    <!-- /.users-list -->
                </div>


            <!-- /.card-body -->
            <div class="card-footer text-center">
                <a href="#">{{$section->name}}</a>
            </div>
            <!-- /.card-footer -->
        </div>
        <!--/.card -->
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Hide the dropdown initially
        $("#addAdminDropdown").hide();

        // Toggle dropdown on button click
        $("#addAdminButton").click(function (e) {
            e.stopPropagation(); // Prevent the dropdown from closing immediately
            $("#addAdminDropdown").toggle();
        });

        // Close the dropdown when clicking outside of it
        $(document).click(function () {
            $("#addAdminDropdown").hide();
        });
    });

    $(document).ready(function () {
        var confirmModal = $('#confirmModal');
        var confirmBtn = $('#confirmBtn');

        $('.make-student-link').on('click', function (e) {
            e.preventDefault();

            var userId = $(this).data('user-id');
            var sectionId = $(this).data('section-id'); // Change data-section-id to data-section-id
            var userName = $(this).find('.font-weight-bold').text();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Update the modal content
            confirmModal.find('.modal-body p').html('Are you sure you want to Add <strong>' + userName + '</strong> to this class Section?');

            // Show the modal
            confirmModal.modal('show');

            // Handle confirm button click
            confirmBtn.on('click', function () {
                // If the user confirms, perform AJAX request to make the user a student in the section
                $.ajax({
                    type: 'POST',
                    url: '/make-student-section/' + userId,
                    data: {
                        section_id: sectionId, // Correct the variable name to sectionId
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        // Display success message
                        $("#section-message").text(response.message).fadeIn();

                        // Hide the success message after 3 seconds
                        setTimeout(function () {
                            $("#section-message").fadeOut();
                        }, 3000);
                        location.reload();

                        // Check if the response contains information about the new student
                        if (response.newStudent) {
                            // You may need to update the following code based on your HTML structure
                            // to append the new student to the list or perform any other actions
                            console.log(response.newStudent);
                            location.reload();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        console.error(xhr.responseText);

                        try {
                            // Parse the JSON response to extract the error message
                            var response = JSON.parse(xhr.responseText);
                            var errorMessage = response.error || 'An error occurred.';

                            // Display the specific error message from the server with fade animation
                            $("#section-error").text(errorMessage).fadeIn();

                            // Hide the error message after 3 seconds with fade animation
                            setTimeout(function () {
                                $("#section-error").fadeOut();
                            }, 3000);
                        } catch (e) {
                            // If parsing fails, display a generic error message
                            console.error('Failed to parse server response:', e);
                            $("#section-error").text('An error occurred.').fadeIn();
                            
                            // Hide the error message after 3 seconds with fade animation
                            setTimeout(function () {
                                $("#section-error").fadeOut();
                            }, 3000);
                            location.reload();
                        }
                    }, 
                    compplete : function(){
                        confirmModal.hide()
                    }

                });
            });
        });
    });



</script>
@endsection

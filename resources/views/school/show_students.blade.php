@extends('layouts.app')

@section('page_title')

{{$school->name}}

@endsection

@section('title', "Central School System - $school->name - Admins")

@section('style')
<style>
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

@section('breadcrumb3', "$school->name Students")

@section('content')
@include('sidebar')

<div class="row">
    <div class="col-md-12">
        <!-- USERS LIST -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Students</h3>

                <div class="card-tools">
                    <span class="badge badge-danger">{{ $school->confirmedStudents()->count(). '/'. $school->schoolPackage->max_students . ' Student(s)' }}</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                   <!-- Button to add admin -->
                   <div class="btn-group">
                    
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
            <div class="card-body p-0">
                <div id="admin-message" class='alert alert-success' style="display:none"></div>
                <div id="admin-error" class='alert alert-danger' style="display:none"></div>

                
                @foreach($studentsByClass as $className => $students)
                <div class="admin-card">
                    <div class="card-header">
                        <h6 class="p-2">{{ $className }}  <i class="toggle-icon fas fa-chevron-down"></i></h6>
                    </div>
                    <div class="card-body">
                        <ul class="users-list clearfix">
                            @forelse($students as $student)
                            <li class="col-md-3 col-6">
                                <div class="card admin-card" data-admin-id="{{ $student->id }}" data-admin-name="{{ $student->profile->full_name }}">
                                    <div class="card-body">
                                        <div class="dropdown" style="position: absolute; top: 10px; left: 10px;">
                                            <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="studentActionsDropdown{{ $student->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <style>
                                                /* Custom styles to hide the down arrow */
                                                .dropdown-toggle::after {
                                                    content: none !important;
                                                }
                                            </style>
                                            <div class="dropdown-menu" aria-labelledby="studentActionsDropdown{{ $student->id }}">
                                                <a class="dropdown-item" href="{{ route('student', ['studentId' => $student->id]) }}">View</a>
                                                <a class="dropdown-item" href="#">Message</a>
                                                @if ($student->id !== auth()->id())
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removestudentModal{{ $student->id }}">Remove student</a>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="user-profile">
                                            @if($student->profile->profile_picture)
                                                <img src="{{ asset('storage/' . $student->profile->profile_picture) }}" alt="User Image" width="150px">
                                            @else
                                                <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image" width="150px">
                                            @endif
                                            <a class="users-list-name" href="#" data-admin-name="{{ $student->profile->full_name }}">{{ $student->profile->full_name }} <br> <span class="badge p-1 badge-info">{{ $student->userClassSection->code }}</span> </a>
                                        </div>
                                        <span class="users-list-date">{{ \Carbon\Carbon::parse($student->created_at)->diffForHumans() }}</span>

                                        <!-- Details section -->
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
                                            <div class="collapsed-details" id="student-details-{{ $student->id }}">
                                                <h5 style="cursor:pointer;padding:9px;" class="details-heading  toggle-details-btn" data-target="student-details-{{ $student->id }}">
                                                Details <i class="toggle-icon fas fa-chevron-down"></i>
                                            </h5>
                                                <div class="detail-item">
                                                    <span class="detail-label small-text"><strong>Email:</strong></span>
                                                    <p class="detail-value small-text">{{ $student->email }}</p>
                                                </div>
                                                <div class="detail-item small-text">
                                                    <span class="detail-label"><strong>Phone:</strong></span>
                                                    <p class="detail-value">{{ $student->profile->phone_number ?? 'N/A' }}</p>
                                                </div>
                                                <div class="detail-item small-text">
                                                    <span class="detail-label"><strong>Gender:</strong></span>
                                                    <p class="detail-value">{{ $student->profile->gender }}</p>
                                                </div>
                                                <div class="detail-item small-text">
                                                    <span class="detail-label"><strong>Date of Birth:</strong></span>
                                                    <p class="detail-value">{{ $student->profile->date_of_birth ?? 'N/A' }}</p>
                                                </div>
                                        </div>

                                        <!-- Remove Student Modal -->
                                        <div class="modal fade" id="removestudentModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="removestudentModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="removestudentModalLabel">Remove Student</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to remove {{ $student->profile->full_name }} as a student?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger" onclick="removestudent({{ $student->id }})">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <p id="no-admin" class="p-2">No Student found for this school.</p>
                            @endforelse
                        </ul>

                    </div>
                </div>
                @endforeach
                <!-- /.users-list -->
            </div>


            <!-- /.card-body -->
            <div class="card-footer text-center">
                <a href="#">{{$school->name}}</a>
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
        // Hide all students initially
        $('.users-list').hide();

        // Show students of the first class initially
        $('.users-list:first').closest('.admin-card').find('.card-body .users-list').slideDown();
        $('.toggle-icon').addClass('fa-chevron-down');

        // Add click student to class name for toggling students
        $('.p-2').on('click', function () {
            // Toggle the display of students for the clicked class
            var usersList = $(this).closest('.admin-card').find('.card-body .users-list');
            usersList.slideToggle();

            // Hide other students within other cards
            $('.admin-card .card-body .users-list').not(usersList).slideUp();

            // Toggle the chevron icon
            var toggleIcon = $(this).find('.toggle-icon');
            toggleIcon.toggleClass('fa-chevron-down fa-chevron-up');

            // Reset other icons
            $('.toggle-icon').not(toggleIcon).removeClass('fa-chevron-up').addClass('fa-chevron-down');
        });

        // Add click student to toggle details button
        // $('.toggle-details-btn').on('click', function () {
        //     var targetId = $(this).data('target');
        //     $('#' + targetId).toggleClass('collapsed-details');
        //     // Toggle the chevron icon
        //     $(this).find('.toggle-icon').toggleClass('fa-chevron-down fa-chevron-up');
        // });
    });



    function removestudent(studentId) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Make AJAX request to remove admin
        $.ajax({
            type: 'POST',
            url: '/remove-student/' + studentId,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                $("#removeStudentModal" + studentId).modal("hide");
                // Display success message
                $("#student-message").text(response.message).fadeIn();

                // Hide the success message after 3 seconds
                setTimeout(function () {
                    
                    $("#student-message").fadeOut();
                }, 3000);

                // Hide the modal
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();


                // Remove the parent <li> element from the list
                $("#removeStudentModal" + studentId).closest("li").remove();
                location.reload()
            },


            error: function (xhr, textStatus, errorThrown) {
                console.error(xhr.responseText);
                $("#removestudentModal" + studentId).modal("hide");

                // Parse the JSON response to get the 'error' message
                var errorMessage = 'Failed to remove admin. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }

                // Display error message
                $("#admin-error").text(errorMessage).fadeIn();

                // Hide the error message after 3 seconds
                setTimeout(function () {
                    $("#admin-error").fadeOut();
                }, 3000);
            }
        });
    }
</script>
@endsection




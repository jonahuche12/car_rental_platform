@extends('layouts.app')

@section('page_title')

{{$school->name}}

@endsection

@section('title', "Central School System - $school->name - Admins")

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
        margin-top: 15px;
    }
    .attendance-label {
    font-weight: bold;
}

.attendance-icon {
    font-size: 24px; /* Adjust size as needed */
    margin-left: 5px; /* Add some space between label and icon */
    transition: transform 0.3s ease; /* Add a smooth transition effect */
}

.attendance-icon:hover {
    transform: scale(1.2); /* Scale the icon on hover for a subtle effect */
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
                    <span class="badge badge-danger">{{ $form_classes->count()  }}</span>
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

                
                @foreach($form_classes as $form_class)
                <div class="admin-card">
                    <div class="card-header tog-header bg-dark">
                        <h6 class="p-2">{{ $form_class->name }}  <i class="toggle-icon fas fa-chevron-down"></i></h6>
                    </div>
                    <div class="card-body">
                        <ul class="users-list clearfix">
                            @forelse($form_class->students as $student)
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
                                                <div class="dropdown-divider"></div>
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
                                        <a class="users-list-name" href="#" data-admin-name="{{ $student->profile->full_name }}">{{ $student->profile->full_name }} <br> <span class="badge p-1 badge-info">{{ $student->profile->role }}</span> </a>
                                    </div>
                                    <span class="users-list-date">
                                        <!-- Inside the <div class="card-body"> loop -->
                                        <div class="student-attendance">
                                            @if ($student->attendanceForToday())
                                                <label for="attendance"  class="text-success">Today's Attendance</label>
                                                <i class="attendance-icon fas fa-check text-success" data-student-id="{{ $student->id }}" data-school-id="{{ $student->school_id }}" data-teacher-id="{{ auth()->id() }}"></i> <!-- Green check icon if attendance is true -->
                                            @else
                                                <label for="attendance" class="text-secondary">Today's Attendance</label>
                                                <i class="attendance-icon fas fa-times text-secondary" data-student-id="{{ $student->id }}" data-school-id="{{ $student->school_id }}" data-teacher-id="{{ auth()->id() }}"></i> <!-- Grey cross icon if attendance is not true -->
                                            @endif
                                            <br>
                                            <span id="attendance-message-success-{{ $student->id }}" class="text-message text-success"></span> <!-- Placeholder for success message -->
                                            <span id="attendance-message-error-{{ $student->id }}" class="text-message text-danger"></span> <!-- Placeholder for error message -->
                                        </div>

                                    </span>

                                    <div class="user-permissions">
                                        <h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="user-details-{{ $student->id }}">
                                            Details <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                        <div id="user-details-{{ $student->id }}" class="collapsed-details">
                                            <!-- Your existing details content here -->
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Email:</strong></span>
                                                <span class="detail-value">{{ $student->email }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Phone:</strong></span>
                                                <span class="detail-value">{{ $student->profile->phone_number ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Gender:</strong></span>
                                                <span class="detail-value">{{ $student->profile->gender }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Date of Birth:</strong></span>
                                                <span class="detail-value">{{ $student->profile->date_of_birth ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remove Admin Modal -->
                                    <div class="modal fade" id="removestudentModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="removestudentModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="removestudentModalLabel">Remove Admin</h5>
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

        // Add click event to class name for toggling students
        $('.p-2', '.tog-header').on('click', function () {
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

        // Add click event to toggle details button
        $('.toggle-details-btn').on('click', function () {
            var targetId = $(this).data('target');
            $('#' + targetId).toggleClass('collapsed-details');
            // Toggle the chevron icon
            $(this).find('.toggle-icon').toggleClass('fa-chevron-down fa-chevron-up');
        });
    });

</script>

<script>
   $(document).ready(function () {
    // Add click event for attendance icons
    $('.attendance-icon').on('click', function () {
        var studentId = $(this).data('student-id');
        var schoolId = $(this).data('school-id');
        var teacherId = $(this).data('teacher-id');
        var attendanceIcon = $(this);
        var attendanceLabel = attendanceIcon.siblings('label');
        var successMessageSpan = $('#attendance-message-success-' + studentId);
        var errorMessageSpan = $('#attendance-message-error-' + studentId);

        // Send AJAX request to toggle attendance
        $.ajax({
            type: 'POST',
            url: '{{ route('toggleAttendance') }}',
            data: {
                student_id: studentId,
                school_id: schoolId,
                teacher_id: teacherId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                // Toggle attendance icon based on response
                if (response.attendance) {
                    attendanceIcon.removeClass('fa-times text-secondary').addClass('fa-check text-success');
                    attendanceLabel.removeClass('text-secondary').addClass('text-success').text('Today\'s Attendance');
                    successMessageSpan.text('Attendance marked').fadeIn().delay(3000).fadeOut();
                    errorMessageSpan.text('');
                } else {
                    attendanceIcon.removeClass('fa-check text-success').addClass('fa-times text-secondary');
                    attendanceLabel.removeClass('text-success').addClass('text-secondary').text('Today\'s Attendance');
                    successMessageSpan.text('').fadeOut();
                    errorMessageSpan.text('Attendance removed').fadeIn().delay(3000).fadeOut();
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                successMessageSpan.text('').fadeOut();
                errorMessageSpan.text('Failed to toggle attendance. Please try again later.').fadeIn().delay(3000).fadeOut();
            }
        });
    });
});


</script>

@endsection




@extends('layouts.app')

@section('page_title')

{{$school->name}}

@endsection

@section('title', "CSS - $school->name - Form Classes")

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
                    <span class="badge bg-primary">{{ $form_classes->count()  }} form class(es)</span>
                    
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
                <div class="admin-card card-header p-2 tog-header collapsed"> <!-- Add 'collapsed' class here -->
                    <div class="card-header tog-header bg-primary p-2" style="cursor:pointer" >
                        <h6 class="p-2 class-header">{{ $form_class->name }}  <i class="toggle-icon fas fa-chevron-down"></i></h6>
                    </div>
                    <div class="card-body" style="display: none;"> <!-- Add 'style="display: none;"' here -->
                    
                        <ul class="users-list clearfix">
                            @forelse($form_class->students as $student)
                            <li class="col-md-3 col-">
                            <div class="card card" data-admin-id="{{ $student->id }}" data-admin-name="{{ $student->profile->full_name }}">
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
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item result-btn" href="#" data-student-id="{{ $student->id }}" data-academic-session-id="{{ $school->academicSession->id }}" data-term-id="{{ $school->term->id }}">Compile Result</a>
                                                <div class="dropdown-divider"></div>
                                            <a href="{{ route('view_student_result', ['student_id' => $student->id, 'academic_session_id' => $school->academicSession->id , 'term_id' => $school->term->id ]) }}" class="btn btn-primary btn-sm mt-2">View Result</a>


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
                                        <a class="users-list-name" href="#" data-admin-name="{{ $student->profile->full_name }}">{{ $student->profile->full_name }} <br> <span class="badge p-1 badge-primary">{{ $student->profile->role }}</span> </a>
                                    </div>
                                    <span class="users-list-date">
                                        <!-- Inside the <div class="card-body"> loop -->
                                        <div class="student-attendance">
                                            @if ($student->attendanceForToday())
                                                <label for="attendance"  class="text-success attendance-icon small-text text-success" data-student-id="{{ $student->id }}" data-school-id="{{ $student->school_id }}" data-teacher-id="{{ auth()->id() }}">Today's Attendance</label>
                                                <i class="attendance-icon small-text fas fa-check text-success" data-student-id="{{ $student->id }}" data-school-id="{{ $student->school_id }}" data-teacher-id="{{ auth()->id() }}"></i> <!-- Green check icon if attendance is true -->
                                            @else
                                                <label for="attendance" class="text-secondary attendance-icon small-text text-secondary" data-student-id="{{ $student->id }}" data-school-id="{{ $student->school_id }}" data-teacher-id="{{ auth()->id() }}">Today's Attendance</label>
                                                <i class="attendance-icon small-text fas fa-times text-secondary" data-student-id="{{ $student->id }}" data-school-id="{{ $student->school_id }}" data-teacher-id="{{ auth()->id() }}"></i> <!-- Grey cross icon if attendance is not true -->
                                            @endif
                                            <br>
                                            <span id="attendance-message-success-{{ $student->id }}" class="text-message text-success"></span> <!-- Placeholder for success message -->
                                            <span id="attendance-message-error-{{ $student->id }}" class="text-message text-danger"></span> <!-- Placeholder for error message -->
                                        </div>

                                    </span>
                                    <div class="user-permissions">
                                        <h5 style="cursor:pointer;" class="details-heading  small-text toggle-details-btn" data-target="user-details-{{ $student->id }}">
                                            Details <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                        <div id="user-details-{{ $student->id }}" class="collapsed-details">
                                        <h5 style="cursor:pointer;" class="details-heading  small-text toggle-details-btn" data-target="user-details-{{ $student->id }}">
                                            Details <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                            <div class="detail-item">
                                                <span class="detail-label small-text"><strong>Email:</strong></span>
                                                <p class="detail-value small-text">{{ $student->email }}</p>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label small-text"><strong>Phone:</strong></span>
                                                <p class="detail-value small-text">{{ $student->profile->phone_number ?? 'N/A' }}</p>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label small-text"><strong>Gender:</strong></span>
                                                <p class="detail-value small-text">{{ $student->profile->gender }}</p>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label small-text"><strong>Date of Birth:</strong></span>
                                                <p class="detail-value small-text">{{ $student->profile->date_of_birth ?? 'N/A' }}</p>
                                            </div>
                                            @if(!empty($student->guardians()))

                                            <div class="detail-item">
                                                <span class="detail-label small-text"><strong>Guardian Contact</strong></span>
                                                @foreach($student->guardians as $guardian)
                                                    <p class="detail-label badge bg-purple small-text"><b>{{$guardian->profile->full_name}} : </b>{{$guardian->profile->phone_number ?? 'N/A'}}</p>
                                                @endforeach
                                            </div>


                                            @endif
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
                        @php
                            $academicSessionId = $school->academicSession->id;
                            $hasRequiredTerms = $school->country === 'Nigeria' ? $school->academicSession->terms()->count() >= 0 : true;
                            $promotionCriteriaExists = $form_class->promotionCriteria()->where('academic_session_id', $academicSessionId)->exists();
                        @endphp

                        @if ($form_class->students->count() > 0 && $hasRequiredTerms && $form_class->students->count() === $form_class->students->filter(function($student) use ($academicSessionId) {
                            return $student->studentResults()->where('academic_session_id', $academicSessionId)->exists();
                        })->count())
                            <!-- Button trigger modal -->
                            @if ($promotionCriteriaExists)
                                <button type="button" class="btn btn-primary small-text btn-sm" data-toggle="modal" data-target="#editPromotionCriteriaModal" data-academic_session-id={{$academicSessionId}} data-section-id={{ $form_class->id }}>
                                    Edit Promotion Criteria
                                </button>
                                @if(!$form_class->promotionCriteria->isEmpty() && !$form_class->promotionCriteria->first()->student_promoted)
                                    <button type="button" class="btn btn-success btn-sm small-text" data-toggle="modal" data-target="#confirmationModal" data-academic_session-id="{{$academicSessionId}}" data-section-id="{{$form_class->id}}">
                                        Promote Eligible Students
                                    </button>
                                @endif


                            @else
                            <!-- Button trigger modal -->
                            <button id="createPromotionCriteriaButton" type="button" class="btn btn-sm small-text btn-primary" data-toggle="modal" data-target="#promotionCriteriaModal" data-academic_session-id={{$academicSessionId}} data-section-id={{ $form_class->id }}>
                                Create Promotion Criteria
                            </button>
                            @endif
                        @endif



                    </div>
                </div>
                @endforeach
                <!-- /.users-list -->
            </div>

            <!-- Promotion Criteria Modal -->
            <div class="modal fade" id="promotionCriteriaModal" tabindex="-1" role="dialog" aria-labelledby="promotionCriteriaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="promotionCriteriaModalLabel">Create Promotion Criteria</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           

                            <!-- Form for creating promotion criteria -->
                            <form id="promotionCriteriaForm" data-academic_session-id="" data-section-id="">
                                <!-- Checkbox to promote all students -->
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="promoteAllStudents">
                                <label class="form-check-label" for="promoteAllStudents">Promote all students regardless of performance</label>
                            </div>
                                <!-- Criteria 1: Required Average Score -->
                                <div id="promotionCriteria">
                                <div class="form-group">
                                    <label for="requiredAvgScore">Required Average Score</label>
                                    <input type="number" class="form-control" id="requiredAvgScore" name="requiredAvgScore" placeholder="Enter required average score" required>
                                    <small class="form-text text-muted">Enter the minimum average score required for promotion.</small>
                                </div>

                                <!-- Criteria 2: Total Attendance Percentage -->
                                <div class="form-group">
                                    <label for="totalAttendancePercentage">Total Attendance Percentage</label>
                                    <input type="number" class="form-control" id="totalAttendancePercentage" name="totalAttendancePercentage" placeholder="Enter total attendance percentage" required>
                                    <small class="form-text text-muted">Enter the minimum total attendance percentage required for promotion.</small>
                                </div>

                                <!-- Criteria 3: Average Score for Compulsory Courses -->
                                <div class="form-group">
                                    <label for="compulsoryCoursesAvgScore">Average Score for Compulsory Courses</label>
                                    <input type="number" class="form-control" id="compulsoryCoursesAvgScore" name="compulsoryCoursesAvgScore" placeholder="Enter average score for compulsory courses" required>
                                    <small class="form-text text-muted">Enter the minimum average score required for compulsory courses.</small>
                                </div>


                                </div>
                                
                            </form>
                            <!-- Description -->
                            <p id="promotionCriteriaDescription"><strong>Description:</strong> To determine whether a student is eligible for promotion, provide the required average score, total attendance percentage, and average score for compulsory courses.</p>
                        </div>
                        <p class="alert alert-success p-2" id="criteria-message" style="display:none"></p>
                        <p class="alert alert-danger p-2" id="criteria-error" style="display:none"></p>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="savePromotionCriteria">Continue</button>
                        </div>
                    </div>
                </div>
            </div>


<!-- Edit Promotion Criteria Modal -->
<div class="modal fade" id="editPromotionCriteriaModal" tabindex="-1" role="dialog" aria-labelledby="editPromotionCriteriaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="editPromotionCriteriaModalLabel">Edit Promotion Criteria</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing promotion criteria -->
                <form id="editPromotionCriteriaForm" data-academic_session-id="{{ $academicSessionId }}" data-section-id="{{ $form_class->id }}">
                    <!-- Checkbox to promote all students -->
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="editPromoteAllStudents">
                        <label class="form-check-label" for="editPromoteAllStudents">Promote all students regardless of performance</label>
                    </div>
                    <!-- Criteria 1: Required Average Score -->
                    <div id="promotionEditCriteria">
                    <div class="form-group">
                        <label for="editRequiredAvgScore">Required Average Score</label>
                        <input type="number" class="form-control" id="editRequiredAvgScore" name="requiredAvgScore" placeholder="Enter required average score" value="" required>
                        <small class="form-text text-muted">Enter the minimum average score required for promotion.</small>
                    </div>
                    <!-- Criteria 2: Total Attendance Percentage -->
                    <div class="form-group">
                        <label for="editTotalAttendancePercentage">Total Attendance Percentage</label>
                        <input type="number" class="form-control" id="editTotalAttendancePercentage" name="totalAttendancePercentage" placeholder="Enter total attendance percentage" value="" required>
                        <small class="form-text text-muted">Enter the minimum total attendance percentage required for promotion.</small>
                    </div>
                    <!-- Criteria 3: Average Score for Compulsory Courses -->
                    <div class="form-group">
                        <label for="editCompulsoryCoursesAvgScore">Average Score for Compulsory Courses</label>
                        <input type="number" class="form-control" id="editCompulsoryCoursesAvgScore" name="compulsoryCoursesAvgScore" placeholder="Enter average score for compulsory courses" value="" required>
                        <small class="form-text text-muted">Enter the minimum average score required for compulsory courses.</small>
                    </div>
                </div>
                </form>
                <!-- Description -->
                <p id="editPromotionCriteriaDescription"><strong>Description:</strong> To determine whether a student is eligible for promotion, provide the required average score, total attendance percentage, and average score for compulsory courses.</p>
            </div>
                <p class="alert alert-success p-2" id="edit-criteria-message" style="display:none"></p>
                <p class="alert alert-danger p-2" id="edit-criteria-error" style="display:none"></p>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-primary" id="saveEditPromotionCriteria">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true" data-academic_session_modal-id="" data-section_modal-id="" data-next_class-id="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Promotion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success p-2" id="promotion-message" style="display:none"></p>
            <p class="alert alert-danger p-2" id="promotion-error" style="display:none"></p>
            <div class="modal-body">
                Are you sure you want to promote all eligible students to the next class <b> <span id="next-class"></span></b> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPromotion">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                    Confirm
                </button>
            </div>
        </div>
    </div>
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
    $(document).on('click', '[data-target="#confirmationModal"]', function () {
        // Get the academic session ID and class section ID from the button's data attributes
        var academicSessionId = $(this).data('academic_session-id');
        var classSectionId = $(this).data('section-id');
        console.log(classSectionId);
        
        // Set the academic session ID and class section ID in the confirmation modal
        $('#confirmationModal').data('academic_session_modal-id', academicSessionId);
        $('#confirmationModal').data('section_modal-id', classSectionId);
        
        // AJAX request to get the next class
        $.ajax({
            url: '{{ route("get_next_class") }}',
            type: 'POST',
            data: {
                class_section_id: classSectionId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Update the next class in the modal
                $('#next-class').text(response.next_class);
                $('#confirmationModal').data('next_class-id', response.next_class_id);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    });

$(document).ready(function() {
    // Handle click event of the confirmation button
    $('#confirmPromotion').click(function() {
        var academicSessionId = $('#confirmationModal').data('academic_session_modal-id');
        var classSectionId = $('#confirmationModal').data('section_modal-id');
        var nextClassId = $('#confirmationModal').data('next_class-id');
        var confirmButton = $(this);

        // Show spinner icon and disable the button
        confirmButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Confirming...');
        confirmButton.prop('disabled', true);

        // Make AJAX request to promote students
        $.ajax({
            url: '{{ route("promote_students") }}',
            type: 'POST',
            data: {
                academic_session_id: academicSessionId,
                class_section_id: classSectionId,
                next_class_id: nextClassId,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Handle success response
                console.log(response);
                $('#promotion-message').text(response.message).fadeIn().delay(6000).fadeOut(function(){
                    // Close the modal and reload the page
                    $('#confirmationModal').modal('hide');
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                $('#promotion-error').text(xhr.responseText).fadeIn();
            },
            complete: function() {
                // Delay restoration of button text and re-enabling the button for up to 6 seconds
                setTimeout(function() {
                    // Restore button text and enable the button
                    confirmButton.html('Confirm');
                    confirmButton.prop('disabled', false);
                }, 6000);
            }
        });
    });
});


</script>



<script>
    $(document).on('click', '[data-target="#editPromotionCriteriaModal"]', function () {
        // Get the academic session ID and class section ID from the data attributes of the button
        var academicSessionId = $(this).data('academic_session-id');
        var classSectionId = $(this).data('section-id');

        // Populate the modal fields with the retrieved data
        $('#editPromotionCriteriaForm').attr('data-academic_session-id', academicSessionId);
        $('#editPromotionCriteriaForm').attr('data-section-id', classSectionId);

        // AJAX request to fetch the criteria values
        $.ajax({
            url: '/fetch-criteria-values', // Replace this with your actual endpoint
            type: 'GET',
            data: {
                academic_session_id: academicSessionId,
                class_section_id: classSectionId
            },
            success: function (response) {
                // Check if all values are 0, if yes, check the checkbox and hide the criteria section
                if (response.requiredAvgScore == 0 && response.totalAttendancePercentage == 0 && response.compulsoryCoursesAvgScore == 0) {
                    $('#editPromoteAllStudents').prop('checked', true);
                    $('#editRequiredAvgScore').val(response.requiredAvgScore);
                    $('#editTotalAttendancePercentage').val(response.totalAttendancePercentage);
                    $('#editCompulsoryCoursesAvgScore').val(response.compulsoryCoursesAvgScore);
                    $('#promotionEditCriteria').hide();
                    $('#editPromotionCriteriaDescription').hide(); // Hide description
                } else {
                    // Populate the criteria fields with the retrieved data
                    $('#editRequiredAvgScore').val(response.requiredAvgScore);
                    $('#editTotalAttendancePercentage').val(response.totalAttendancePercentage);
                    $('#editCompulsoryCoursesAvgScore').val(response.compulsoryCoursesAvgScore);
                    $('#promotionEditCriteria').show();
                    $('#editPromotionCriteriaDescription').show(); // Show description
                }
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
                console.error(error);
            }
        });
    });

    // Event listener for the checkbox to toggle the promotionEditCriteria section and description
    $('#editPromoteAllStudents').change(function () {
        if ($(this).is(':checked')) {
            $('#promotionEditCriteria').hide();
            $('#editPromotionCriteriaDescription').hide(); // Hide description
        } else {
            $('#promotionEditCriteria').show();
            $('#editPromotionCriteriaDescription').show(); // Show description
        }
    });

    $(document).ready(function() {
        // Handle form submission
        $('#saveEditPromotionCriteria').click(function() {
            // Serialize form data
            var formData = $('#editPromotionCriteriaForm').serialize();

            // Check if the 'Promote all students' checkbox is checked
            var editPromoteAllStudents = $('#editPromoteAllStudents').is(':checked');
            if (editPromoteAllStudents) {
                console.log(editPromoteAllStudents)
                // Set all form fields to zero
                formData += '&requiredAvgScore=0&totalAttendancePercentage=0&compulsoryCoursesAvgScore=0';
            }
            console.log("Not checked")

            // Get academicSessionId and class_section_id from data attributes
            var academicSessionId = $('#editPromotionCriteriaForm').data('academic_session-id');
            var classSectionId = $('#editPromotionCriteriaForm').data('section-id');

            // Append academicSessionId and class_section_id to formData
            formData += '&academicSessionId=' + academicSessionId + '&class_section_id=' + classSectionId;

            // Send AJAX request
            $.ajax({
                url: '{{ route("update_promotion_criteria") }}', // Replace with your actual route
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Show success message and fade out after 3 seconds
                    $('#edit-criteria-message').html(response.message).fadeIn().delay(3000).fadeOut();
                    setTimeout(function() {
                        location.reload(); // Reload page after 3 seconds
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    // Log error to console
                    console.log(xhr.responseText);

                    // Show error message and fade out after 3 seconds
                    $('#edit-criteria-error').html(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
                }
            });
        });
    });

</script>


<script>
   $(document).ready(function() {
    // Handle form submission
    $('#savePromotionCriteria').click(function() {
        // Serialize form data
        var formData = $('#promotionCriteriaForm').serialize();
        
        // Check if the 'Promote all students' checkbox is checked
        var promoteAllStudents = $('#promoteAllStudents').is(':checked');
        if (promoteAllStudents) {
            // Set all form fields to zero
            formData += '&requiredAvgScore=0&totalAttendancePercentage=0&compulsoryCoursesAvgScore=0';
        }

        // Get academicSessionId and class_section_id from data attributes
        var academicSessionId = $('#promotionCriteriaForm').data('academic_session-id');
        var classSectionId = $('#promotionCriteriaForm').data('section-id');
        console.log(academicSessionId)

        // Append academicSessionId and class_section_id to formData
        formData += '&academicSessionId=' + academicSessionId + '&class_section_id=' + classSectionId;

        // Send AJAX request
        $.ajax({
            url: '{{ route("promotion_criteria.store") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Show success message and fade out after 3 seconds
                $('#criteria-message').html(response.message).fadeIn().delay(3000).fadeOut();
                setTimeout(function() {
                    location.reload(); // Reload page after 3 seconds
                }, 3000);
            },
            error: function(xhr, status, error) {
                // Log error to console
                console.log(xhr.responseText);

                // Show error message and fade out after 3 seconds
                $('#criteria-error').html(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
            }
        });
    });
});

</script>

<script>
      $(document).ready(function() {
        // Function to fill academic session ID and class section ID dynamically
        $('#createPromotionCriteriaButton').click(function() {
            // Get academic session ID and class section ID from data attributes
            var academicSessionId = $(this).data('academic_session-id');
            var classSectionId = $(this).data('section-id');
            
            // Set the data attributes of the form
            $('#promotionCriteriaForm').attr('data-academic_session-id', academicSessionId);
            $('#promotionCriteriaForm').attr('data-section-id', classSectionId);
        });
    });
    // JavaScript to show/hide the promotion criteria form based on checkbox status
    $(document).ready(function() {
        $('#promoteAllStudents').change(function() {
            if (this.checked) {
                $('#promotionCriteria').hide(); // Hide the promotion criteria form
                $('#promotionCriteriaDescription').text('All students in the class will automatically be promoted.'); // Change description
            } else {
                $('#promotionCriteria').show(); // Show the promotion criteria form
                $('#promotionCriteriaDescription').text('To determine whether a student is eligible for promotion, provide the required average score, total attendance percentage, and average score for compulsory courses. '); // Reset description
            }
        });
    });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Get all form class headers
    var classHeaders = document.querySelectorAll('.class-header');

    // Add click event listener to each header
    classHeaders.forEach(function (header) {
        header.addEventListener('click', function () {
            // Get the parent form class element
            var formClass = this.parentElement.parentElement;

            // Close all other form classes except the clicked one
            var allFormClasses = document.querySelectorAll('.admin-card');
            allFormClasses.forEach(function (formCard) {
                if (formCard !== formClass) {
                    formCard.classList.add('collapsed');
                    formCard.querySelector('.card-body').style.display = 'none';
                }
            });

            // Toggle the collapsed class for the clicked form class
            formClass.classList.toggle('collapsed');

            // Toggle the visibility of the card body for the clicked form class
            var cardBody = this.parentElement.nextElementSibling;
            if (cardBody.style.display === 'none') {
                cardBody.style.display = 'block';
            } else {
                cardBody.style.display = 'none';
            }
        });
    });
});

</script>

<script>
  $(document).ready(function () {
    // Add click event for attendance labels
    $('.attendance-icon').siblings('label').on('click', function () {
        var attendanceLabel = $(this);
        var attendanceIcon = attendanceLabel.siblings('.attendance-icon');
        var studentId = attendanceIcon.data('student-id');
        var schoolId = attendanceIcon.data('school-id');
        var teacherId = attendanceIcon.data('teacher-id');
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

<script>
   $(document).ready(function () {
    // Add click event to the "Result" button
    $('.result-btn').on('click', function (e) {
        e.preventDefault();

        // Retrieve data attributes from the button
        var studentId = $(this).data('student-id');
        var academicSessionId = $(this).data('academic-session-id');
        var termId = $(this).data('term-id');

        // Make an AJAX request to compile results
        $.ajax({
            url: '/compile-results/' + studentId,
            type: 'GET',
            data: {
                academic_session_id: academicSessionId,
                term_id: termId
            },
            success: function (response) {
                console.log(response)
                // Redirect to the result page with the compiled results as data
                window.location.href = response.link;
            },
            error: function (xhr, status, error) {
                // Handle errors if any
                console.log(xhr.responseText)
                console.error(error);
            }
        }).done(function(response) {
            // Store the results in sessionStorage
            sessionStorage.setItem('compiledResults', JSON.stringify(response.results));
        });
    });
});


</script>

@endsection




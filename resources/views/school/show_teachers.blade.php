@extends('layouts.app')


@section('page_title')

{{$school->name}}

@endsection


@section('title', "Central School System - $school->name - Teachers")

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
        font-size: 18px;
        margin-bottom: 10px;
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
@endsection

@section('breadcrumb2')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3', "$school->name Teachers")

@section('content')
@include('sidebar')

<div class="row">
    <div class="col-md-12">
        <!-- USERS LIST -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Teachers</h3>

                <div class="card-tools">
                    <span class="badge badge-danger">{{ $school->confirmedTeachers->count(). '/'. $school->schoolPackage->max_teachers . ' Teacher(s)' }}</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                   <!-- Button to add admin -->
                   <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                       <sup class="text-success">{{$school->potentialTeachers()->count()}}</sup> <i class="fas fa-user-plus"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu" style="width: 250px;">
                        @forelse($school->potentialTeachers as $potentialTeacher)
                            @if($potentialTeacher->id != auth()->user()->id)
                            <a href="#" class="dropdown-item d-flex align-items-center make-teacher-link" data-user-id="{{ $potentialTeacher->id }}" data-school-id="{{ $school->id }}" data-toggle="modal" data-target="#confirmModal{{ $potentialTeacher->id }}">
      
                                @if($potentialTeacher->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $potentialTeacher->profile->profile_picture) }}" alt="User Image" width="40px" class="mr-3 rounded-circle">
                                @else
                                    <i class="fas fa-camera fa-lg mr-3"></i> <!-- You can replace this with your camera icon -->
                                @endif
                                <div>
                                    <span class="font-weight-bold">{{ $potentialTeacher->profile->full_name }}</span>
                                    <br>
                                    <small class="text-muted">{{ $potentialTeacher->email }}</small>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            @endif
                            

                        @empty
                            <a href="#" class="dropdown-item">No potential Teacher found.</a>
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
                <div id="teacher-message" class='alert alert-success' style="display:none"></div>
                <div id="admin-error" class='alert alert-danger' style="display:none"></div>
                <ul class="users-list clearfix">
                    {{-- Display other admins --}}
                    @forelse($school->confirmedTeachers as $teacher)
                        <li class="col-md-3 col-6">
                            <div class="card admin-card" data-admin-id="{{ $teacher->id }}" data-admin-name="{{ $teacher->profile->full_name }}">
                                <div class="card-body">
                                    <div class="dropdown" style="position: absolute; top: 10px; left: 10px;">
                                        <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="teacherActionsDropdown{{ $teacher->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <style>
                                            /* Custom styles to hide the down arrow */
                                            .dropdown-toggle::after {
                                                content: none !important;
                                            }
                                        </style>
                                        <div class="dropdown-menu" aria-labelledby="teacherActionsDropdown{{ $teacher->id }}">
                                            <a class="dropdown-item" href="#">View</a>
                                            <a class="dropdown-item" href="#">Message</a>
                                            @if ($teacher->id !== auth()->id())
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removeTeacherModal{{ $teacher->id }}">Remove Teacher</a>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="user-profile">
                                        @if($teacher->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $teacher->profile->profile_picture) }}" alt="User Image" width="150px">
                                        @else
                                            <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image" width="150px">
                                        @endif
                                        <a class="users-list-name" href="#" data-admin-name="{{ $teacher->profile->full_name }}">{{ $teacher->profile->full_name }} <br> <span class="badge p-1 badge-info">{{ $teacher->profile->role }}</span> </a>
                                    </div>
                                    <span class="users-list-date">{{ \Carbon\Carbon::parse($teacher->created_at)->diffForHumans() }}</span>

                                    <div class="user-permissions">
                                        <h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="user-details-{{ $teacher->id }}">
                                            Details <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                        <div id="user-details-{{ $teacher->id }}" class="collapsed-details">
                                            <!-- Your existing details content here -->
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Email:</strong></span>
                                                <span class="detail-value">{{ $teacher->email }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Phone:</strong></span>
                                                <span class="detail-value">{{ $teacher->profile->phone_number ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Gender:</strong></span>
                                                <span class="detail-value">{{ $teacher->profile->gender }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label"><strong>Date of Birth:</strong></span>
                                                <span class="detail-value">{{ $teacher->profile->date_of_birth ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remove Admin Modal -->
                                    <div class="modal fade" id="removeTeacherModal{{ $teacher->id }}" tabindex="-1" role="dialog" aria-labelledby="removeTeacherModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="removeTeacherModalLabel">Remove Admin</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to remove {{ $teacher->profile->full_name }} as a Teacher?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger" onclick="removeTeacher({{ $teacher->id }})">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <p class="m-4" id="no-admin">No Teachers found for this school.</p>
                    @endforelse
                </ul>
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

        $('.make-teacher-link').on('click', function (e) {
            e.preventDefault();

            var userId = $(this).data('user-id');
            var schoolId = $(this).data('school-id');
            var userName = $(this).find('.font-weight-bold').text();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Update the modal content
            confirmModal.find('.modal-body p').html('Are you sure you want to make <strong>' + userName + '</strong> a Teacher?');

            // Show the modal
            confirmModal.modal('show');

            // Handle confirm button click
            confirmBtn.one('click', function () {
                // If the user confirms, perform AJAX request to make the user a teacher
                $.ajax({
                    type: 'POST',
                    url: '/make-teacher/' + userId,
                    data: {
                        school_id: schoolId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        // Display success message
                        $("#teacher-message").text(response.message).fadeIn();

                        // Hide the success message after 3 seconds
                        setTimeout(function () {
                            $("#teacher-message").fadeOut();
                        }, 3000);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle the error response
                        console.log(xhr.responseText)
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Failed to make the user a teacher. Please try again.';
                        console.error(errorMessage);
                        // Optionally, display an error message to the user
                    },
                });

                // Close the modal
                confirmModal.modal('hide');
            });
        });
    });


    function removeTeacher(teacherid) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Make AJAX request to remove admin
        $.ajax({
            type: 'POST',
            url: '/remove-teacher/' + teacherid,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                // Display success message
                $("#teacher-message").text(response.message).fadeIn();

                // Hide the success message after 3 seconds
                setTimeout(function () {
                    
                    $("#teacher-message").fadeOut();
                }, 3000);

                // Hide the modal
                $("#removeTeacherModal" + teacherid).modal("hide");
                $("#removeTeacherModal" + teacherid).modal("hide");
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();


                // Remove the parent <li> element from the list
                $("#removeTeacherModal" + teacherid).closest("li").remove();
                location.reload()
            },


            error: function (xhr, textStatus, errorThrown) {
                console.error(xhr.responseText);
                $("#removeTeacherModal" + teacherid).modal("hide");

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

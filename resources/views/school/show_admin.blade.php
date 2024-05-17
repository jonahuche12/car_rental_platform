@extends('layouts.app')

@section('page_title', "$school->name Admins")

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
        display: flex;
        flex-direction: column;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
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

@section('breadcrumb3', "$school->name Admins")

@section('content')
@include('sidebar')

<div class="row">
    <div class="col-md-12">
        <!-- USERS LIST -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Admins</h3>

                <div class="card-tools">
                    <span class="badge badge-danger">{{ $school->getConfirmedAdmins()->count(). '/'. $school->schoolPackage->max_admins . ' admin(s)' }}</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                   <!-- Button to add admin -->
                   <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                       <sup class="text-success">{{$school->getPotentialAdmins()->count()}}</sup> <i class="fas fa-user-plus"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu" style="width: 250px;">
                        @forelse($school->getPotentialAdmins() as $potentialAdmin)
                            <a href="#" class="dropdown-item d-flex align-items-center make-admin-link" data-user-id="{{ $potentialAdmin->id }}" data-school-id="{{ $school->id }}" data-toggle="modal" data-target="#confirmModal{{ $potentialAdmin->id }}">
      
                                @if($potentialAdmin->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $potentialAdmin->profile->profile_picture) }}" alt="User Image" width="40px" class="mr-3 rounded-circle">
                                @else
                                    <i class="fas fa-camera fa-lg mr-3"></i> <!-- You can replace this with your camera icon -->
                                @endif
                                <div>
                                    <span class="font-weight-bold">{{ $potentialAdmin->profile->full_name }}</span>
                                    <br>
                                    <small class="text-muted">{{ $potentialAdmin->email }}</small>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            

                        @empty
                            <a href="#" class="dropdown-item">No potential admins found.</a>
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
                <div id="admin-message" class='alert alert-success' style="display:none"></div>
                <div id="admin-error" class='alert alert-danger' style="display:none"></div>
                <ul class="users-list clearfix">
                    {{-- Display School Owner as the first admin --}}
                    <li class="col-md-3 col-6">
                        <div class="card admin-card">
                            <div class="card-body">
                                <div class="user-profile">
                                    <img src="{{ $school->schoolOwner->profile->profile_picture ? asset('storage/' . $school->schoolOwner->profile->profile_picture) : asset('dist/img/default-profile-picture.jpg') }}" alt="User Image" width="150px">
                                    <a class="users-list-name" href="#">{{ $school->schoolOwner->profile->full_name }} <br> <span class="text-success">(Owner)</span> </a>
                                </div>
                                <span class="users-list-date">{{ \Carbon\Carbon::parse($school->schoolOwner->created_at)->diffForHumans() }}</span>
                                <!-- Add permission checkboxes for school owner if needed -->
                            </div>
                        </div>
                    </li>

                    {{-- Display other admins --}}
                    @forelse($school->getConfirmedAdmins() as $admin)
                        <li class="col-md-3 col-6">
                        <div class="card admin-card" data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->profile->full_name }}">
   
                                <div class="card-body">
                                <div class="user-profile">
                                    @if($admin->profile->profile_picture)
                                        <img src="{{ asset('storage/' . $admin->profile->profile_picture) }}" alt="User Image" width="150px">
                                    @else
                                    <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image" width="150px">
                                    @endif
                                    <a class="users-list-name" href="#" data-admin-name="{{ $admin->profile->full_name }}">{{ $admin->profile->full_name }} <br> <span class="badge p-1 badge-info">{{ $admin->profile->role }}</span> </a>
                                </div>

                                    <span class="users-list-date">{{ \Carbon\Carbon::parse($admin->created_at)->diffForHumans() }}</span>
                                    <!-- Add permission checkboxes -->
                                    @if ($admin->id !== auth()->id())
                                    <div class="user-permissions">
                                        <h5 style="cursor:pointer;" class="details-heading btn bg-primary btn-sm toggle-details-btn" data-target="user-details-{{ $admin->id }}">
                                            Permissions <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                        <div id="user-details-{{ $admin->id }}" class="collapsed-details">
                                        <!-- Add your permission checkboxes here as you have already done -->
                                        <h5 style="cursor:pointer;" class="details-heading  btn bg-primary btn-sm toggle-details-btn" data-target="user-details-{{ $admin->id }}">
                                            Permissions <i class="toggle-icon fas fa-chevron-down"></i>
                                        </h5>
                                        <div class="form-group mb-2">
                                            <label for="confirm-student" class="small-text" >Confirm Student</label>
                                            <input type="checkbox"  class="small-text" data-permission="permission_confirm_student" {{ $admin->profile->permission_confirm_student ? 'checked' : '' }}>
    
                                        </div>
                                        <!-- <div class="form-group mb-2">

                                        <label for="confirm-admin" class="small-text" >Confirm Admin </label>
                                        
                                        <input type="checkbox" data-permission="permission_confirm_admin" {{ $admin->profile->permission_confirm_admin ? 'checked' : '' }}>
    
                                        </div> -->
                                        <div class="form-group mb-2">

                                        <label for="confirm-admin" class="small-text" >Confirm Teachers </label>
                                        
                                        <input type="checkbox" data-permission="permission_confirm_teacher" {{ $admin->profile->permission_confirm_teacher ? 'checked' : '' }}>
    
                                        </div>
                                        <!-- <div class="form-group mb-2">

                                        <label for="confirm-admin" class="small-text" >Confirm Staff</label>
                                        <input type="checkbox" data-permission="permission_confirm_staff" {{ $admin->profile->permission_confirm_staff ? 'checked' : '' }}>
    
                                        </div> -->
                                        <div class="form-group mb-2">

                                        <label for="confirm-admin" class="small-text" >Create Course</label>
                                        <input type="checkbox" data-permission="permission_create_course" {{ $admin->profile->permission_create_course ? 'checked' : '' }}>
    
                                        </div>
                                        <div class="form-group mb-2">

                                        <label for="confirm-admin" class="small-text" >Create Events</label>
                                        <input type="checkbox" data-permission="permission_create_event" {{ $admin->profile->permission_create_event ? 'checked' : '' }}>
    
                                        </div>
                                        <div class="form-group mb-2">

                                        <label for="confirm-admin" class="small-text" >Create Lesson</label>
                                        <input type="checkbox" data-permission="permission_create_lesson" {{ $admin->profile->permission_create_lesson ? 'checked' : '' }}>
    
                                        </div>
                                        <div class="form-group mb-2">

                                        <label for="confirm-admin" class="small-text" >Create Class</label>
                                        <input type="checkbox" data-permission="permission_create_class" {{ $admin->profile->permission_create_class ? 'checked' : '' }}>
    
                                        </div>

                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#removeAdminModal{{ $admin->id }}">
                                            Remove Admin
                                        </button>

                                        </div>
                                        <!-- <div class="action-icons">
                                            <a href="#" class="mr-2" title="View Student Data">
                                                <i class="action-icon fas fa-eye text-primary"></i>
                                            </a>
                                            <a href="#" class="mr-2" title="Message Student">
                                                <i class="action-icon fas fa-envelope text-success"></i>
                                            </a>
                                        </div> -->

                                        <!-- Remove Admin Modal -->
                                        <div class="modal fade" id="removeAdminModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="removeAdminModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="removeAdminModalLabel">Remove Admin</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to remove {{ $admin->profile->full_name }} as an admin?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger" onclick="removeAdmin({{ $admin->id }})">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Permission Confirmation Modal -->
                                        <div class="modal fade" id="permissionConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="permissionConfirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="permissionConfirmationModalLabel">Permission Confirmation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" id="permissionConfirmationModalContent">
                                                        <!-- Dynamic content will be inserted here -->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" id="permissionConfirmationModalCancelBtn" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-primary" id="permissionConfirmationModalConfirmBtn">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        </div>
                                    </div>
                                    @endif
                                </div>
                        </li>
                        @empty
                        <p id="no-admin">No admins found for this school.</p>
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

$('.make-admin-link').on('click', function (e) {
    e.preventDefault();

    var userId = $(this).data('user-id');
    var schoolId = $(this).data('school-id');
    var userName = $(this).find('.font-weight-bold').text();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Update the modal content
    confirmModal.find('.modal-body p').html('Are you sure you want to make <strong>' + userName + '</strong> (ID: ' + userId + ') an admin?');

    // Show the modal
    confirmModal.modal('show');

    // Handle confirm button click
    confirmBtn.on('click', function () {
        // If the user confirms, perform AJAX request to make the user an admin
        $.ajax({
            type: 'POST',
            url: '/make-admin/' + userId,
            data: {
                school_id: schoolId,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            // ... rest of your AJAX code
            success: function (response) {
                // Display success message
                $("#admin-message").text(response.message).fadeIn();

                // Hide the success message after 3 seconds
                setTimeout(function () {
                    $("#admin-message").fadeOut();
                }, 3000);

                // Check if the response contains information about the new admin
                if (response.newAdmin) {
                    // Append the new admin to the list
                    $("#no-admin").hide();
                    var newAdmin = response.newAdmin;
                    var profilePictureUrl = response.profile_picture_url || '/dist/img/default-profile-picture.jpg';

                    var adminListItem = '<li>' +
                        '<div class="user-profile">' +
                        (profilePictureUrl ?
                            '<img src="' + profilePictureUrl + '" alt="User Image" width="150px">' :
                            '<i class="fas fa-camera" style="font-size: 150px;"></i>'
                        ) +
                        '<a class="users-list-name" href="#">' + newAdmin.profile.full_name + '</a>' +
                        '<span class="users-list-date">Today</span>' +
                        '</div>' +
                        '</li>';

                    $(".users-list").append(adminListItem);
                    location.reload();
                }
            },
            // ... rest of your AJAX code
        });

        // Close the modal
        confirmModal.modal('hide');
    });
});
});


$(document).ready(function () {
    // Event listener for permission checkboxes
    $('.user-permissions input[type="checkbox"]').on('change', function () {
        var permissionName = $(this).data('permission');
        var adminId = $(this).closest('.admin-card').data('admin-id');
        var adminName = $(this).closest('.admin-card').data('admin-name');

        if (!adminId) {
            console.error("No admin found. Unable to perform the action.");
            return;
        }

        // Set dynamic content in the confirmation modal
        $('#permissionConfirmationModalContent').html('Are you sure you want to ' + ($(this).prop('checked') ? 'grant' : 'revoke') + '<strong> ' + permissionName + '</strong>  for <strong>'+ adminName + '</strong');

        // Show the modal for permission confirmation
        $('#permissionConfirmationModal').modal('show');

        // Handle confirm button click in the modal
        $('#permissionConfirmationModalConfirmBtn').off('click').on('click', function () {
            $('#permissionConfirmationModal').modal('hide');

            // Call the function to grant or revoke permission
            grantPermission(adminId, permissionName);
        });

        // Uncheck the checkbox if the user cancels the confirmation
        $('#permissionConfirmationModalCancelBtn').off('click').on('click', function () {
            $(this).prop('checked', !$(this).prop('checked'));
            $('#permissionConfirmationModal').modal('hide');
        });
    });
});


function grantPermission(adminId, permissionName) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Determine whether to grant or revoke the permission
    var grantPermission = !$('#' + permissionName).prop('checked');

    // Make AJAX request to grant or revoke permission
    $.ajax({
        type: 'POST',
        url: '/grant-permission/' + adminId,
        data: {
            permission: permissionName,
            grant: grantPermission, // Pass whether to grant or revoke the permission
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            if (response.message) {
                // Display success message
                $("#admin-message").text(response.message).fadeIn();

                // Hide the success message after 3 seconds
                setTimeout(function () {
                    $("#admin-message").fadeOut();
                }, 3000);
            } else {
                // Display error message
                var errorMessage = 'Failed to grant/revoke permission. Please try again.';

                if (response.error) {
                    errorMessage = response.error;
                }

                $("#admin-error").text(errorMessage).fadeIn();

                // Hide the error message after 3 seconds
                setTimeout(function () {
                    $("#admin-error").fadeOut();
                }, 3000);
            }

            // You can also perform any other actions or updates based on the response
            console.log(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            // Handle error
            console.error(xhr);

            var errorMessage = "An error occurred. Please try again.";

            // Check if the server provided a more detailed error message
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            // Display the detailed error message
            $("#admin-error").text(errorMessage).fadeIn();

            // Hide the error message after 3 seconds
            setTimeout(function () {
                $("#admin-error").fadeOut();
            }, 3000);
        }
    });
}

function removeAdmin(adminId) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Make AJAX request to remove admin
        $.ajax({
            type: 'POST',
            url: '/remove-admin/' + adminId,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                // Display success message
                $("#admin-message").text(response.message).fadeIn();

                // Hide the success message after 3 seconds
                setTimeout(function () {
                    
                    $("#admin-message").fadeOut();
                }, 3000);

                // Hide the modal
                $("#removeAdminModal" + adminId).modal("hide");
                $("#removeAdminModal" + adminId).modal("hide");
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();


                // Remove the parent <li> element from the list
                $("#removeAdminModal" + adminId).closest("li").remove();
                location.reload()
            },


            error: function (xhr, textStatus, errorThrown) {
                console.error(xhr);

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

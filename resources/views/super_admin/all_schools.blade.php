@extends('layouts.app')

@section('title', "Central School system - Manage Schools")

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
    a {
        text-decoration: none;
        color: #000;
    }

    .dropdown-item:hover {
        background-color: #17a2b8 !important; /* Change the background color of the dropdown item */
        font-weight: 900;
        padding: 0.25rem 1.5rem; /* Adjust padding as needed */
    }

    .dropdown-item:hover a {
        color: #fff; /* Change link text color to white on hover */
    }
    



    
</style>
<style>
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
</style>

@endsection

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "Manage School")

@section('content')
@include('sidebar')

<section class="content">

    <!-- Default box -->
    <div class="card">
        
        <div class="card-header">
            <h3 class="card-title">Schools</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="card-body p-0 table-responsive">
            <div class="message" style="display:none"></div>
            <ul class="users-list clearfix">
                @forelse($schools as $school)
                <li class="col-md-3 col-6">
                    <div class="card admin-card" data-school-id="{{ $school->id }}" data-admin-name="{{ $school->name }}">
                        <div class="card-body">
                        <div class="dropdown" style="position: absolute; top: 10px; left: 10px;">
                            <!-- Dropdown button -->
                            <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="schoolActionsDropdown{{ $school->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <!-- Dropdown menu -->
                            <div class="dropdown-menu" aria-labelledby="schoolActionsDropdown{{ $school->id }}">
                                <div class="dropdown-item">
                                    <a class="edit-school-btn" href="#" data-school-id="{{ $school->id }}">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                </div>
                                <div class="dropdown-divider"></div>

                                @php
                                    // Calculate the creation date of the school
                                    $creationDate = $school->created_at;
                                    $currentDate = now();
                                    $daysSinceCreation = $currentDate->diffInDays($creationDate);
                                @endphp

                                @if ($daysSinceCreation >= 2 && !$school->is_active)
                                    <div class="dropdown-item">
                                        <a class="delete-school-btn" href="#" data-school-id="{{ $school->id }}" data-picture="{{ $school->logo }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                @endif

                                <div class="dropdown-divider"></div>
                                <div class="dropdown-item">
                                    @if (!$school->is_active)
                                        <a class="activate-school-btn" href="#" data-school-id="{{ $school->id }}">
                                            <i class="fas fa-check"></i> Activate
                                        </a>
                                    @else
                                        <a class="" href="{{ route('schools.show', ['id' => $school->id]) }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    
                                    @endif
                                </div>
                            </div>
                        </div>

                            <a class="users-list-name" href="{{ route('schools.show', ['id' => $school->id]) }}" data-admin-name="{{ $school->name }}">
                                <div class="user-profile">
                                    <!-- School logo -->
                                    @if($school->logo)
                                        <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" width="150px">
                                    @else
                                    <i class="fas fa-camera" style="font-size: 150px;"></i>
                                    @endif

                                    <!-- School name -->
                                    <a class="users-list-name" href="{{ route('schools.show', ['id' => $school->id]) }}" data-admin-name="{{ $school->name }}">{{ $school->name }}</a>

                                    @if($school->academicSession)

                                    <a href="#" class="badge badge-sm badge-info">Academic Session{{$school->academicSession->name ?? ''}}</a>
                                    @endif

                                    @if($school->term)
                                    <br><a href="#" class="badge badge-sm badge-info">{{$school->term->name ?? ''}}</a>
                                    @endif

                                   


                                </div>
                                <div class="user-permissions">
                                <style>
                                       #user-details-{{ $school->id }} {
                                            display: none;
                                            margin-top: 10px;
                                            position: absolute;
                                            /* background-color: #f9f9c6; */
                                            border: 1px solid #ccc;
                                            padding: 10px;
                                            z-index: 1000; /* Ensure it appears above other content */
                                            width: 100%; /* Take up full width */
                                            max-width: calc(100% - 20px); /* Set maximum width to leave some padding */
                                        }

                                    </style>
                                    <h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="user-details-{{ $school->id }}">
                                        Details <i class="toggle-icon fas fa-chevron-down"></i>
                                    </h5>
                                    <div id="user-details-{{ $school->id }}" class="collapsed-details">
                                    <h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="user-details-{{ $school->id }}">
                                        Details <i class="toggle-icon fas fa-chevron-down"></i>
                                    </h5>
                                        <div class="detail-item">
                                            <span class="detail-label  small-text"><strong>Location:</strong></span>
                                            <p class="detail-value small-text">{{ $school->address }}, {{ $school->city }}, {{ $school->state }}, {{ $school->country }}</p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label small-text"><strong>Total Teachers:</strong></span><br>
                                            <p class="detail-value  badge bg-purple">{{ $school->teachers()->count() }} / {{$school->schoolPackage->max_teachers}}</p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label  small-text"><strong>Total Students:</strong></span><br>
                                            <p class="detail-value badge bg-purple">{{ $school->students()->count() }} / {{$school->schoolPackage->max_students}}</p>
                                        </div>
                                        
                                        <div class="detail-item">
                                            <span class="detail-label small-text"><strong>Phone Number:</strong></span>
                                            <p class="detail-value small-text">{{ $school->schoolOwner->profile->phone_number ?? '' }}</p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label small-text"><strong>Email:</strong></span>
                                            <p class="detail-value small-text">{{ $school->email }}</p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label small-text"><strong>Package:</strong></span>
                                            <p class="detail-value small-text">{{ $school->schoolPackage->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
                @empty
                <p id="no-school" class="p-2">No schools found.</p>
                @endforelse
            </ul>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->


</section>



@endsection

@section('scripts')




<script>
  $(document).on('click', '.delete-school-btn', function () {
    var schoolId = $(this).data('school-id');
    var picturePath = $(this).data('picture');

    // Ask for confirmation before deleting
    if (confirm('Are you sure you want to delete this school?')) {
        // Make an AJAX request to delete the school
        $.ajax({
            type: 'DELETE',
            url: '/school/' + schoolId,
            data: { picture_path: picturePath }, // Send the picture path to delete from the server
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('School deleted successfully:', response);

                // Display a success message
                var successMessage = $('<div class="alert alert-success" role="alert">School deleted successfully</div>');

                // Show the .message div
                $('.message').show();

                // Append the success message to the .message div
                $('.message').append(successMessage);

                // Replace the deleted table row with the success message
                var deletedRow = $('tr[data-school-id="' + schoolId + '"]');
                deletedRow.replaceWith(successMessage);

                // Automatically fade out the success message after 6 seconds
                successMessage.delay(6000).fadeOut(500, function() {
                    $(this).remove();
                    location.reload();
                });
            },
            error: function (xhr, status, error) {
                console.error(error);

                // Display error message received from the server
                var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Failed to delete school.';
                var errorAlert = $('<div class="alert alert-danger" role="alert">' + errorMessage + '</div>');

                // Show the .message div
                $('.message').show();

                // Append the error message to the .message div
                $('.message').append(errorAlert);

                // Fade out the error message after 3 seconds
                errorAlert.delay(3000).fadeOut(500, function() {
                    $(this).remove();
                });
            }
        });
    }
});

</script>
<script>
    $(document).on('click', '.activate-school-btn', function () {
        var schoolId = $(this).data('school-id');
        var packageId = $(this).data('package-id');
        
        // Perform AJAX activation
        $.ajax({
            type: 'POST',
            url: '/activate-school/' + schoolId,
            data: { package_id: packageId, school_id : schoolId },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status === 'success') {
                    alert('School activated successfully!');
                    location.reload(); // You might want to update the UI to reflect the activation status
                } else if (response.status === 'payment_required') {
                    // Redirect to the payment page with the package price
                    window.location.href = '/payment?package_id=' + response.package_id + '&price=' + response.price + '&school_id=' + response.school_id;
                } else {
                    alert('Failed to activate school. Please try again later.');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                console.log(xhr.responseText);
                // Handle errors as needed
            }
        });
    });
</script>

<script>
    // Update Academic Session
    $('#updateSessionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var schoolName = button.closest('.user-profile').find('.users-list-name').text(); // Get the school name
        var modal = $(this);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        modal.find('#school-name').text(schoolName); // Set the school name in the modal body
        
        modal.find('#confirmUpdateSession').off('click').on('click', function () {
            // Perform the AJAX request to update the academic session
            $.ajax({
                url: '/update-academic-session', // URL to handle the update operation
                method: 'POST', // HTTP method
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    school_id: button.closest('.card').data('school-id'), // Pass the school ID
                    // You can pass additional data if required
                },
                success: function (response) {
                    // Display success message
                    $('.session-message').removeClass('alert-danger').addClass('alert-success p-2').text(response.message).fadeIn();
                    setTimeout(function () {
                        // Reload the page after 3 seconds
                        location.reload();
                    $('#editTermModal').modal('hide');
                    }, 3000);
                },
                error: function (xhr, status, error) {
                    // Display error message
                    $('.session-message').removeClass('alert-success').addClass('alert-danger p-2').text('Error updating term: ' + xhr.responseText).fadeIn();
                }
            });
        });
    });

    $('#updateTermModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var modal = $(this);
        
        modal.find('#confirmUpdateTerm').off('click').on('click', function () {
            // Perform the AJAX request to update the term
            $.ajax({
                url: '/update-term', // URL to handle the update operation
                method: 'POST', // HTTP method
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    school_id: button.closest('.card').data('school-id'), // Pass the school ID
                    // You can pass additional data if required
                },
                success: function (response) {
                    // Display success message
                    $('.term-message').removeClass('alert-danger').addClass('alert-success p-2').text(response.message).fadeIn();
                    setTimeout(function () {
                        // Reload the page after 3 seconds
                        location.reload();
                        $('#updateTermModal').modal('hide');
                    }, 3000);
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText)
                    // Display error message
                    $('.term-message').removeClass('alert-success').addClass('alert-danger p-2').text('Error updating term: ' + xhr.responseText).fadeIn();
                }
            });
        });
    });

</script>




@endsection




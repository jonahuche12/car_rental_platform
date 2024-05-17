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
@php
    // Retrieve the authenticated user
    $user = auth()->user();

    // Get the count of schools owned by the user
    $ownedSchoolsCount = $user->ownedSchools()->count(); // Assuming 'ownedSchools' is the relationship method

    // Define the maximum number of schools allowed to create
    $maxSchoolsAllowed = 3;
@endphp

@if ($ownedSchoolsCount < $maxSchoolsAllowed)
    <div class="">
        <a class="btn btn-primary" href="{{ route('create_school') }}">Create New School</a>
    </div>
@endif

<section class="content">

    <!-- Default box -->
    @include('partials.wards')
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
                                    @if (!$school->is_active)
                                    <div class="dropdown-divider"></div>
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
                                        @php
                                            $isLatestAcademicSession = $school->academicSession->id === $latest_academic_session->id;
                                            $academicSessionClass = $isLatestAcademicSession ? 'success' : 'info';
                                        @endphp
                                        <a href="#" class="badge badge-sm bg-{{ $academicSessionClass }}">Academic Session{{ $school->academicSession->name ?? '' }}</a>
                                    @endif
                                    @if($school->term)
                                        @php
                                            $isLatestTerm = $school->term->id === $latest_term->id;
                                            $termClass = $isLatestTerm ? 'success' : 'info';
                                        @endphp
                                        <br><a href="#" class="badge badge-sm bg-{{ $termClass }}">{{ $school->term->name ?? '' }}</a>
                                    @endif

                                    @if(!$school->academicSession || $school->academicSession->id !== $latest_academic_session->id)
                                        <a class="badge badge-sm badge-warning" style="cursor:pointer;" data-toggle="modal" data-target="#updateSessionModal">Update Academic Session</a><br><br>
                                    @endif

                                    <!-- Update Term badge -->
                                    @if($school->academicSession && $school->academicSession->id === $latest_academic_session->id && (!$school->term || $school->term->id !== $latest_term->id))
                                        <br><a class="badge badge-sm badge-warning" style="cursor:pointer;" data-toggle="modal" data-target="#updateTermModal">Update Term</a>
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




<!-- Add this code within your Blade template, inside the edit modal -->
<div class="modal fade" id="editschoolModal" tabindex="-1" role="dialog" aria-labelledby="editschoolModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editschoolModalLabel">Edit <span id="school_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add a form for editing a school -->
                <form id="editschoolForm" data-school-id="" enctype="multipart/form-data">
                    <!-- Name field -->
                    <div class="form-group">
                        <label for="edit_name">School Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_picture">Logo/Badge</label>
                        <input type="file" class="form-control-file" id="logo" name="logo" accept="image/*">
                        <div id="logoPreview" class="mt-2"></div>
                    </div>

                    <!-- Description field -->
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <!-- Mission field -->
                    <div class="form-group">
                        <label for="edit_mission">Mission</label>
                        <textarea class="form-control" id="edit_mission" name="mission" rows="3"></textarea>
                    </div>

                    <!-- Vision field -->
                    <div class="form-group">
                        <label for="edit_vision">Vision</label>
                        <textarea class="form-control" id="edit_vision" name="vision" rows="3"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="country">Choose Country:</label>
                        <select class="form-control" name="country" id="country_input" onchange="populateStates()">
                            <option value=""></option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Ghana">Ghana</option>
                            <option value="South Africa">South Africa</option>
                            <option value="Kenya">Kenya</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="state">Select State:</label>
                        <select class="form-control" name="state" id="state_input" onchange="populateCities()">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="city">City:</label>
                        <select class="form-control" name="city" id="city_input">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="city">Address:</label>
                        <input type="text" class="form-control" name="address" id='addressInput' >
                        
                    </div>

                    <!-- Email field -->
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>

                    <!-- Phone Number field -->
                    <div class="form-group">
                        <label for="edit_phone_number">Phone Number</label>
                        <input type="tel" class="form-control" id="edit_phone_number" name="phone_number">
                    </div>

                    <!-- Website field -->
                    <div class="form-group">
                        <label for="edit_website">Website</label>
                        <input type="text" class="form-control" id="edit_website" name="website">
                    </div>

                    <!-- Facebook field -->
                    <div class="form-group">
                        <label for="edit_facebook">Facebook</label>
                        <input type="text" class="form-control" id="edit_facebook" name="facebook">
                    </div>

                    <!-- Instagram field -->
                    <div class="form-group">
                        <label for="edit_instagram">Instagram</label>
                        <input type="text" class="form-control" id="edit_instagram" name="instagram">
                    </div>

                    <!-- Twitter field -->
                    <div class="form-group">
                        <label for="edit_twitter">Twitter</label>
                        <input type="text" class="form-control" id="edit_twitter" name="twitter">
                    </div>

                    <!-- LinkedIn field -->
                    <div class="form-group">
                        <label for="edit_linkedin">LinkedIn</label>
                        <input type="text" class="form-control" id="edit_linkedin" name="linkedin">
                    </div>


                    <button type="submit" class="btn btn-primary mt-3">Update school</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Update Academic Session Modal -->
<div class="modal fade" id="updateSessionModal" tabindex="-1" role="dialog" aria-labelledby="updateSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateSessionModalLabel">Update Academic Session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="session-message" style="display:none"></div>
                Are you sure you want to update the academic session for <b class="badge badge-info"><span id="school-name"></span></b> ? All school records related to the previous academic session will be archived, and you won't be able to edit them.

                <div class="badge badge-secondary">latest session: {{$latest_academic_session->name}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmUpdateSession">Update Academic Session</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Term Modal -->
<div class="modal fade" id="updateTermModal" tabindex="-1" role="dialog" aria-labelledby="updateTermModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTermModalLabel">Update Term</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="term-message" style="display:none"></div>
                Are you sure you want to update the term? All school records related to the previous term will be archived, and you won't be able to edit them.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmUpdateTerm">Update Term</button>
            </div>
        </div>
    </div>
</div>



</section>



@endsection

@section('scripts')


<script>
    $(document).ready(function () {
        // Handle file input change event
        $('#logo').change(function () {
            // Get the selected file
            var file = this.files[0];

            // Check if a file is selected
            if (file) {
                // Create a FileReader to read the file content
                var reader = new FileReader();

                // Define a callback function to execute when the file is loaded
                reader.onload = function (e) {
                    // Display the image preview
                    $('#logoPreview').html('<img src="' + e.target.result + '" class="img-fluid" alt="Logo Preview">');
                };

                // Read the file as a data URL (base64 encoded)
                reader.readAsDataURL(file);
            } else {
                // If no file is selected, clear the image preview
                $('#logoPreview').html('');
            }
        });

    });
</script>


<script>
    $(document).ready(function () {
        var schools = @json($schools);

        $(document).on('click', '.edit-school-btn', function () {
            var schoolId = $(this).data('school-id');
            $('#editschoolForm').data('school-id', schoolId);
            var school = schools.find(function (school) {
                return school.id == schoolId;
            });
            $('#school_name').text(school.name);

            // Populate the form fields with school data
            $('#editschoolForm input[name="name"]').val(school.name);
            $('#editschoolForm textarea[name="description"]').val(school.description);
            $('#editschoolForm textarea[name="mission"]').val(school.mission);
            $('#editschoolForm textarea[name="vision"]').val(school.vision);
            $('#editschoolForm input[name="address"]').val(school.address);
            $('#editschoolForm input[name="email"]').val(school.email);
            $('#editschoolForm input[name="phone_number"]').val(school.phone_number);
            $('#editschoolForm input[name="website"]').val(school.website);
            $('#editschoolForm input[name="facebook"]').val(school.facebook);
            $('#editschoolForm input[name="instagram"]').val(school.instagram);
            $('#editschoolForm input[name="twitter"]').val(school.twitter);
            $('#editschoolForm input[name="linkedin"]').val(school.linkedin);
            $('#editschoolForm input[name="total_students"]').val(school.total_students);
            $('#editschoolForm input[name="total_teachers"]').val(school.total_teachers);
            $('#editschoolForm input[name="total_staff"]').val(school.total_staff);
            $('#editschoolForm input[name="is_active"]').prop('checked', school.is_active);
            $('#editschoolForm select[name="school_package_id"]').val(school.school_package_id);

            // Populate country, state, and city dropdowns
            $('#editschoolForm select[name="country"]').val(school.country);
            $('#editschoolForm select[name="state"]').val(school.state);
            $('#editschoolForm select[name="city"]').val(school.city);
            $('#editschoolForm input[name="address"]').val(school.address);
            
            var logoPreview = $('#logoPreview');

            logoPreview.empty(); // Clear previous previews
            // Display the logo preview if there is a saved logo
            if (school.logo) {
                console.log(school.logo)

                var img = $('<img>').attr('src', '/storage/' + school.logo).attr('alt', 'Logo Preview').addClass('img-thumbnail');
                logoPreview.append(img);
            }

            // Show the modal
            $('#editschoolModal').modal('show');
        });


        // Submit form using AJAX for the edit form
        $('#editschoolForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var schoolId = $(this).data('school-id');
            // console.log('Clicked element:', this);
            console.log('School ID:', schoolId);
            var url = '/school/' + schoolId + '/edit';
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    console.log('school updated successfully:', response);

                    // Update the UI with the new school values
                    location.reload();

                    $('#editschoolModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

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




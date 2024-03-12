@extends('layouts.app')

@section('title', "Central School system - Manage Schools")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "Manage School")

@section('content')
@include('sidebar')
<div class="">
            <a class="btn btn-primary"href="{{ route('create_school')}}">Create New school</a>
        </div>
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
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            #
                        </th>
                        <th style="width: 8%">
                            School Name
                        </th>
                        <th style="width: 6%" class="text-center">
                            Logo
                        </th>
                        <!-- <th style="width: 12%" class="text-center">
                            Mission
                        </th> -->
                        <!-- <th style="width: 12%" class="text-center">
                            Vision
                        </th> -->
                        <th style="width: 6%" class="text-center">
                            Location
                        </th>
                        <th style="width: 6%" class="text-center">
                            Total Teachers
                        </th>
                        <th style="width: 6%" class="text-center">
                            Total Students
                        </th>
                        <th style="width: 6%" class="text-center">
                         Staff
                        </th>
                        
                        <!-- <th style="width: 15%" class="text-center">
                            Description
                        </th> -->
                        <th style="width: 8%">
                            Phone Number
                        </th>
                        <th style="width: 6%" class="text-center">
                            Email
                        </th>
                        <th style="width: 6%" class="text-center">
                            Package
                        </th>
                        <th style="width: 20%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schools as $school)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <a>
                                {{ $school->name }}
                            </a>
                            <br />
                            <small>
                                Created {{ $school->created_at->format('d.m.Y') }}
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    {{-- Add logic to display school image --}}
                                    <img alt="Avatar" class="table-avatar" src="{{ asset('storage/' . $school->logo) }}">
                                </li>
                            </ul>
                        </td>


                        <!-- <td>
                            {{ $school->mission }}
                        </td> -->
                        <!-- <td>
                            {{ $school->vision }}
                        </td> -->
                        <td>
                            {{ $school->address .", ". $school->city  .", ". $school->state  .", ". $school->country ."."}}
                        </td>
                        <td>
                            {{ $school->total_teachers }}
                        </td>
                        <td>
                            {{ $school->total_students }}
                        </td>
                        <td>
                            {{ $school->total_staff }}
                        </td>
                        
                        <!-- <td>
                            {{ $school->description }}
                        </td> -->
                        <td>
                            {{ $school->phone_number }}
                        </td>
                        <td>
                            {{ $school->email }}
                        </td>

                        <td class="project-state">
                            <span class="badge badge-success">{{ $school->schoolPackage->name }}</span>
                        </td>
                        <td class="project-actions text-right">
                            <div class="btn-group" role="group" aria-label="School Actions">
                                <a class="btn btn-info btn-sm edit-school-btn" href="#" data-school-id="{{ $school->id }}">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                <a class="btn btn-danger btn-sm delete-school-btn" href="#" data-school-id="{{ $school->id }}" data-picture="{{ $school->logo }}">
                                    <i class="fas fa-trash"></i> Delete
                                </a>

                                @if (!$school->is_active)
                                    <a class="btn btn-success btn-sm activate-school-btn" href="#" data-school-id="{{ $school->id }}">
                                        <i class="fas fa-check"></i> Activate
                                    </a>
                                @else
                                    <a class="btn btn-warning btn-sm" href="{{ route('schools.show', ['id' => $school->id]) }}">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">
                            No school  available.
                            <div class="-left">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#createschoolModal">Create New school</button>
                            </div>
                        
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
        var picturePath = $(this).data('logo');

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
                    console.log('school deleted successfully:', response);

                    // Display a success message
                    var successMessage = $('<div class="alert alert-success" role="alert">school deleted successfully</div>');

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
                        location.reload()
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
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





@endsection




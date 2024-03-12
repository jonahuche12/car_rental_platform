@extends('layouts.app')

@section('title', "Central School system - Create Packages")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "School Package")

@section('content')
@include('sidebar')
<div class="">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createPackageModal">Create New Package</button>
        </div>
<section class="content">

    <!-- Default box -->
    <div class="card">
        
        <div class="card-header">
            <h3 class="card-title">School Packages</h3>

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
                        <th style="width: 10%">
                            Package Name
                        </th>
                        <th style="width: 6%" class="text-center">
                            Picture
                        </th>
                        <th style="width: 6%" class="text-center">
                            Duration
                        </th>
                        <th style="width: 6%" class="text-center">
                            Max Lessons/Day
                        </th>
                        <th style="width: 6%" class="text-center">
                            Max Uploads
                        </th>
                        
                        <th style="width: 15%" class="text-center">
                            Description
                        </th>
                        <th style="width: 8%">
                            Progress
                        </th>
                        <th style="width: 6%" class="text-center">
                            Price
                        </th>
                        <th style="width: 20%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <a>
                                {{ $package->name }}
                            </a>
                            <br />
                            <small>
                                Created {{ $package->created_at->format('d.m.Y') }}
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    {{-- Add logic to display package image --}}
                                    <img alt="Avatar" class="table-avatar" src="{{ asset('storage/' . $package->picture) }}">
                                </li>
                            </ul>
                        </td>


                        <td>
                            {{ $package->duration_in_days }} days
                        </td>
                        <td>
                            {{ $package->max_lessons_per_day }}
                        </td>
                        <td>
                            {{ $package->max_uploads }}
                        </td>
                        
                        <td>
                            {{ $package->description }}
                        </td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $package->getUserUsagePercentage() }}"
                                    aria-valuemin="0" aria-valuemax="100" style="width: {{ $package->getUserUsagePercentage() }}%">
                                </div>
                            </div>
                            <small>
                                {{ $package->getUserUsagePercentage() }}% Complete
                            </small>
                        </td>

                        <td class="project-state">
                            <span class="badge badge-success">{{ $package->price }}</span>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-info btn-sm edit-package-btn" href="#" data-package-id="{{ $package->id }}">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                            <a class="btn btn-danger btn-sm delete-package-btn" href="#" data-package-id="{{ $package->id }}" data-picture="{{ $package->picture }}">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">
                            No school packages available.
                            <div class="-left">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#createPackageModal">Create New Package</button>
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



    <div class="modal fade" id="createPackageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New User Package</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add a form for creating a new package -->
                    <form id="createPackageForm" enctype="multipart/form-data">
                        <!-- Name field -->
                        <div class="form-group">
                            <label for="name">Package Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Description field -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <!-- Picture field -->
                        <div class="form-group">
                            <label for="picture">Picture</label>
                            <input type="file" class="form-control-file" id="picture" name="picture" accept="image/*">
                            <div id="picturePreview" class="mt-2"></div>
                        </div>
                        <!-- Price field -->
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>

                        <!-- Duration in days field -->
                        <div class="form-group">
                            <label for="duration_in_days">Duration (in days)</label>
                            <input type="number" class="form-control" id="duration_in_days" name="duration_in_days" required>
                        </div>

                        <!-- Max Students field -->
                        <div class="form-group">
                            <label for="max_students">Max Lessons Per Day</label>
                            <input type="number" class="form-control" id="max_lessons_per_day" name="max_lessons_per_day" required>
                        </div>

                        <!-- Max Admins field -->
                        <div class="form-group">
                            <label for="max_admins">Max Uploads</label>
                            <input type="number" class="form-control" id="max_uploads" name="max_uploads" required>
                        </div>



                        <button type="submit" class="btn btn-primary mt-3">Create Package</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Add this code within your Blade template, inside the edit modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1" role="dialog" aria-labelledby="editPackageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add a form for editing a package -->
                <form id="editPackageForm" data-package-id="" enctype="multipart/form-data">
                    <!-- Name field -->
                    <div class="form-group">
                        <label for="edit_name">Package Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <!-- Description field -->
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <!-- Picture field -->
                    <div class="form-group">
                        <label for="edit_picture">Picture</label>
                        <input type="file" class="form-control-file" id="edit_picture" name="picture" accept="image/*">
                        <div id="edit_picturePreview" class="mt-2"></div>
                    </div>

                    <!-- Price field -->
                    <div class="form-group">
                        <label for="edit_price">Price</label>
                        <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                    </div>

                    <!-- Duration in days field -->
                    <div class="form-group">
                        <label for="edit_duration_in_days">Duration (in days)</label>
                        <input type="number" class="form-control" id="edit_duration_in_days" name="duration_in_days" required>
                    </div>

                    <!-- Max Students field -->
                    <div class="form-group">
                        <label for="edit_max_students">Max Lessons Per day</label>
                        <input type="number" class="form-control" id="edit_max_lessons_per_day" name="max_lessons_per_day" required>
                    </div>

                    <!-- Max Admins field -->
                    <div class="form-group">
                        <label for="edit_max_admins">Max Uploads</label>
                        <input type="number" class="form-control" id="edit_max_uploads" name="max_uploads" required>
                    </div>


                    <button type="submit" class="btn btn-primary mt-3">Update Package</button>
                </form>
            </div>
        </div>
    </div>
</div>



</section>



@endsection

@section('scripts')
<!-- ... Your existing form ... -->

<!-- Picture preview container -->


<!-- ... Your existing form ... -->

<script>
    $(document).ready(function () {
        // Handle file input change event
        $('#picture').change(function () {
            // Get the selected file
            var file = this.files[0];

            // Check if a file is selected
            if (file) {
                // Create a FileReader to read the file content
                var reader = new FileReader();

                // Define a callback function to execute when the file is loaded
                reader.onload = function (e) {
                    // Display the image preview
                    $('#picturePreview').html('<img src="' + e.target.result + '" class="img-fluid" alt="Preview">');
                };

                // Read the file as a data URL (base64 encoded)
                reader.readAsDataURL(file);
            } else {
                // If no file is selected, clear the image preview
                $('#picturePreview').html('');
            }
        });

    });
</script>


<script>
    $(document).ready(function () {
        var packages = @json($packages);

        // Handle file input change event
        $('#picture').change(function () {
            var file = this.files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#picturePreview').html('<img src="' + e.target.result + '" class="img-fluid" alt="Preview">');
                };

                reader.readAsDataURL(file);
            } else {
                $('#picturePreview').html('');
            }
        });

        // Handle form submission using AJAX with FormData
        $('#createPackageForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/user-packages',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    $('#createPackageModal').modal('hide');

                    // Assuming your response is a JSON object, not a string
                    var newRow = '<tr>' +
                        '<td>#</td>' +
                        '<td><a>' + response.name + '</a><br/><small>Created ' + response.created_at + '</small></td>' +
                        '<td><ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="' + response.picture_url + '"></li></ul></td>' +
                        '<td>' + response.duration_in_days + ' days</td>' +
                        '<td>' + response.max_lessons_per_day + '</td>' +
                        '<td>' + response.max_uploads + '</td>' +
                        '<td>' + response.description + '</td>' +
                        '<td class="project_progress"><div class="progress progress-sm"><div class="progress-bar bg-green" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div><small>0% Complete</small></td>' +
                        '<td class="project-state"><span class="badge badge-success">Success</span></td>' +
                        '<td class="project-actions text-right">' +
                        '<a class="btn btn-primary btn-sm" href="#"><i class="fas fa-folder"></i> View</a>' +
                        '<a class="btn btn-info btn-sm edit-package-btn" data-package-id="' + response.id + '" href="#"><i class="fas fa-pencil-alt"></i> Edit</a>' +
                        '<a class="btn btn-danger btn-sm" href="#"><i class="fas fa-trash"></i> Delete</a>' +
                        '</td>' +
                        '</tr>';

                    $('.table tbody').append(newRow);

                    // Add success message to the .message div
                    $('.message').removeClass('alert-danger').addClass('alert-success').html('Package created successfully.').show();

                    // Reload the page after 3 seconds
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

        // Handle click event on the "Edit" button
        $(document).on('click', '.edit-package-btn', function () {
            var packageId = $(this).data('package-id');
            var package = packages.find(function (pkg) {
                return pkg.id == packageId;
            });

            $('#editPackageForm input[name="name"]').val(package.name);
            $('#editPackageForm textarea[name="description"]').val(package.description);
            $('#editPackageForm input[name="price"]').val(package.price);
            $('#editPackageForm input[name="duration_in_days"]').val(package.duration_in_days);
            $('#editPackageForm input[name="max_lessons_per_day"]').val(package.max_lessons_per_day);
            $('#editPackageForm input[name="max_uploads"]').val(package.max_uploads);
            console.log(package.max_lessons_per_day)
           

            $('#editPackageForm').attr('data-package-id', packageId);
            $('#editPackageModal').modal('show');
        });

        // Submit form using AJAX for the edit form
        $('#editPackageForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var packageId = $(this).data('package-id');
            var url = '/user_packages/' + packageId + '/edit';
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
                    console.log('Package updated successfully:', response);

                    // Update the UI with the new package values
                    location.reload();

                    $('#editPackageModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

        $(document).on('click', '.delete-package-btn', function () {
        var packageId = $(this).data('package-id');
        var picturePath = $(this).data('picture');

        // Ask for confirmation before deleting
        if (confirm('Are you sure you want to delete this package?')) {
            // Make an AJAX request to delete the package
            $.ajax({
                type: 'DELETE',
                url: '/user_packages/' + packageId,
                data: { picture_path: picturePath }, // Send the picture path to delete from the server
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log('Package deleted successfully:', response);

                    // Display a success message
                    var successMessage = $('<div class="alert alert-success" role="alert">Package deleted successfully</div>');

                    // Show the .message div
                    $('.message').show();

                    // Append the success message to the .message div
                    $('.message').append(successMessage);

                    // Replace the deleted table row with the success message
                    var deletedRow = $('tr[data-package-id="' + packageId + '"]');
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


<!-- Add this script within your Blade template or in a separate JavaScript file -->
<script>
    $(document).ready(function () {
        // Handle file input change event for picture preview
        $('#edit_picture').change(function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#edit_picturePreview').html('<img src="' + e.target.result + '" class="img-fluid" alt="Preview">');
                };
                reader.readAsDataURL(file);
            } else {
                $('#edit_picturePreview').html('');
            }
        });
    });

        
</script>



@endsection




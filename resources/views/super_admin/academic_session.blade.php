@extends('layouts.app')

@section('title', "Central School system - Create Academic Sessions")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "School Academic Session")

@section('sidebar')
    @include('sidebar')
@endsection
@section('content')

<div class="">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createAcademicSessionModal">Create New Academic Session</button>
        </div>
<section class="content">

    <!-- Default box -->
    <div class="card">
        
        <div class="card-header">
            <h3 class="card-title">School academic_sessions</h3>

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
                        <th>
                            Academic Session Name
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($academic_sessions as $academic_session)
    <tr>
        <td class="text-center">
            {{ $loop->iteration }}
        </td>
        <td>
            <a>{{ $academic_session->name }}</a><br>
            <small>Created {{ $academic_session->created_at->format('d.m.Y') }}</small>
        </td>
        <td class="project-actions">
            <div class="btn-group" role="group" aria-label="Actions">
                <!-- Edit Academic Session Button -->
                <a class="btn btn-info btn-sm edit-academic_session-btn" href="#" data-academic_session-id="{{ $academic_session->id }}">
                    <i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline"> Edit</span>
                </a>
                <!-- Delete Academic Session Button -->
                <a class="btn btn-danger btn-sm delete-academic_session-btn" href="#" data-academic_session-id="{{ $academic_session->id }}" data-picture="{{ $academic_session->picture }}">
                    <i class="fas fa-trash"></i><span class="d-none d-sm-inline"> Delete</span>
                </a>
                <!-- Add Term Button -->
                <a class="btn btn-primary btn-sm add-term-btn" href="#" data-academic_session-id="{{ $academic_session->id }}">
                    <i class="fas fa-plus"></i><span class="d-none d-sm-inline"> Create Term</span>
                </a>
            </div>
        </td>
    </tr>

    <!-- Nested Table for Terms -->
    <tr class="terms-row">
        <td colspan="3">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Term Name</th>
                        <th>Actions</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($academic_session->terms as $term)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $term->name }}</td>
                            <td>
                                <!-- Edit Term Button -->
                                <a class="btn btn-info btn-sm edit-term-btn" href="#" data-term-id="{{ $term->id }}">
                                    <i class="fas fa-pencil-alt"></i><span class="d-none d-sm-inline"> Edit</span>
                                </a>
                                <!-- Delete Term Button -->
                                <a class="btn btn-danger btn-sm delete-term-btn" href="#" data-term-id="{{ $term->id }}">
                                    <i class="fas fa-trash"></i><span class="d-none d-sm-inline"> Delete</span>
                                </a>
                            </td>
                            <!-- Add more columns as needed -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11" class="text-center">
            No Academic Sessions available.
            <div class="-left">
                <button class="btn btn-primary" data-toggle="modal" data-target="#createAcademicSessionModal">Create New Academic Session</button>
            </div>
        </td>
    </tr>
@endforelse

                </tbody>
            </table>
        </div>
        <!-- /.card-body -->

    <!-- /.card -->



    <div class="modal fade" id="createAcademicSessionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New Academic Session</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add a form for creating a new academic_session -->
                    <form id="createAcademicSessionForm" enctype="multipart/form-data">
                        <!-- Name field -->
                        <div class="form-group">
                            <label for="name">Academic Session Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>


                        <button type="submit" class="btn btn-primary mt-3">Create Academic Session</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Add this code within your Blade template, inside the edit modal -->
<div class="modal fade" id="editAcademicSessionModal" tabindex="-1" role="dialog" aria-labelledby="editAcademicSessionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAcademicSessionModalLabel">Edit Academic Session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add a form for editing a academic_session -->
                <form id="editAcademicSessionForm" data-academic_session-id="" enctype="multipart/form-data">
                    <!-- Name field -->
                    <div class="form-group">
                        <label for="edit_name">Academic Session Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    

                    <button type="submit" class="btn btn-primary mt-3">Update Academic Session</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade mt-5" id="addTermModal" tabindex="-1" role="dialog" aria-labelledby="addTermModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTermModalLabel">Add Term for  <b><span id="academic_session-name"></span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add a form for editing a academic_session -->
                <form id="addTermForm" data-academic_session-id="" enctype="multipart/form-data">
                    <!-- Name field -->
                    <div class="form-group">
                        <label for="edit_name">Term name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <input type="hidden" class="form-control" name="academic_session" >
                    </div>

                    

                    <button type="submit" class="btn btn-primary mt-3">Add Term</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add this code within your Blade template, inside the edit modal -->
<div class="modal fade" id="editTermModal" tabindex="-1" role="dialog" aria-labelledby="editTermModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTermModalLabel">Edit <b><span id='edit-term-name'></span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add a form for editing a academic_session -->
                <form id="editTermForm" data-term-id="" enctype="multipart/form-data">
                    <!-- Name field -->
                    <div class="form-group">
                        <label for="edit_name">Term</label>
                        <input type="text" class="form-control" id="edit_name" name="name" value="" required>
                    </div>

                    <!-- Message container -->
                    <div class="term-message p-4" style="display: none;"></div>

                    <button type="submit" class="btn btn-primary mt-3">Update Term</button>
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





<script>
    $(document).ready(function () {
        var academic_sessions = @json($academic_sessions);

        

        // Handle form submission using AJAX with FormData
        $('#createAcademicSessionForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/academic_sessions',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    $('#createAcademicSessionModal').modal('hide');


                    // Add success message to the .message div
                    $('.message').removeClass('alert-danger').addClass('alert-success p-2').html('Academic Session created successfully.').show();

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
        $(document).on('click', '.edit-academic_session-btn', function () {
            var academic_sessionId = $(this).data('academic_session-id');
            var academic_session = academic_sessions.find(function (pkg) {
                return pkg.id == academic_sessionId;
            });

            $('#editAcademicSessionForm input[name="name"]').val(academic_session.name);
            

            $('#editAcademicSessionForm').attr('data-academic_session-id', academic_sessionId);
            $('#editAcademicSessionModal').modal('show');
        });

        // Submit form using AJAX for the edit form
        $('#editAcademicSessionForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var academic_sessionId = $(this).data('academic_session-id');
            var url = '/academic_sessions/' + academic_sessionId + '/edit';
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
                    console.log('academic_session updated successfully:', response);

                    // Update the UI with the new academic_session values
                    location.reload();

                    $('#editAcademicSessionModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

        // Handle click event on the "Edit" button
        $(document).on('click', '.add-term-btn', function () {
            var academic_sessionId = $(this).data('academic_session-id');
            var academic_session = academic_sessions.find(function (pkg) {
                return pkg.id == academic_sessionId;
            });
            $('#addTermForm input[name="academic_session"]').val(academic_session.id);

            $('#academic_session-name').text(academic_session.name);
            

            $('#addTermForm').attr('data-academic_session-id', academic_sessionId);
            $('#addTermModal').modal('show');
        });

        // Submit form using AJAX for the edit form
        $('#addTermForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var academic_sessionId = $(this).data('academic_session-id');
            var url = '/add_term/' + academic_sessionId;
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
                    console.log('academic_session updated successfully:', response);

                    // Update the UI with the new academic_session values
                    location.reload();

                    $('#addTermModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

        $(document).on('click', '.edit-term-btn', function () {
            // Retrieve the term ID from the data attribute
            var termId = $(this).data('term-id');

            // Make an AJAX request to fetch the term details based on the term ID
            $.ajax({
                type: 'GET',
                url: '/terms/' + termId, // Adjust the URL endpoint as per your route configuration
                dataType: 'json',
                success: function (response) {
                    // Populate the edit term modal with the retrieved term details
                    $('#edit-term-name').text(response.term.name); // Display term name in modal title
                    $('#editTermForm input[name="name"]').val(response.term.name);
                    $('#editTermForm').attr('data-term-id', termId); // Set the term ID in form data attribute

                    // Show the edit term modal
                    $('#editTermModal').modal('show');
                },
                error: function (xhr, status, error) {
                    // Handle errors appropriately
                    console.error(error);
                }
            });
        });
        // Submit form using AJAX for the edit form
        $('#editTermForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var termId = $(this).data('term-id');
            var url = '/edit_term/' + termId;
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
                    // Display success message
                    $('.term-message').removeClass('alert-danger').addClass('alert-success').text('Term updated successfully').fadeIn();
                    setTimeout(function () {
                        // Reload the page after 3 seconds
                        location.reload();
                    $('#editTermModal').modal('hide');
                    }, 3000);
                },
                error: function (xhr, status, error) {
                    // Display error message
                    $('.message').removeClass('alert-success').addClass('alert-danger').text('Error updating term: ' + xhr.responseText).fadeIn();
                }
            });

            // Hide messages after 3 seconds
            setTimeout(function () {
                $('.message').fadeOut();
            }, 3000);
        });


        $(document).on('click', '.delete-term-btn', function () {
            var termId = $(this).data('term-id');
            

            // Ask for confirmation before deleting
            if (confirm('Are you sure you want to delete this term?')) {
                // Make an AJAX request to delete the academic_session
                $.ajax({
                    type: 'DELETE',
                    url: '/delete-term/' + termId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log('Term deleted successfully:', response);

                        // Display a success message
                        var successMessage = $('<div class="alert alert-success" role="alert">Term deleted successfully</div>');

                        // Show the .message div
                        $('.message').show();

                        // Append the success message to the .message div
                        $('.message').append(successMessage);

                        // Replace the deleted table row with the success message
                        var deletedRow = $('tr[data-term-id="' + termId + '"]');
                        deletedRow.replaceWith(successMessage);

                        // Automatically fade out the success message after 6 seconds
                        successMessage.delay(3000).fadeOut(500, function() {
                            
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


        })

        $(document).on('click', '.delete-academic_session-btn', function () {
            var academic_sessionId = $(this).data('academic_session-id');
            var picturePath = $(this).data('picture');

            // Ask for confirmation before deleting
            if (confirm('Are you sure you want to delete this academic_session?')) {
                // Make an AJAX request to delete the academic_session
                $.ajax({
                    type: 'DELETE',
                    url: '/academic_sessions/' + academic_sessionId,
                    data: { picture_path: picturePath }, // Send the picture path to delete from the server
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log('academic_session deleted successfully:', response);

                        // Display a success message
                        var successMessage = $('<div class="alert alert-success" role="alert">academic_session deleted successfully</div>');

                        // Show the .message div
                        $('.message').show();

                        // Append the success message to the .message div
                        $('.message').append(successMessage);

                        // Replace the deleted table row with the success message
                        var deletedRow = $('tr[data-academic_session-id="' + academic_sessionId + '"]');
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




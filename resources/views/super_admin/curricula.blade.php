@extends('layouts.app')

@section('title', "Central School system - Create Packages")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "School Package")

@section('content')
@include('sidebar')
<div class="">
            <button class="btn btn-primary" data-toggle="modal" data-target="#creatCurriculumModal">Create New Curriculum</button>
        </div>
<section class="content">

    <!-- Default box -->
    <div class="card">
        
        <div class="card-header">
            <h3 class="card-title">Curricula</h3>

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
                        <th style="width: 1%">#</th>
                        <th style="width: 10%">Class Level</th>
                        <th style="width: 20%" class="text-center">Subject</th>
                        <th style="width: 20%" class="text-center">Theme</th>
                        <th style="width: 29%" class="text-center">Description</th>
                        <th style="width: 20%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($curricula as $curriculum)
                    
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $curriculum->class_level }}</td>
                        <td class="text-center">{{ $curriculum->subject }}</td>
                        <td class="text-center">{{ $curriculum->theme }}</td>
                        <td class="text-center">{{ $curriculum->description }}</td>
                        <td class="project-actions text-center">
                            <div class="btn-group">
                                <a class="btn btn-secondary btn-sm add-curriculum_topic-btn" href="#" 
                                data-curriculum-id="{{ $curriculum->id }}" 
                                data-curriculum-theme="{{ $curriculum->theme }}" 
                                data-toggle="modal">
                                    <i class="fas fa-plus"></i><span class="d-none d-sm-inline"> Add Topic</span>
                                </a>

                                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapse{{ $curriculum->id }}" aria-expanded="false" aria-controls="collapse{{ $curriculum->id }}">
                                    <i class="fa fa-chevron-down"></i> <span class="d-none d-sm-inline">Topics ({{ $curriculum->topics()->count() }})</span>
                                </button>
                                <a class="btn btn-secondary btn-sm edit-curriculum-btn" href="#" data-curriculum-id="{{ $curriculum->id }}" data-toggle="modal">
                                    <i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline">Edit</span>
                                </a>
                                <a class="btn btn-danger btn-sm delete-curriculum-btn" href="#" data-curriculum-id="{{ $curriculum->id }}">
                                    <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr class="collapse" id="collapse{{ $curriculum->id }}">
                        <td colspan="5">
                            <table class="table table-striped mt-2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Topic</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($curriculum->topics as $topic)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $topic->pivot->topic }} </td>
                                        <td>{{ $topic->pivot->description }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <!-- <a class="btn btn-info btn-sm view-section-details-btn" href="#">
                                                    <i class="fas fa-eye"></i> View
                                                </a> -->
                                                <a class="btn btn-secondary btn-sm edit-curriculum-z" href="#" data-topic-id="{{ $topic->pivot->id }}" data-curriculum-id="{{ $curriculum->id }}" data-toggle="modal" data-target="#editCurriculumTopicModal">
                                                    <i class="fas fa-pencil-alt"></i> <span class="d-none d-sm-inline">Edit {{ $curriculum->id }} {{ $topic->pivot->id }}</span>
                                                </a>

                                                </a>
                                                <a class="btn btn-danger btn-sm delete-curriculum_topic-btn" href="#" data-topic-id="{{ $topic->pivot->id }}">
                                                    <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                            </a>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No Topics available for this Curriculum.</td>
                                    </tr>
                                    
                                    @endforelse
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No Class available for this School.
                            <div class="-left">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#creatCurriculumModal">Create New Curriculum</button>
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



            <div class="modal fade" id="creatCurriculumModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create New Curriculum</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Add a form for creating a new package -->
                            <form id="createCurriculumForm" method="POST" action="#">
                                @csrf

                            <div class="form-group mb-3">
                                <label for="country">Choose Country:</label>
                                <select class="form-control" name="country" id="country_input">
                                    <option value="">Choose Country</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Kenya">Kenya</option>
                                </select>
                            </div>

                            <div class="form-group">
                                    <label for="class_level">Subject:</label>
                                    <select name="subject" class="form-control" id="class_level">
                                        <option value="">Select Subject</option>
                                        <option value="English Language">English Language</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="Chemistry">Chemistry</option>
                                        <option value="Biology">Biology</option>
                                        <option value="Physics">Physics</option>
                                        <option value="Economics">Economics</option>
                                        <option value="Government">Government</option>
                                        <option value="Litreture">Litreture</option>
                                        <option value="Accounting">Accounting</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="theme">Theme:</label>
                                    <input type="text" name="theme" class="form-control" required>
                                </div>


                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="class_level">Class Level:</label>
                                    <select name="class_level" class="form-control" id="class_level">
                                        <option value="">Select Level</option>
                                        <option value="primary_one">Primary One</option>
                                        <option value="primary_two">Primary Two</option>
                                        <option value="primary_three">Primary Three</option>
                                        <option value="primary_four">Primary Four</option>
                                        <option value="primary_five">Primary Five</option>
                                        <option value="primary_six">Primary Six</option>
                                        <option value="jss_one">JSS One</option>
                                        <option value="jss_two">JSS Two</option>
                                        <option value="jss_three">Jss Three</option>
                                    </select>
                                </div>


                                <div id="topics-container">
                                    <div class="form-group topic-group">
                                        <fieldset>
                                            <legend>Topic 1</legend>
                                            <div>
                                                <label for="topic">Topic:</label>
                                                <input type="text" name="topic[]" class="form-control" required>
                                            </div>
                                            <div>
                                                <label for="topic_descriptions">Description:</label>
                                                <textarea name="topic_description[]" class="form-control"></textarea>
                                            </div>
                                        </fieldset>
                                        <button type="button" class="btn btn-danger remove-topic">Remove</button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success" id="add-topic">Add Topic</button>


                                <button type="submit" id='submit-form' class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Add this code within your Blade template, inside the edit modal -->
        <div class="modal fade" id="editCurriculumModal" tabindex="-1" role="dialog" aria-labelledby="editCurriculumModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCurriculumModalLabel">Edit Curriculum  <span id="curriculum-name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add a form for editing a package -->
                        <form id="editCurriculumForm" data-curriculum-id="">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Theme</label>
                                <input type="text" class="form-control" id="edit_theme" name="theme" required>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Update Curriculum</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Add this code within your Blade template, inside the edit modal -->
        <div class="modal fade" id="editCurriculumTopicModal" tabindex="-1" role="dialog" aria-labelledby="editCurriculumTopicModalLabel" aria-hidden="true" data-topic-id="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCurriculumTopicModalLabel">Edit Topic <span id="topic"></span> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" style="display:none" id="editSuccessMessage"></div>
                        <div class="alert alert-danger" style="display:none" id="editErrorMessage"></div>
                        <!-- Add a form for editing a package -->
                        <form id="editCurriculumTopicForm" data-topic-id="">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_topic">Topic</label>
                                <input type="text" class="form-control" id="edit_topic" name="topic" required>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_section_description">Description</label>
                                <textarea class="form-control" id="edit_topic_description" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Update Topic</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="deleteConfirmationModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Topic? 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCurriculumTopicModal" tabindex="-1" role="dialog" aria-labelledby="addCurriculumTopicModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCurriculumTopicModalLabel">
                            Add Topic for <b><span id="curriculum_theme"></span></b>
                        </h5>
                        <span id="curriculum_topic_id" style="display:none" data-curriculum_topic-id=""></span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add a form for adding a new topic -->
                        <form id="addCurriculumTopicForm">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="add_topic">Topic</label>
                                <input type="text" class="form-control" id="add_topic" name="topic" required>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="add_topic_description">Description</label>
                                <textarea class="form-control" id="add_topic_description" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Add Topic</button>
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
     var curricula = @json($curricula);
     console.log(curricula)
  

        // Handle form submission using AJAX with FormData
        $('#createCurriculumForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/store_curricula',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    $('#creatCurriculumModal').modal('hide');


                    // Add success message to the .message div
                    $('.message').removeClass('alert-danger').addClass('alert-success').html('Package created successfully.').show();

                    // Reload the page after 3 seconds
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

        // Handle click event on the "Edit" button
        $(document).on('click', '.edit-curriculum-btn', function () {
            var curriculumId = $(this).data('curriculum-id');
            var curriculum = curricula.find(function (pkg) {
                console.log(curriculumId)
                return pkg.id == curriculumId;
            });

            $('#editCurriculumForm input[name="theme"]').val(curriculum.theme);
            $('#editCurriculumForm textarea[name="description"]').val(curriculum.description);
            

            $('#editCurriculumForm').attr('data-curriculum-id', curriculumId);
            $('#editCurriculumModal').modal('show');
        });

        // Submit form using AJAX for the edit form
        $('#editCurriculumForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var curriculumId = $(this).data('curriculum-id');
            var url = '/curriculum/' + curriculumId + '/edit';
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
                    console.log('Curriculum updated successfully:', response);

                    // Update the UI with the new package values
                    location.reload();

                    $('#editCurriculumModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

        $(document).on('click', '.delete-curriculum-btn', function () {
            var curriculumId = $(this).data('curriculum-id');

            // Ask for confirmation before deleting
            if (confirm('Are you sure you want to delete this package?')) {
                // Make an AJAX request to delete the package
                $.ajax({
                    type: 'DELETE',
                    url: '/curriculum/' + curriculumId,
                    
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
                        var deletedRow = $('tr[data-curriculum-id="' + curriculumId + '"]');
                        deletedRow.replaceWith(successMessage);

                        // Automatically fade out the success message after 6 seconds
                        successMessage.delay(1000).fadeOut(500, function() {
                            
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


        document.addEventListener('DOMContentLoaded', function () {
            const addTopicButton = document.getElementById('add-topic');
            const topicsContainer = document.getElementById('topics-container');

            let topicCount = 1;

            addTopicButton.addEventListener('click', function () {
                const topicGroup = document.querySelector('.topic-group').cloneNode(true);
                topicCount++;
                const legend = topicGroup.querySelector('legend');
                const topicInput = topicGroup.querySelector('[name="topic[]"]');
                const descriptionTextarea = topicGroup.querySelector('[name="topic_description[]"]');
                legend.innerText = `Topic ${topicCount}`;
                topicInput.value = ''; // Clear the previous input
                descriptionTextarea.value = ''; // Clear the previous textarea
                const removeButton = topicGroup.querySelector('.remove-topic');
                removeButton.addEventListener('click', function () {
                    topicsContainer.removeChild(topicGroup);
                });
                topicsContainer.appendChild(topicGroup);
            });
        });

        // Handle click event on the "Edit" button for class section
        $(document).on('click', '.edit-curriculum-z', function (e) {
            e.preventDefault();
            
            var curriculumId = $(this).data('curriculum-id');
            var topicId = $(this).data('topic-id');
            // console.log(curriculumId, topicId)

            // Find the curriculum using curriculumId
            var curriculum = curricula.find(function (pkg) {
                return pkg.id == curriculumId;
            });

            if (curriculum) {
                // console.log(curriculum.topics.pivot)
                // Find the corresponding topic using topicId within the found curriculum
                var curriculumTopic = curriculum.topics.find(function (topic) {
                    return topic.pivot.id == topicId;
                });

                if (curriculumTopic) {
                    $('#editCurriculumTopicForm input[name="topic"]').val(curriculumTopic.pivot.topic);
                    $('#editCurriculumTopicForm textarea[name="description"]').val(curriculumTopic.pivot.description);

                    $('#editCurriculumTopicForm').attr('data-curriculum-id', curriculumId);
                    $('#editCurriculumTopicForm').attr('data-topic-id', topicId);

                    $('#editCurriculumTopicModal').modal('show');
                } else {
                    console.error('Topic not found for curriculumId: ' + curriculumId + ' and topicId: ' + topicId);
                }
            } else {
                console.error('Curriculum not found for curriculumId: ' + curriculumId);
            }
        });

       

      // Submit form using AJAX for the edit form
        $('#editCurriculumTopicForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var topicId = $(this).data('topic-id');
            var url = '/curriculum_topic/' + topicId + '/edit';
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
                    $('#editSuccessMessage').html('Topic updated successfully.').show();
                    setTimeout(function () {
                        $('#editCurriculumTopicModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    $('#editErrorMessage').html('Error updating topic.').show();
                    setTimeout(function () {
                        $('#editCurriculumTopicModal').modal('hide');
                    }, 2000);
                }
            });
        });

                // Handle click event on the "Delete" button for class section
        $(document).on('click', '.delete-curriculum_topic-btn', function () {
            var curriculumId = $(this).data('topic-id');

            // Show the confirmation modal
            $('#deleteConfirmationModal').modal('show');

            // Store the curriculumId in the modal's data attribute
            $('#deleteConfirmationModal').data('topic-id', curriculumId);
        });


        // Handle click event on the "Delete" button inside the modal
        $(document).on('click', '#confirmDelete', function () {
            var topicId = $('#deleteConfirmationModal').data('topic-id');

            // Make an AJAX request to delete the class section
            $.ajax({
                type: 'DELETE',
                url: '/curriculum_topic/' + topicId,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log('Curriculum Topic deleted successfully:', response);

                    // Display a success message
                    var successMessage = $('<div class="alert alert-success" role="alert">Class deleted successfully</div>');

                    // Show the .message div
                    $('.message').show();

                    // Append the success message to the .message div
                    $('.message').append(successMessage);

                    // Automatically fade out the success message after 6 seconds
                    successMessage.delay(1000).fadeOut(500, function() {
                        $(this).remove();
                        location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });

            // Hide the confirmation modal after processing
            $('#deleteConfirmationModal').modal('hide');
        });


        $(document).on('click', '.add-curriculum_topic-btn', function () {
            var curriculumId = $(this).data('curriculum-id');
            var curriculumTheme = $(this).data('curriculum-theme');
            console.log(curriculumId);

            // Set the curriculum theme in the modal
            $('#curriculum_theme').text(curriculumTheme);
            $('#curriculum_topic_id').text(curriculumId); // Corrected line

            $('#addCurriculumForm').attr('data-curriculum-id', curriculumId);
            $('#addCurriculumTopicModal').modal('show');
        });

        // Handle form submission using AJAX with FormData
        $('#addCurriculumTopicForm').submit(function (e) {
            e.preventDefault();

            var curriculumId = $('#curriculum_topic_id').text(); // Corrected line
            console.log(curriculumId);

            var formData = new FormData(this);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/store_curriculum_topic/' + curriculumId,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    $('#addCurriculumTopicModal').modal('hide');

                    // Add success message to the .message div
                    $('.message').removeClass('alert-danger').addClass('alert p-2 alert-success').html('Topic added successfully.').show();

                    // Reload the page after 3 seconds
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });




</script>





@endsection




@extends('layouts.app')

@section('title', "CSS - Show Events")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "Events")

@section('style')
<style>
    .table {
        margin-bottom: 0; /* Remove default bottom margin */
    }

    .nested-table {
        margin-bottom: 0; /* Remove bottom margin for the nested table */
    }
    /* Hide the input visually */
    #banner_picture, #edit_banner_picture {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        border: 0;
    }

    

    /* Style for the label to resemble a button */
    .custom-file-upload, .custom-file-upload_edit {
        display: inline-block;
        padding: 10px 20px;
        cursor: pointer;
        background-color: #f2f2f2; /* Grey background color */
        color: #333; /* Text color */
        border-radius: 5px;
        border: 1px solid #ccc; /* Border color */
        transition: background-color 0.3s ease;
    }

    /* Hover effect for the label */
    .custom-file-upload:hover {
        background-color: #e0e0e0; /* Darker grey background color on hover */
    }

    /* Style for the icon */
    .icon {
        margin-right: 5px;
    }

    /* Style for the image preview */
    #image_preview {
        max-width: 100%;
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

    /* Additional styling as needed */
</style>

@endsection

@section('content')
@include('sidebar')

    <section class="content">
    <div class="">
        <button class="btn btn-primary" data-toggle="modal" data-target="#createEventModal">Create New event</button>
    </div>
        <!-- Default box -->
        <div class="card">
            
            <div class="card-header">
            <h3 class="card-title">
                <img alt="Avatar" class="table-avatar rounded-circle" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px;">
                <b>{{ $school->name }} events</b>
            </h3>


                <div class="card-tools">
                <span class="badge badge-danger">{{ $school->events()->count() }}  event(s)</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button> -->
                </div>
            </div>
            
            <div class="card-body p-0" >
                
                        
                <div class="card-body">
                <ul class="users-list clearfix">
                    @forelse($school->events as $event)
                    <li class="col-md-3 col-6">
                        <div class="card admin-card" data-event-id="{{ $event->id }}" data-event-title="{{ $event->title }}">
                            <div class="card-body">
                                <div class="dropdown" style="position: absolute; top: 10px; left: 10px;">
                                    <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="eventActionsDropdown{{ $event->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="z-index:1001;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <style>
                                        /* Custom styles to hide the down arrow */
                                        .dropdown-toggle::after {
                                            content: none !important;
                                        }
                                    </style>
                                    <div class="dropdown-menu" aria-labelledby="eventActionsDropdown{{ $event->id }}">
                                        <a class="dropdown-item edit-event-btn" href="#" data-event-id="{{ $event->id }}" data-toggle="modal" data-target="#editEventModal">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete-event-btn" href="#" data-event-id="{{ $event->id }}" data-toggle="modal" data-target="#removeEvent{{ $event->id }}">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </div>

                                <div class="user-profile shadow p-3 mb-5 bg-white">
                                <h4><b>
                                    <a class="users-list-name" href="#" data-event-title="{{ $event->title }}">
                                        {{ $event->title }}
                                    </a></b>
                                </h4>
                            
                                @if($event->banner_picture || $school->logo)
                                    <!-- Display the image as a link to open in a modal -->
                                    <a href="#" data-toggle="modal" data-target="#imageModal{{ $event->id }}">
                                        <img src="{{ $event->banner_picture ? asset('storage/' . $event->banner_picture) : ($school->logo ? asset('storage/' . $school->logo) : asset('path_to_camera_icon')) }}" alt="Event Banner" class="img-fluid mt-2">
                                    </a>
                                @else
                                    <!-- Display a camera icon as a placeholder -->
                                    <i class="fas fa-camera" style="font-size: 150px;"></i>
                                @endif

                                <!-- Modal to display full-size image and details -->
                                <div class="modal fade" id="imageModal{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel{{ $event->id }}" aria-hidden="true">
                                    <div class="modal-dialog " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="imageModalLabel{{ $event->id }}">{{ $event->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                            @if($event->banner_picture || $school->logo)
                                                <img src="{{ $event->banner_picture ? asset('storage/' . $event->banner_picture) : ($school->logo ? asset('storage/' . $school->logo) : asset('path_to_camera_icon')) }}" class="img-fluid" alt="Event Banner">
                                            @else
                                                <i class="fas fa-camera" style="font-size: 150px;"></i>
                                            @endif
                                            </div>
                                            <div class="" id="event-details">
                                                <h5 style="cursor:pointer;" class="details-heading bg-secondary p-2 toggle-details-btn" data-target="event-details">
                                                    Details <i class="toggle-icon fas fa-chevron-down"></i>
                                                </h5>
                                                <div class="detail-item">
                                                    <span class="detail-label"><strong>Starting Date:</strong></span>
                                                    <p class="detail-value">{{ $event->start_date }}</p>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label"><strong>End Date:</strong></span>
                                                    <p class="detail-value">{{ $event->end_date }}</p>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label"><strong>Description:</strong></span>
                                                    <p class="detail-value">{{ $event->description }}</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                                <div class="user-permissions">
                                    <style>
                                        #event-details-{{ $event->id }} {
                                            /* display: none; */
                                            margin-top: 10px;
                                            position: absolute;
                                            /* background-color: #f9f9c6; */
                                            border: 1px solid #ccc;
                                            padding: 10px;
                                            z-index: 1000;
                                            width: 100%;
                                            height: 100%;
                                            max-width: calc(100% - 20px);
                                        }
                                    </style>
                                    <h5 style="cursor:pointer;" class="details-heading toggle-details-btn btn bg-purple" data-target="event-details-{{ $event->id }}">
                                        Details <i class="toggle-icon fas fa-chevron-down"></i>
                                    </h5>
                                    <div class="collapsed-details" id="event-details-{{ $event->id }}">
                                    <h5 style="cursor:pointer;" class="details-heading bg-secondary p-2 toggle-details-btn btn bg-purple" data-target="event-details-{{ $event->id }}">
                                        Details <i class="toggle-icon fas fa-chevron-down"></i>
                                    </h5>
                                        <div class="detail-item">
                                            <span class="detail-label"><strong>Starting Date:</strong></span>
                                            <p class="detail-value small-text">{{ $event->start_date }}</p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><strong>End Date:</strong></span>
                                            <p class="detail-value small-text">{{ $event->end_date }}</p>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><strong>Description:</strong></span>
                                            <p class="detail-value small-text">{{ $event->description }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                    @empty
                    <p id="no-admin" class="p-2">No Event Yet.</p>
                    <div>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#createEventModal">Create New Event</button>
                    </div>
                    @endforelse
                </ul>


                </div>
                    
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->



        <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       
                        <!-- Add a form for creating a new package -->
                        <form method="POST"  id="createEventForm" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                            </div>
                            <!--  -->
                            

                            <div class="form-group">
                                <!-- Label styled as a button with picture icon to trigger the file dialog -->
                                <label for="banner_picture" class="custom-file-upload">
                                    <i class="fas fa-image icon"></i> Choose Event Picture
                                </label>
                                <!-- Input element (hidden visually) -->
                                <input type="file" name="banner_picture" id="banner_picture" class="form-control-file">
                            <div id="image_preview"></div>
                            </div>

                            <!-- Image preview section -->

                            
                            <!-- Hidden field for school_id, assuming it's authenticated user's school -->
                            <input type="hidden" name="school_id" value="{{ $school->id }}">
                            <div class="error alert alert-danger" style="display:none"></div>
                        <div class="message alert alert-success" style="display:none"></div>
                            <button type="submit" class="btn btn-primary">Create Event</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <!-- Add this code within your Blade template, inside the edit modal -->
        <!-- Edit event Modal -->
        <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEventModalLabel">Edit event <span id="event-name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Display success and error messages -->
                        
                        <!-- Edit event Form -->
                        <form method="POST" id="editEventForm" data-event-id="" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                            </div>
                            <!-- Image preview section outside of the form -->
                            <div class="form-group">
                                <!-- Label styled as a button with picture icon to trigger the file dialog -->
                                <label for="edit_banner_picture" class="custom-file-upload_edit">
                                    <i class="fas fa-image icon"></i> Choose Event Picture
                                </label>
                                <!-- Input element (hidden visually) -->
                                <input type="file" name="banner_picture" id="edit_banner_picture" class="form-control-file">
                            
                            </div>
                            <div class="modal-body">
                                <div id="edit_image_preview"></div>
                            </div>
                            <!-- Hidden field for school_id, assuming it's authenticated user's school -->
                            <input type="hidden" name="school_id" value="{{ $school->id }}">
                            <div class="alert alert-success" style="display:none"></div>
                        <div class="alert alert-danger" style="display:none"></div>
                            <button type="submit" class="btn btn-primary">Update Event</button>
                            
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>



        <!-- Modal for Delete confirmation -->
        <div class="modal" tabindex="-1" role="dialog" id="confirmationModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-success" id="event-message" style="display:none;"></div>
                    <div class="alert alert-danger" id="event-error" style="display:none;"></div>
                    <div class="modal-body">
                        Are you sure you want to delete this Event? 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>





    </section>
    



@endsection

@section('scripts')
<script>
        var events = @json($school->events);

    $(document).ready(function () {
            console.log(events);

            // Handle form submission using AJAX with FormData
            $('#createEventForm').submit(function (e) {
                e.preventDefault();

                var formData = new FormData(this);
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/school-event',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {

                        // Add success message to the .message div
                        $('.message').removeClass('alert-danger').addClass('alert-success p-2').html('event created successfully.').show();

                        // Wait for 3 seconds before reloading the page
                        setTimeout(function () {
                            // Fade out the success message
                            $('.message').fadeOut();

                            // Wait a little before reloading the page
                            setTimeout(function () {
                                $('#createEventModal').modal('hide');
                                location.reload();
                            }, 500); // Adjust the delay duration based on your preference
                        }, 3000);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        console.log(xhr.responseText);

                        // Check if the response contains error messages
                        var errorMessage = 'An error occurred.'; // Default error message
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // If the response contains a list of errors
                            var errors = xhr.responseJSON.errors;
                            errorMessage = '<ul class="error-list">'; // Add a class to style the list items
                            // Iterate through each error and append it to the error message
                            $.each(errors, function (key, value) {
                                errorMessage += '<li>' + value + '</li>';
                            });
                            errorMessage += '</ul>';
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            // If the response contains a single error message
                            errorMessage = xhr.responseJSON.error;
                        }

                        // Display error message
                        $('.error').removeClass('alert-success').addClass('alert-danger p-2').html(errorMessage).show();

                        // Hide the error message after 3 seconds
                        setTimeout(function () {
                            $('.error').fadeOut();
                        }, 3000);
                    }


                });
        });
        

    });

    $(document).ready(function() {
    // Event listener for delete button click
    $('.delete-event-btn').click(function(e) {
        e.preventDefault(); // Prevent the default action of the link
        
        // Get the event ID from the data attribute
        var eventId = $(this).data('event-id');
        
        // Show the confirmation modal corresponding to the event
        $('#confirmationModal').modal('show');
        
        // Set the data-event-id attribute of the confirmDelete button in the modal
        $('#confirmDelete').attr('data-event-id', eventId);
    });

    // Event listener for confirmDelete button click
    $('#confirmDelete').click(function() {
        // Get the event ID from the data-event-id attribute
        var eventId = $(this).attr('data-event-id');
        
        // Call the deleteevent function with the event ID
        deleteevent(eventId);
    });
});

function deleteevent(eventId) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Make AJAX request to remove event
    $.ajax({
        type: 'POST',
        url: '/delete-event/' + eventId,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            // Hide the confirmation modal
            // $('#confirmationModal').modal('hide');
            
            // Display success message
            $("#event-message").text(response.message).fadeIn();

            // Hide the success message after 3 seconds
            setTimeout(function() {
                $("#event-message").fadeOut();
                // Reload the page after 3 seconds
                location.reload();
            }, 3000);

            // Remove the parent <li> element from the list
            $("#removeEvent" + eventId).closest("li").remove();
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error(xhr.responseText);
            // Hide the confirmation modal
            // $('#confirmationModal').modal('hide');

            // Parse the JSON response to get the 'error' message
            var errorMessage = 'Failed to delete event. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            // Display error message
            $("#event-error").text(errorMessage).fadeIn();

            // Hide the error message after 3 seconds
            setTimeout(function() {
                $("#event-error").fadeOut();
            }, 3000);
        }
    });
}

    $(document).ready(function() {
        // Handle change event of the file input field
        $('#edit_banner_picture').change(function() {
            // Get the selected file
            var file = this.files[0];
            
            // Check if a file is selected
            if (file) {
                // Create a FileReader object to read the file
                var reader = new FileReader();
                
                // Set the onload event handler
                reader.onload = function(e) {
                    // Update the image preview with the newly selected image
                    $('#edit_image_preview').html('<img src="' + e.target.result + '" alt="Event Picture" class="img-fluid">');
                };
                
                // Read the file as a data URL
                reader.readAsDataURL(file);
            }
        });
    });

    $(document).on('click', '.edit-event-btn', function (e) {
        e.preventDefault(); // Prevent the default behavior

        var eventId = $(this).data('event-id');

        var events = {!! json_encode($school->events->toArray()) !!};
        var eventObj = events.find(function (event) {
            return event.id == eventId;
        });

        // Clear the previous image preview for this event
        $('#edit_image_preview').html('');

        // Set event details in the edit form
        $('#editEventForm input[name="title"]').val(eventObj.title);
        $('#editEventForm textarea[name="description"]').val(eventObj.description);
        $('#editEventForm input[name="start_date"]').val(eventObj.start_date);
        $('#editEventForm input[name="end_date"]').val(eventObj.end_date);
        $('#editEventForm input[name="school_id"]').val(eventObj.school_id);

        // Show/hide the image preview container based on whether an image is selected
        if (eventObj.banner_picture) {
            var imagePath = '{{ asset('storage/') }}' + '/' + eventObj.banner_picture;
            var preview = $('#edit_image_preview');
            preview.show();
            preview.html('<img src="' + imagePath + '" alt="Event Picture" class="img-fluid">');
        }

        // Set the event ID in the form data attribute
        $('#editEventForm').attr('data-event-id', eventId);

        // Show the edit event modal
        $('#editEventModal').modal('show');
    });


    // Submit form using AJAX for the edit form
    $('#editEventForm').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var eventId = $(this).data('event-id');
        var url = '/event/' + eventId + '/edit';
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
                console.log('event updated successfully:');

                // Display success message
                var successAlert = $('#editEventModal .alert.alert-success');
                successAlert.empty().append('<p>event updated successfully.</p>').show();

                // Hide the success message after 3 seconds
                setTimeout(function () {
                    successAlert.hide();
                }, 3000);

                // Reload the page after 3 seconds
                setTimeout(function () {
                    location.reload();
                }, 3000);
            },
            error: function (xhr, status, error) {
                console.error(error);
                var errorMessage = xhr.responseJSON; // Get the JSON response
                console.log(errorMessage);

                // Clear any previous error messages
                $('#editEventModal .alert.alert-danger').empty();

                // Check if the error message contains the 'error' key
                if (errorMessage.error) {
                    // Iterate over each error message and display it
                    $.each(errorMessage.error, function (field, message) {
                        var errorAlert = $('#editEventModal .alert.alert-danger');
                        errorAlert.append('<p>' + message + '</p>').show();
                    });
                } else {
                    // If the error message doesn't contain the 'error' key, display it directly
                    var errorAlert = $('#editEventModal .alert.alert-danger');
                    errorAlert.append('<p>' + errorMessage + '</p>').show();
                }
            }
        });
    });

    




   
</script>
<script>
    // JavaScript code to preview the selected image
    document.getElementById('banner_picture').addEventListener('change', function() {
        var file = this.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var image = new Image();
            image.src = e.target.result;

            image.onload = function() {
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                var maxWidth = 500;
                var maxHeight = 500;
                var width = image.width;
                var height = image.height;

                // Calculate new dimensions to maintain aspect ratio
                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }

                // Get the width of the containing div
                var containerWidth = document.getElementById('image_preview').offsetWidth;

                // Adjust width and height based on container width
                if (width > containerWidth) {
                    var scaleFactor = containerWidth / width;
                    width *= scaleFactor;
                    height *= scaleFactor;
                }

                // Set canvas dimensions
                canvas.width = width;
                canvas.height = height;

                // Draw image on canvas
                ctx.drawImage(image, 0, 0, width, height);

                // Display the resized image in the image preview div
                var preview = document.getElementById('image_preview');
                preview.innerHTML = ''; // Clear previous content
                preview.appendChild(canvas);

                // Add img-fluid class to make the image responsive
                var img = preview.getElementsByTagName('canvas')[0];
                img.classList.add('img-fluid');

                // Center the image horizontally
                img.style.margin = '0 auto';
            };
        };

        reader.readAsDataURL(file);
    });

</script>





@endsection




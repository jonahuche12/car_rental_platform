@extends('layouts.app')

@section('title', "Central School system - Show Classes")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "School Package")

@section('style')
<style>
    .table {
        margin-bottom: 0; /* Remove default bottom margin */
    }

    .nested-table {
        margin-bottom: 0; /* Remove bottom margin for the nested table */
    }

    /* Additional styling as needed */
</style>

@endsection

@section('content')
@include('sidebar')
<div class="">
    <button class="btn btn-primary" data-toggle="modal" data-target="#createCourseModal">Create New Course</button>
</div>
    <section class="content">

        <!-- Default box -->
        <div class="card">
            
            <div class="card-header">
            <h3 class="card-title">
                <img alt="Avatar" class="table-avatar rounded-circle" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px;">
                <b>{{ $school->name }} Courses</b>
            </h3>


                <div class="card-tools">
                <span class="badge badge-danger">{{ $school->courses()->count() }}  course(s)</span>
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
                        @forelse($school->courses as $course)
                        <li class="col-md-3 col-6">
                        <div class="card admin-card" data-admin-id="{{ $course->id }}" data-admin-name="{{ $course->name}}">
                            <div class="card-body">
                            <div class="dropdown" style="position: absolute; top: 10px; left: 10px;">
                                <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="studentActionsDropdown{{ $course->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <style>
                                    /* Custom styles to hide the down arrow */
                                    .dropdown-toggle::after {
                                        content: none !important;
                                    }
                                </style>
                                <div class="dropdown-menu" aria-labelledby="studentActionsDropdown{{ $course->id }}">
                                    <a class="dropdown-item edit-course-btn" href="#" data-course-id="{{ $course->id }}" data-toggle="modal" data-target="#editCourseModal">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a class="dropdown-item" href="#" title="Choose classes for this course" onclick="loadClassesAndTeachers({{ $course->id }}, '{{$course->name}}')">
                                        <i class="fas fa-list"></i> Choose Classes
                                    </a>



                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removeCourse{{ $course->id }}">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                </div>
                            </div>


                            <div class="user-profile shadow p-3 mb-5 bg-white rounded">
                                <h1 class="mb-3 p-3"><b>{{ $course->code }}</b></h1>
                                <h4>
                                    <a class="users-list-name" href="#" data-admin-name="{{ $course->name }}">
                                        {{ $course->name }}
                                    </a>
                                </h4>
                            </div>

                                <!-- <span class="users-list-date">{{ \Carbon\Carbon::parse($course->created_at)->diffForHumans() }}</span> -->

                                <div class="user-permissions">
                                    <h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="user-details-{{ $course->id }}">
                                        Details <i class="toggle-icon fas fa-chevron-down"></i>
                                    </h5>
                                    <div id="user-details-{{ $course->id }}" class="collapsed-details">
                                        <!-- Your existing details content here -->
                                        <div class="detail-item">
                                            <span class="detail-label"><strong>No of Teachers:</strong></span>
                                            <span class="detail-value">{{ $course->teachers->count() }} Teachers</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"><strong>Total Students:</strong></span>
                                            <span class="detail-value">{{ $course->students->count()}} Students</span>
                                        </div>
                                        <div class="detail-item">
                                            <!-- <span class="detail-label"><strong>Description:</strong></span><br> -->
                                            <p class="detail-value">{{ $course->description}}</p>
                                        </div>
                                        
                                    </div>
                                </div>

                                

                                <!-- Remove Admin Modal -->
                                <div class="modal fade" id="removeCourse{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="removeCourseLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="removeCourseLabel">Delete Course <b>{{$course->code}}</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to Delete {{ $course->name}}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-danger" onclick="deleteCourse({{ $course->id }})">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                        @empty
                            <p id="no-admin" class="p-2">No Course found for this school.</p>
                        @endforelse
                    </ul>
                </div>
                    
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <div class="modal fade" id="chooseClassesModal" tabindex="-1" role="dialog" aria-labelledby="chooseClassesModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chooseClassesModalLabel">Choose Classes and Teachers for <b><span id="course_name"></span></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="message alert alert-success" style="display:none"></div>
                    <div class="error alert alert-danger" style="display:none"></div>
                    <div class="modal-body" id="chooseClassesModalBody">
                        <!-- Add your dynamic content here -->
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveSelectedClasses()">Save</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="createCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="error alert alert-danger" style="display:none"></div>
                        <div class="message alert alert-success" style="display:none"></div>
                        <!-- Add a form for creating a new package -->
                        <form id="createCourseForm">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Course Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required placeholder="E.g Mathematics">
                                <input type="hidden" name="school_id" value="{{$school->id}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Course Code</label>
                                <input type="text" class="form-control" id="edit_code" name="code" required placeholder="E.g Maths">
                            </div>
                            <div class="form-group">
                                <label for="section_name">General name:</label>
                                <select name="general_name" class="form-control" id="general_name">
                                    <option value="">Select General name</option>
                                    @foreach($uniqueSubjectNames as $subject_name)
                                    <option value="{{$subject_name}}">{{$subject_name}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" class="form-control" id="school_id" name="school_id" value="{{$school->id}}">
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <!-- Compulsory checkbox -->
                            <div class="form-group">
                                <label for="compulsory">Compulsory:</label>
                                <input type="checkbox" id="compulsory" name="compulsory" value="1"><br>
                                <span class="info text-danger">Check this box if this course is compulsory, otherwise leave it blank.</span>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Create Course</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Add this code within your Blade template, inside the edit modal -->
        <!-- Edit Course Modal -->
        <div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCourseModalLabel">Edit Course <span id="course-name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Display success and error messages -->
                        <div class="alert alert-success" style="display:none"></div>
                        <div class="alert alert-danger" style="display:none"></div>
                        <!-- Edit Course Form -->
                        <form id="editCourseForm" enctype="multipart/form-data" data-course-id="">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Course Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required placeholder="E.g English Language">
                            </div>
                            <div class="form-group">
                                <label for="edit_code">Course Code</label>
                                <input type="text" class="form-control" id="edit_code" name="code" required placeholder="E.g JSS1">
                            </div>

                            <!-- General Name field -->
                            <div class="form-group">
                                <label for="edit_general_name">General Name</label>
                                <select name="general_name" class="form-control" id="edit_general_name">
                                    <option value="">Select General Name</option>
                                    <!-- Options will be populated dynamically using JavaScript -->
                                </select>
                            </div>

                            <!-- Compulsory checkbox -->
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="edit_compulsory" name="compulsory" value="1">
                                    <label class="form-check-label" for="edit_compulsory">Compulsory</label>
                                </div>
                                <span class="info text-danger">Check this box if this course is compulsory, otherwise leave it blank.</span>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Update Course</button>
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
                <div class="modal-body">
                    Are you sure you want to delete this section? This action will also remove associated teachers and students.
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

console.log({!! json_encode($course->getAllSections()) !!});
    $(document).ready(function () {
        var courses = @json($school->courses);
            console.log(courses);

            // Handle form submission using AJAX with FormData
            $('#createCourseForm').submit(function (e) {
                e.preventDefault();

                var formData = new FormData(this);
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/school-course',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {

                        // Add success message to the .message div
                        $('.message').removeClass('alert-danger').addClass('alert-success p-2').html('Course created successfully.').show();

                        // Wait for 3 seconds before reloading the page
                        setTimeout(function () {
                            // Fade out the success message
                            $('.message').fadeOut();

                            // Wait a little before reloading the page
                            setTimeout(function () {
                                $('#createCourseModal').modal('hide');
                                location.reload();
                            }, 500); // Adjust the delay duration based on your preference
                        }, 3000);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        console.log(xhr.responseText);

                        // Display error message
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred.';
                        $('.error').removeClass('alert-success').addClass('alert-danger p-2').html(errorMessage).show();

                        // Hide the error message after 3 seconds
                        setTimeout(function () {
                            $('.error').fadeOut();
                        }, 3000);
                    }


                });
        });
        

    });


    function deleteCourse(courseId) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Make AJAX request to remove admin
        $.ajax({
            type: 'POST',
            url: '/delete-course/' + courseId,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                $("#removeCourse" + courseId).modal("hide");
                // Display success message
                $("#student-message").text(response.message).fadeIn();

                // Hide the success message after 3 seconds
                setTimeout(function () {
                    
                    $("#student-message").fadeOut();
                }, 3000);

                // Hide the modal
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();


                // Remove the parent <li> element from the list
                $("#removeCourse" + courseId).closest("li").remove().delay(2000);
                location.reload()
            },


            error: function (xhr, textStatus, errorThrown) {
                console.error(xhr.responseText);
                $("#removeCourse" + courseId).modal("hide");

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
    function loadClassesAndTeachers(courseId, courseName) {
        // Use AJAX to fetch class sections for the specific course
        $.ajax({
            url: '/fetch-class-sections/' + courseId,
            type: 'GET',
            dataType: 'json',
            success: function (selectedClassSections) {
                console.log(selectedClassSections);

                // Update the modal body with the dynamic content
                var modalBody = $('#chooseClassesModalBody');
                modalBody.empty();

                // Create the form element
                var chooseClassesForm = $('<form>', {
                    id: 'chooseClassesForm'
                });

                // Render class sections in the modal body
                var schoolClassSections = <?= json_encode($course->school->schoolClassSections) ?>;

                schoolClassSections.forEach(function (classSection) {
                    var checkbox = $('<input>', {
                        type: 'checkbox',
                        class: 'form-check-input',
                        name: 'selected_classes[]',
                        value: classSection.id,
                        checked: selectedClassSections.some(section => section.id === classSection.id)
                    });

                    var label = $('<label>', {
                        class: 'form-check-label',
                        text: classSection.name
                    });
                    var classIdInput = $('<input>', {
                        type: 'hidden',
                        name: 'class_id[]', // Change to an array since multiple class IDs can be selected
                        value: classSection.id
                    });

                    // Create a select tag for teachers
                    var teacherSelect = $('<select>', {
                        class: 'form-control',
                        name: 'teachers[]'
                    });

                    // Check if the class section is associated with the specific course
                    var isClassSectionSelected = selectedClassSections.some(section => section.id == classSection.id);

                    // Determine the selected teacher for the class section from the selectedClassSections
                    var selectedTeacherId = isClassSectionSelected ?
                        getSelectedTeacherId(selectedClassSections, classSection.id) :
                        null;

                    // Add the default option when the class section is not added to the course
                    teacherSelect.append($('<option>', {
                        value: '',
                        text: 'Select teacher for course'
                    }));

                    // Add options for teachers from the school's teachers
                    var schoolTeachers = <?= json_encode($course->school->confirmedTeachers) ?>;
                    schoolTeachers.forEach(function (teacher) {
                        var option = $('<option>', {
                            value: teacher.id,
                            text: `${teacher.last_name} ${teacher.middle_name} ${teacher.first_name}`
                        });

                        // Set the selected option if it matches the selectedTeacherId
                        if (selectedTeacherId && selectedTeacherId === teacher.id) {
                            option.prop('selected', true);
                        }

                        teacherSelect.append(option);
                    });

                    // Check the checkbox if the class section is associated with the specific course
                    if (isClassSectionSelected) {
                        checkbox.prop('checked', true);
                    }

                    // Create a div to hold both the checkbox and teacher select
                    var formCheck = $('<div>', {
                        class: 'form-check'
                    }).append(
                        $('<input>', {
                            type: 'hidden',
                            name: 'course_id',
                            value: courseId
                        }),
                        classIdInput,
                        checkbox,
                        label,
                        teacherSelect
                    );

                    chooseClassesForm.append(formCheck);
                });

                // Append the form to the modal body
                modalBody.append(chooseClassesForm);

                // Set the course name in the modal title and wrap it in a <b> tag
                $('#course_name').html('<b>' + courseName + '</b>');

                // Show the modal
                $('#chooseClassesModal').modal('show');
            },
            error: function (error) {
                console.error('Error fetching class sections:', error);
            }
        });

        // Function to get the selected teacher ID for a class section
        function getSelectedTeacherId(selectedClassSections, classcourseId) {
            var selectedSection = selectedClassSections.find(section => section.id == classcourseId);
            return selectedSection ? selectedSection.pivot.teacher_id : null;
        }

        // Function to get the matching teacher based on the teacher ID
        function getMatchingTeacher(teacherId) {
            var schoolTeachers = <?= json_encode($course->school->confirmedTeachers) ?>;
            return schoolTeachers.find(teacher => teacher.id === teacherId);
        }
    }
    function saveSelectedClasses() {
        // Serialize form data including the course_id
        var formData = $('#chooseClassesForm').serializeArray();
        
        // Filter out null values for teachers
        formData = formData.filter(function(item) {
            return item.name !== "teachers[]" || item.value !== "";
        });

        console.log(formData);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Send an AJAX request to the backend
        $.ajax({
            url: '/update_class_section_course',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                console.log(response);

                // Add success message to the .message div
                $('.message').removeClass('alert-danger').addClass('alert-success p-2').html(response.message).show();

                // Wait for 3 seconds before reloading the page
                setTimeout(function () {
                    // Fade out the success message
                    $('.message').fadeOut();

                    // Wait a little before reloading the page
                    setTimeout(function () {
                        // $('#chooseClassesModal').modal('hide');
                        // location.reload();
                    }, 500); // Adjust the delay duration based on your preference
                }, 3000);
            },
            error: function (xhr, status, error) {
                console.error(error);
                console.log(xhr.responseText);

                // Display error message
                var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred.';
                $('.error').removeClass('alert-success').addClass('alert-danger p-2').html(errorMessage).show();

                // Hide the error message after 3 seconds
                setTimeout(function () {
                    $('.error').fadeOut();
                }, 3000);
            },
            complete: function () {
            }
        });
    }


    $(document).on('click', '.edit-course-btn', function (e) {
        e.preventDefault(); // Prevent the default behavior

        var courseId = $(this).data('course-id');

        var courses = @json($school->courses->toArray());
        console.log(courses)
        var courseObj = courses.find(function (course) {
            return course.id == courseId;
        });

        // Set course details in the edit form
        $('#editCourseForm input[name="name"]').val(courseObj.name);
        $('#editCourseForm input[name="code"]').val(courseObj.code);
        $('#editCourseForm textarea[name="description"]').val(courseObj.description);
        $('#course-name').text(courseObj.code);

        // Populate the general_name select field dynamically
        $('#edit_general_name').empty();
        $.each(@json($uniqueSubjectNames), function(index, subjectName) {
            $('#edit_general_name').append($('<option>', {
                value: subjectName,
                text: subjectName,
                selected: subjectName == courseObj.general_name // Set selected option to the general_name value of the course
            }));
        });

        // Set the compulsory checkbox
        if (courseObj.compulsory) {
            $('#edit_compulsory').prop('checked', true);
        } else {
            $('#edit_compulsory').prop('checked', false);
        }

        // Set the course ID in the form data attribute
        $('#editCourseForm').attr('data-course-id', courseId);

        // Show the edit course modal
        $('#editCourseModal').modal('show');
    });


// Submit form using AJAX for the edit form
$('#editCourseForm').submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    var courseId = $(this).data('course-id');
    var url = '/course/' + courseId + '/edit';
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
            console.log('Course updated successfully:', response);

            // Display success message
            var successAlert = $('#editCourseModal .alert.alert-success');
            successAlert.empty().append('<p>Course updated successfully.</p>').show();

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
            $('#editCourseModal .alert.alert-danger').empty();

            // Check if the error message contains the 'error' key
            if (errorMessage.error) {
                // Iterate over each error message and display it
                $.each(errorMessage.error, function (field, message) {
                    var errorAlert = $('#editCourseModal .alert.alert-danger');
                    errorAlert.append('<p>' + message + '</p>').show();
                });
            } else {
                // If the error message doesn't contain the 'error' key, display it directly
                var errorAlert = $('#editCourseModal .alert.alert-danger');
                errorAlert.append('<p>' + errorMessage + '</p>').show();
            }
        }
    });
});





   
</script>




@endsection




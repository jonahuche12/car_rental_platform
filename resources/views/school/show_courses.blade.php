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
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-edit"></i> Edit
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
                        <form id="createCourseForm" >
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Course Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required placeholder="E.g Mathematics ">
                                <input type="hidden" name="school_id", value="{{$school->id}}">
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

    
                            


                            <button type="submit" class="btn btn-primary mt-3">Create Course</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this code within your Blade template, inside the edit modal -->
        <div class="modal fade" id="editClassModal" tabindex="-1" role="dialog" aria-labelledby="editClassModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editClassModalLabel">Edit Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" style="display:none"></div>
                        <div class="alert alert-danger" style="display:none"></div>
                        <!-- Add a form for editing a package -->
                        <form id="editClassForm" data-class-id="" enctype="multipart/form-data">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Class Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required placeholder="E.g Junior Secondary School 1">
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Class Code</label>
                                <input type="text" class="form-control" id="edit_code" name="code" required placeholder="E.g JSS1">
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Class Level</label>
                                <select name="class_level" class="form-control" id=""></select>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>

                            <!-- Picture field -->
                            <div class="form-group">
                                <label for="edit_picture">Banner</label>
                                <input type="file" class="form-control-file" id="edit_picture" name="picture" accept="image/*">
                                <div id="edit_picturePreview" class="mt-2"></div>
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Update Class</button>
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
        // Predefined array of class levels
        var predefinedClassLevels = {'Primary One':'primary_one', 'Primary Two':'primary_two', 'Primary Three':'primary_three', 'Primary Four':'primary_four','Primary Five':'primary_five','Primary Six':'primary_six', 'JSS One':'jss_one','JSS Two':'jss_two','JSS Three':'jss_three','SSS One':'sss_one','SSS Two':'sss_two','SSS Three':'sss_three'};

       // Handle click event on the "Edit" button
        $(document).on('click', '.edit-class-btn', function () {
            var classId = $(this).data('class-id');
            var classObj = classes.find(function (pkg) {
                return pkg.id == classId;
            });

            // Populate the class level select box
            var classLevelSelect = $('#editClassForm select[name="class_level"]');
            classLevelSelect.empty();

            // Find the display and value pair for the actual class level
            var actualClassLevelPair = Object.entries(predefinedClassLevels).find(function ([display, value]) {
                return classObj.class_level === value;
            });

            // Check if actual class level pair is found
            if (actualClassLevelPair) {
                // Add the actual class level as the first option
                classLevelSelect.append($('<option>', {
                    value: actualClassLevelPair[1],
                    text: actualClassLevelPair[0],
                    selected: true
                }));
            } else {
                // Handle the case when class level is not found in predefinedClassLevels
                // You can choose to show a default option or handle it according to your needs
                classLevelSelect.append($('<option>', {
                    value: '',
                    text: 'Class Level Not Found',
                    selected: true
                }));
            }

            // Iterate over each predefined class level and add it as an option
            Object.entries(predefinedClassLevels).forEach(function ([display, value]) {
                if (classObj.class_level != value) {
                    classLevelSelect.append($('<option>', {
                        value: value,
                        text: display
                    }));
                }
            });

            $('#editClassForm input[name="name"]').val(classObj.name);
            $('#editClassForm input[name="code"]').val(classObj.code);
            $('#editClassForm textarea[name="description"]').val(classObj.description);

            $('#editClassForm').attr('data-class-id', classId);
            $('#editClassModal').modal('show');
        });

       // Submit form using AJAX for the edit form
        $('#editClassForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var classId = $(this).data('class-id');
            console.log(classId);
            var url = '/class/' + classId + '/edit';
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
                    console.log('Class updated successfully:', response);

                    // Display success message for 2 seconds
                    $('.alert-success').html('Class updated successfully').show().delay(3000).fadeOut();


                    // Wait for 3 seconds before hiding the modal
                    setTimeout(function () {
                        $('#editClassModal').modal('hide');
                    }, 3000);

                    // Update the UI with the new class values
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    console.log('Error response:', xhr.responseText);

                    // Display error message for 2 seconds
                    $('.alert-danger').html('Error: ' + xhr.responseText).show().delay(3000).fadeOut();
                }
            });
        });

        $(document).on('click', '.add-class-section-btn', function (e) {
            e.preventDefault(); // Prevent the default behavior

            var classId = $(this).data('class-id');
            var classObj = classes.find(function (pkg) {
                return pkg.id == classId;
            });
            var className = classObj.name;

            // Set the class name in the modal
            $('#class-name').text(className);
            $('#addClassSectionForm input[name="class_id"]').val(classId);

            // Set the class ID in the form data attribute
            $('#addClassSectionForm').attr('data-class-id', classId);

            // Show the modal
            // $('#createClassSectionModal').modal('show');
        });


             // Submit form using AJAX for the edit form
        $('#addClassSectionForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var classId = $(this).data('class-id');
            var url = '/class_section/' + classId + '/add';
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
                    console.log('Class updated successfully:', response);

                    // Update the UI with the new class values
                    location.reload();

                    $('#addClassSectionForm').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
                }
            });
        });

        // Handle click event on the "Edit" button for class section
        $(document).on('click', '.edit-section-z', function (e) {
            e.preventDefault(); // Prevent the default behavior

            var sectionId = $(this).data('section-id');

            var sections = @json($school->classes->flatMap->schoolClassSections->toArray());
            var sectionObj = sections.find(function (sec) {
                return sec.id == sectionId;
            });
            console.log(sections)

            // Pass the sectionObj to the Blade view
            var sectionJson = @json($school->confirmedTeachers->toArray());

            $('#editSectionForm input[name="name"]').val(sectionObj.name);
            $('#editSectionForm input[name="code"]').val(sectionObj.code);
            $('#editSectionForm textarea[name="description"]').val(sectionObj.description);

            // Populate the main form teacher dropdown
            var mainFormTeacherSelect = $('#main_form_teacher');
            mainFormTeacherSelect.empty(); // Clear existing options

            // Populate with teachers from the school using the JSON data
            sectionJson.forEach(function (teacher) {
                mainFormTeacherSelect.append($('<option>', {
                    value: teacher.id,
                    text: teacher.profile.full_name,
                    selected: teacher.id == sectionObj.main_form_teacher_id ? 'selected' : ''
                }));
            });

            // If there is no current form teacher, add an empty option
            if (!sectionObj.main_form_teacher_id) {
                mainFormTeacherSelect.prepend($('<option>', {
                    value: '',
                    text: ''
                }));
            }

            // Show the edit section modal
            $('#editSectionForm').attr('data-section-id', sectionId);
            $('#editSectionModal').modal('show');
        });

        // Submit form using AJAX for the edit form
        $('#editSectionForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var sectionId = $(this).data('section-id');
            console.log(sectionId)
            var url = '/section/' + sectionId + '/edit';
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
                    console.log('Class updated successfully:', response);

                    // Update the UI with the new class values
                    location.reload();

                    $('#editSectionModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    console.log(xhr.responseText);
                    // Add logic to display error messages or perform other actions
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
    function getSelectedTeacherId(selectedClassSections, classSectionId) {
        var selectedSection = selectedClassSections.find(section => section.id == classSectionId);
        return selectedSection ? selectedSection.pivot.teacher_id : null;
    }

    // Function to get the matching teacher based on the teacher ID
    function getMatchingTeacher(teacherId) {
        var schoolTeachers = <?= json_encode($course->school->confirmedTeachers) ?>;
        return schoolTeachers.find(teacher => teacher.id === teacherId);
    }
}


    function saveSelectedClasses() {
    // Get form data including the course_id
    var formData = $('#chooseClassesForm').serialize();
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
        },
        complete: function () {
        }
    });
}



    // Handle click event on the "Delete" button for class section
    $(document).on('click', '.delete-section-btn', function () {
        var sectionId = $(this).data('section-id');

        // Show the confirmation modal
        $('#confirmationModal').modal('show');

        // Store the sectionId in the modal's data attribute
        $('#confirmationModal').data('section-id', sectionId);
    });


    // Handle click event on the "Delete" button inside the modal
    $(document).on('click', '#confirmDelete', function () {
        var sectionId = $('#confirmationModal').data('section-id');

        // Make an AJAX request to delete the class section
        $.ajax({
            type: 'DELETE',
            url: '/section/' + sectionId,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Class Section deleted successfully:', response);

                // Display a success message
                var successMessage = $('<div class="alert alert-success" role="alert">Class deleted successfully</div>');

                // Show the .message div
                $('.message').show();

                // Append the success message to the .message div
                $('.message').append(successMessage);

                // Automatically fade out the success message after 6 seconds
                successMessage.delay(6000).fadeOut(500, function() {
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
        $('#confirmationModal').modal('hide');
    });

    $(document).ready(function() {
        $('.toggle-nested-table').on('click', function() {
            // Get the data-class-id attribute from the button
            var classId = $(this).data('class-id');

            // Toggle the display of the corresponding nested table
            $('.nested-table[data-class-id="' + classId + '"]').toggle();

            // Toggle the icon between plus and minus
            var icon = $('.toggle-icon', this);
            icon.toggleClass('fa-chevron-up fa-chevron-down');
        });
    });
    @if(isset($section))

    $(document).on('click', '.view-section-details-btn', function () {
        var sectionId = $(this).data('section-id');
       
        var sectionDetails = `
            <p><strong>Name:</strong> {{ $section->name }}</p>
            <p><strong>Code:</strong> {{ $section->code }}</p>
            <p><strong>Description:</strong> {{ $section->description }}</p>
            <!-- Add more details as needed -->
        `;
        $('#viewSectionDetailsModal .modal-body').html(sectionDetails);
    });
    @endif

</script>




@endsection




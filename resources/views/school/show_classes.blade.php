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
    <button class="btn btn-primary" data-toggle="modal" data-target="#createClassModal">Create New Class</button>
</div>
    <section class="content">

        <!-- Default box -->
        <div class="card">
            
            <div class="card-header">
            <h3 class="card-title">
                <img alt="Avatar" class="table-avatar rounded-circle" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px;">
                <b>{{ $school->name }} Classes</b>
            </h3>


                <div class="card-tools">
                <span class="badge badge-danger">{{ $school->classes()->count(). '/'. $school->schoolPackage->max_classes . ' class(es)' }}</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button> -->
                </div>
            </div>
            
            <div class="card-body p-0 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl" >
                <div class="message" style="display:none"></div>
                <table class="table projects">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th style="width: 21%">
                                Class Name
                            </th>
                            <th style="width: 12%" class="text-center">
                                Banner
                            </th>
                            
                            <th style="width: 12%" class="text-center">
                                Total Students
                            </th>
                            <th style="width: 12%" class="text-center">
                                Total Teachers
                            </th>
                            
                            <th style="width: 20%" class="text-center">
                                Description
                            </th>
                            <!-- <th style="width: 10%">
                                Progress
                            </th> -->
                            
                            <th style="width: 25%"  class="text-center">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($school->classes as $class)
                        <tr class="bg-info">
                            <td>
                                {{ $loop->iteration }}
                                <i class="toggle-icon fa fa-chevron-up"></i>
                            </td>
                            <td style="width: 21%">
                                <a>
                                    {{ $class->name }}
                                </a>
                                <br />
                                <small>
                                    Created {{ $class->created_at->format('d.m.Y') }}
                                </small>
                                <a class="badge badge-sm badge-warning">{{ $class->schoolClassSections()->count()}} section(s)</a>
                            </td>
                            <td style="width: 12%" class="text-center">
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        {{-- Add logic to display package image --}}
                                        <img alt="Avatar" class="table-avatar" src="{{ asset('storage/' . $class->picture) }}">
                                    </li>
                                </ul>
                            </td>
                            <td style="width: 12%" class="text-center">
                                {{ $class->students()->count() }}
                            </td>
                            <td style="width: 12%" class="text-center">
                                {{ $class->teachers()->count() }}
                            </td>


                            
                            <td style="width: 25%" class="text-center">
                                {{ $class->description }}
                            </td>
                            <!-- <td class="project_progress text-center" style="width: 10%" >
                                
                                <small>
                                    {{ 0 }}% Complete
                                </small>
                            </td> -->

                            <td class="project-actions text-right">
                                <div class="btn-group">
                                    
                                    <button class="btn btn-primary btn-sm add-class-section-btn" data-toggle="modal" data-class-id="{{ $class->id }}" data-target="#createClassSectionModal">
                                        <i class="fa fa-plus"></i><span class="action d-none d-sm-inline"> Section</span>
                                    </button>
                                    <a class="btn btn-secondary btn-sm edit-class-btn" href="#" data-class-id="{{ $class->id }}">
                                        <i class="fas fa-pencil-alt"></i> <span class="action d-none d-sm-inline">Edit</span>
                                    </a>
                                    <a class="btn btn-dark btn-sm view-section-details-btn" href="{{ route('view_curriculum', ['classId' => $class->id]) }}">
                                        <i class="fas fa-eye"></i> Curriculum
                                    </a>
                                    <button class="btn btn-warning toggle-nested-table" data-class-id="{{ $class->id }}">
                                    <i class="toggle-icon fa fa-chevron-up"></i><span class="action d-none d-sm-inline"></span>
                                    </button>
                                    <!-- <a class="btn btn-danger btn-sm delete-package-btn" href="#" data-class-id="{{ $class->id }}" data-picture="{{ $class->picture }}">
                                        <i class="fas fa-trash"></i><span class="action d-none d-sm-inline"> Delete</span>
                                    </a> -->
                                </div>
                            </td>


                        </tr>
                        <tr>

                        
                        <td colspan="8"  style="margin-top:-5px" > 
                        
                           
                        <table class="table table-striped nested-table mt-2" data-class-id="{{ $class->id }}" >
      
                            <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th style="width: 20%">Class Section Name</th>
                                    <th style="width: 15%">Form Teacher</th>
                                    <th style="width: 15%">Number of Students</th>
                                    <th style="width: 15%">Description</th>
                                    <th style="width: 20%">Actions</th> <!-- Add a column for actions -->
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($class->schoolClassSections as $section)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $section->name }}</td>
                                        <td>
                                            @if ($section->mainFormTeacher)
                                                {{ $section->mainFormTeacher->profile->full_name }}
                                            @else
                                                No Form Teacher Assigned
                                            @endif
                                        </td>
                                        <td>{{ $section->students->count() }}</td>
                                        <td>{{ $section->description }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-info btn-sm view-section-details-btn" href="{{ route('view_section', ['sectionId' => $section->id]) }}">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a class="btn btn-secondary btn-sm edit-section-z" href="#" data-section-id="{{ $section->id }}" data-toggle="modal" data-target="#editSectionModal">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm delete-section-btn" href="#" data-section-id="{{ $section->id }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No Class Sections available for this Class.
                                            <!-- You can add a button to create new class sections if needed -->
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        </td>
                    </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                No Class available for this School.
                                <div class="-left">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#createClassModal">Create New Class</button>
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


        <!-- Modal for Creating a New Class Section -->
        <div class="modal fade" id="createClassSectionModal" tabindex="-1" role="dialog" aria-labelledby="createClassSectionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createClassSectionModalLabel">Create New Class Section for <b><span id="class-name"></span></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for Creating a New Class Section -->
                        <form action="#" id="addClassSectionForm" method="POST">
                            @csrf
                            <!-- Class Section Name -->
                            <div class="form-group">
                                <label for="section_name">Class Section Name:</label>
                                <input type="text" class="form-control" id="section_name" name="section_name" placeholder="Junior Secondary School 1 A" required>
                            </div>

                            <div class="form-group">
                                <label for="section_name">Code:</label>
                                <input type="text" class="form-control" id="section_code" name="section_code" placeholder = "E.g JSS1 A" required>

                                <input type="hidden" class="form-control" id="class_id" name="class_id" >
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <!-- Main Form Teacher (if applicable) -->
                            <div class="form-group">
                                <label for="main_form_teacher">Main Form Teacher:</label>
                                <select class="form-control" id="main_form_teacher" name="main_form_teacher">
                                    <option value="">Select Main Form Teacher</option>
                                    @foreach ($school->confirmedTeachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->profile->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Add more form fields as needed -->

                            <!-- Button to Submit the Form -->
                            <button type="submit" class="btn btn-primary">Create Class Section</button>
                        </form>
                    </div>
                    <!-- Additional Modal Footer or Content as needed -->
                </div>
            </div>
        </div>

        <div class="modal fade" id="createClassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add a form for creating a new package -->
                        <form id="createClassForm" enctype="multipart/form-data">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Class Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required placeholder="E.g Junior Secondary School 1">
                                <input type="hidden" name="school_id", value="{{$school->id}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Class Code</label>
                                <input type="text" class="form-control" id="edit_code" name="code" required placeholder="E.g JSS1">
                            </div>
                            <div class="form-group">
                                <label for="section_name">Class Level:</label>
                                <select name="class_level" class="form-control" id="class_level">
                                    <option value="">Select Class Level</option>
                                    <option value="primary_one">Primary One</option>
                                    <option value="primary_two">Primary Two</option>
                                    <option value="primary_three">Primary Three</option>
                                    <option value="primary_four">Primary Four</option>
                                    <option value="primary_five">Primary Five</option>
                                    <option value="primary_six">Primary Six</option>
                                    <option value="jss_one">JSS One</option>
                                    <option value="jss_two">JSS Two</option>
                                    <option value="jss_three">Jss Three</option>
                                    <option value="sss_one">SSS One</option>
                                    <option value="sss_two">SSS Two</option>
                                    <option value="sss_three">SSS Three</option>
                                </select>

                                <input type="hidden" class="form-control" id="class_id" name="class_id" >
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <!-- Picture field -->
                            <div class="form-group">
                                <label for="picture">Banner</label>
                                <input type="file" class="form-control-file form-control" id="picture" name="picture" accept="image/*">
                                <div id="picturePreview" class="mt-2"></div>
                            </div>
                            


                            <button type="submit" class="btn btn-primary mt-3">Create School</button>
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



        <!-- Add this code within your Blade template, inside the edit modal -->
        <div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSectionModalLabel">Edit Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add a form for editing a package -->
                        <form id="editSectionForm" data-section-id="" enctype="multipart/form-data">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Class Section Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required placeholder="E.g Junior Secondary School 1 A">
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Section Code</label>
                                <input type="text" class="form-control" id="edit_code" name="code" required placeholder="E.g JSS1A">
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_section_description">Description</label>
                                <textarea class="form-control" id="edit_section_description" name="description" rows="3"></textarea>
                            </div>


                            <!-- Main Form Teacher (if applicable) -->
                            <div class="form-group">
                                <label for="main_form_teacher">Main Form Teacher:</label>
                                <select class="form-control" id="main_form_teacher" name="main_form_teacher">
                                    <!-- <option value=""></option> -->
                                    @foreach ($school->confirmedTeachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->profile->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <button type="submit" class="btn btn-primary mt-3">Update Section</button>
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

    @if(isset($section))

    <div class="modal fade" id="viewSectionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewSectionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSectionDetailsModalLabel">Class Section Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add content to display class section details here -->
                    <p><strong>Name:</strong> {{ $section->name }}</p>
                    <p><strong>Code:</strong> {{ $section->code }}</p>
                    <p><strong>Description:</strong> {{ $section->description }}</p>
                    <!-- Add more details as needed -->

                    <!-- You can add buttons or forms for confirming potential students and adding form teachers -->
                    <!-- For example, you can use the same modal to include confirmation and addition forms -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Add more buttons or actions as needed -->
                </div>
            </div>
        </div>
    </div>
    @endif






    </section>



@endsection

@section('scripts')
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
        var classes = @json($school->classes);
            console.log(classes);

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
            $('#createClassForm').submit(function (e) {
                e.preventDefault();

                var formData = new FormData(this);
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/school-class',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        $('#createClassModal').modal('hide');

                        // Assuming your response is a JSON object, not a string
                        // var newRow = '<tr>' +
                        //     '<td>#</td>' +
                        //     '<td><a>' + response.name + '</a><br/><small>Created ' + response.created_at + '</small></td>' +
                        //     '<td><ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="' + response.picture_url + '"></li></ul></td>' +
                        //     '<td>' + response.code + ' </td>' +
                        //     '<td>' + 0 + '</td>' +
                        //     '<td>' + 0 + '</td>' +
                        //     '<td>' + response.description + '</td>' +
                            
                        //     '<td class="project-actions text-right">' +
                        //     '<a class="btn btn-primary btn-sm" href="#"><i class="fas fa-folder"></i> View</a>' +
                        //     '<a class="btn btn-info btn-sm edit-package-btn" data-package-id="' + response.id + '" href="#"><i class="fas fa-pencil-alt"></i> Edit</a>' +
                        //     '<a class="btn btn-danger btn-sm delete-package-btn" data-package-id="' + response.id + '" data-picture="' + response.picture_path + '" href="#"><i class="fas fa-trash"></i> Delete</a>' +
                        //     '</td>' +
                        //     '</tr>';

                        // $('.table tbody').append(newRow);

                        // Add success message to the .message div
                        $('.message').removeClass('alert-danger').addClass('alert-success').html('Class created successfully.').show();

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




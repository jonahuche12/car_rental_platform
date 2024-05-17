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
    .term-card {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .term-header {
        padding: 10px 20px;
        cursor: pointer;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ccc;
        border-radius: 5px 5px 0 0;
    }

    .term-name {
        font-weight: bold;
    }

    .term-header:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }


    /* Additional styling as needed */
</style>

@endsection

@section('content')
@include('sidebar')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#createAssessmentModal">Create New Assessment</button>
            </div>
            <section class="content">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <div class="d-flex align-items-center">
                            <img alt="Avatar" class="table-avatar rounded-circle mr-3" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px;">
                            <h3 class="card-title mb-0">{{ $school->name }} - <small><b>{{ $class_section->name }} - {{$course->name}}</b></small></h3>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="message alert alert-success mb-0" style="display:none"></div>
                        @foreach ($academicSessions->sortByDesc('created_at') as $academicSession)
                    <div class="mb-3 p-3 border-bottom">
                        <h4 class="badge 
                            @if($academicSession->id > $school->academicSession->id)
                                badge-danger
                            @elseif($academicSession->id < $school->academicSession->id)
                                badge-warning
                            @else
                                badge-primary
                            @endif">Academic Session: {{ $academicSession->name }}
                        </h4>
                        
                        @foreach ($academicSession->terms->sortByDesc('created_at') as $term)
                            @php
                                $sortedassessments = $term->assessments->sortByDesc('created_at');
                                $termColorClass = '';
                                if ($term->id > $school->term->id) {
                                    $termColorClass = 'bg-success';
                                } elseif ($term->id < $school->term->id) {
                                    $termColorClass = 'bg-tertiary';
                                } else {
                                    $termColorClass = 'bg-primary';
                                }
                            @endphp
                            <div class="term-card border rounded mb-3 ml-3">
                                <div class="term-header {{ $termColorClass }} p-2" data-toggle="collapse" data-target="#term-{{ $term->id }}" aria-expanded="true" aria-controls="term-{{ $term->id }}">
                                    <span class="term-name">{{ $term->name }}</span>
                                    <span class="badge bg-primary">{{ $sortedassessments->count() }}</span>
                                </div>
                                    <div id="term-{{ $term->id }}" class="collapse" aria-labelledby="term-header-{{ $term->id }}">
                                        <div class="card-body p-0">
                                            <!-- Your nested table and content for this term goes here -->
                                            <div class="collapse table-responsive" id="term-{{ $term->id }}">
                                                <table class="table table-striped nested-table bg-light">
                                                    <!-- Table header -->
                                                    <thead class="bg-dark text-light">
                                                        <tr>
                                                            <th style="width: 1%">#</th>
                                                            <th>Assessment Name</th>
                                                            <th class="text-center">Description</th>
                                                            <th class="text-center">Complete Score</th>
                                                            <th>Due Date</th>
                                                            <th style="width: 25%" class="text-center"></th>
                                                        </tr>
                                                    </thead>
                                                    <!-- Table body -->
                                                    <tbody>

@foreach ($sortedassessments as $assessment)

    <tr class="">
        <td>{{ $loop->iteration }}</td>
        <td style="width: 19%">
            <a class="text-info" data-toggle="collapse" href="#assessment-{{ $assessment->id }}" role="button" aria-expanded="false" aria-controls="assessment-{{ $assessment->id }}">
                {{ $assessment->name }} <i class="fas fa-chevron-down"></i>
            </a>
        </td>
        <td style="width: 23%" class="text-center">{{ $assessment->description }}</td>
        <td style="5%" class="text-center">{{ $assessment->complete_score }}</td>
        <td class="project_progress text-center" style="width: 10%">
            <small>{{ $assessment->due_date }}</small>
        </td>
        <td class="project-actions text-right">
            <div class="btn-group">
                @if (!$assessment->archived)
                    <button type="button" class="btn btn-sm btn-outline-secondary edit-assessment-btn" data-assessment-id="{{ $assessment->id }}" data-toggle="tooltip" data-placement="top" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary archive-assessment-btn" data-assessment-id="{{ $assessment->id }}" data-assessment-name="{{ $assessment->name }}" data-toggle="tooltip" data-placement="top" title="Archive">
                        <i class="fas fa-archive"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-assessment-btn" data-assessment-id="{{ $assessment->id }}" data-toggle="tooltip" data-placement="top" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                    @if(!$assessment->use_in_final_result)
                        <button type="button" class="btn btn-sm btn-outline-warning result-assessment-btn" data-assessment-id="{{ $assessment->id }}" data-assessment-name="{{ $assessment->name }}" data-toggle="tooltip" data-placement="top" title="Final Result">
                            <i class="fas fa-chart-line"></i> <!-- Use appropriate icon for not used in final result -->
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-success result-assessment-btn" data-assessment-id="{{ $assessment->id }}" data-assessment-name="{{ $assessment->name }}" data-toggle="tooltip" data-placement="top" title="Used in final result">
                            <i class="fas fa-chart-line"></i> <!-- Use appropriate icon for used in final result -->
                        </button>
                    @endif
                @endif
            </div>
        </td>
    </tr>
<tr>
    <td colspan="6">
        <div class="collapse" id="assessment-{{ $assessment->id }}">
            <table class="table table-striped nested-table bg-light">
                <thead class="bg-primary text-light">
                    <tr>
                        <th>#</th>
                        <th>Student Full Name</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @if($assessment->academicSession->id == $school->academicSession->id)
                        @foreach ($assessment->course->students as $student)
                            @if ($student->userClassSection && $student->userClassSection->name == $assessment->class_section->name)
                                <tr>
                                    <td>{{ $loop->iteration }} </td>
                                    <td>{{ $student->profile->full_name }}</td>
                                    <td>
                                        <p id="message-{{ $student->id }}-{{$assessment->id}}" class="span-message text-success" style="display:none"></p>
                                        <p id="error-{{ $student->id }}-{{$assessment->id}}" class="span-error text-danger" style="display:none"></p>
                                        @php
                                            $grade = $student->getGradeForassessment($assessment->id);
                                        @endphp
                                        @if ($grade)
                                            <div id="grade-display-{{ $student->id }}-{{ $assessment->id }}">
                                                <span class="current-score">{{ $grade->score }}</span>
                                                @if (!$assessment->archived)
                                                    <button class="btn btn-sm btn-outline-primary edit-score" data-student-id="{{ $student->id }}" data-assessment-id="{{ $assessment->id }}"><i class="fas fa-edit"></i></button>
                                                @endif
                                            </div>
                                            <div id="edit-grade-form-{{ $student->id }}-{{ $assessment->id }}" style="display:none;">
                                                <form action="" id="gradeForm-{{ $student->id }}-{{ $assessment->id }}" method="POST" class="form-inline grade-form">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value ="{{ $student->id }}">
                                                    <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $assessment->course->id }}">
                                                    <input type="number" class="form-control mr-2" style="max-width: 100px;" max="999" name="score" placeholder="Score" required>
                                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                                </form>
                                            </div>
                                        @else
                                            <div id="edit-grade-form-{{ $student->id }}-{{ $assessment->id }}" style="">
                                                <form action="" id="gradeForm-{{ $student->id }}-{{ $assessment->id }}" method="POST" class="form-inline grade-form">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $assessment->course->id }}">
                                                    <input type="number" class="form-control mr-2" style="max-width: 100px;" max="999" name="score" placeholder="Score" required>
                                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $percentage = $grade ? \App\Models\Grade::calculatePercentage($grade->score, $assessment->complete_score ?? 0) : 0;
                                        @endphp
                                        {{ $percentage }}%
                                    </td>
                                    <td>
                                        @php
                                            $grade = \App\Models\Grade::calculateGrade($percentage);
                                        @endphp
                                        {{ $grade }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        @foreach ($assessment->grades as $grade)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $grade->student->profile->full_name }}</td>
                                <td>{{ $grade->score }}</td>
                                <td>
                                    @php
                                        $percentage = \App\Models\Grade::calculatePercentage($grade->score, $assessment->complete_score ?? 0);
                                    @endphp
                                    {{ $percentage }}%
                                </td>
                                <td>
                                    @php
                                        $grade = \App\Models\Grade::calculateGrade($percentage);
                                    @endphp
                                    {{ $grade }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </td>
</tr>
@endforeach
</tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>




        <!-- Modal for Creating a New Class Section -->
        <div class="modal fade" id="createAssessmentModal" tabindex="-1" role="dialog" aria-labelledby="createAssessmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="createAssessmentModalLabel">Create New <b>{{$course->name}} </b> Assessment for <b><span id="class-name">{{$class_section->name}}</span></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="assessment-message alert alert-success" style="display:none"></div>
                    <div class="assessment-error alert alert-danger" style="display:none"></div>
                    <div class="modal-body">
                        <!-- Form for Creating a New Class Section -->
                        <form action="{{ route('create_assessment') }}" id="createAssessmentForm" method="POST">
                            @csrf
                            <!-- Class Section Name -->
                            <div class="form-group">
                                <label for="section_name">Assessment Title:</label>
                                <input type="text" class="form-control" id="section_name" name="name" placeholder="E.g {{$course->code }} First assessment" required>
                            </div>
                            <div class="form-group">
                                <label for="complete_score">Complete Score:</label>
                                <input type="number" class="form-control" id="complete_score" name="complete_score" placeholder="E.g 100" required>
                            </div>
                            <input type="hidden" class="form-control" id="course_id" name="course_id" value="{{$course->id}}" required>
                            <input type="hidden" class="form-control" id="section_name" name="class_section_id" value="{{$classSectionId}}" required>
                            <div class="form-group">
                                <label for="section_name">Due Date:</label>
                                <input type="date" class="form-control" id="section_name" name="due_date" required>
                            </div>
                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <!-- Button to Submit the Form -->
                            <button type="submit" class="btn btn-primary">Create Assessment</button>
                        </form>
                    </div>
                    <!-- Additional Modal Footer or Content as needed -->
                </div>
            </div>
        </div>

        <!-- Add this code within your Blade template, inside the edit modal -->
        <div class="modal fade" id="editAssessmentModal" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="editSectionModalLabel">Edit Assessment <span id='assessment_name'></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="assessment-message alert alert-success" style="display:none"> </div>
                    <div class="assessment-error alert alert-danger" style="display:none"> </div>
                    <div class="modal-body">
                        <!-- Add a form for editing a package -->
                        <form id="editAssessmentFrom" data-section-id="" enctype="multipart/form-data">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">assessment Title</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required placeholder="E.g Reproductive system">
                            </div>
                            <div class="form-group">
                                <label for="complete_score">Complete Score:</label>
                                <input type="number" class="form-control" id="complete_score" name="complete_score" placeholder="E.g 100" required>
                            </div>
                           
                            <input type="hidden" class="form-control" id="course_id" name="course_id" placeholder="" value="{{$course->id}}" required>


                            <input type="hidden" class="form-control" id="section_name" name="class_section_id" placeholder="" value="{{$classSectionId}}" required>

                            <div class="form-group">
                                <label for="section_name">Due Date:</label>
                                <input type="date" class="form-control" id="section_name" name="due_date"  required>
                            </div>
                            

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_section_description">Description</label>
                                <textarea class="form-control" id="edit_section_description" name="description" rows="3"></textarea>
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
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <div class="assessment-message alert alert-success" style="display:none"> </div>
                        <div class="assessment-error alert alert-danger" style="display:none"> </div>
                    <div class="modal-body">
                        Are you sure you want to delete this assessment? You can only delete assessments with no records yet
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="archiveConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="archiveConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header  bg-primary text-light">
                        <h5 class="modal-title bg-primary text-light" id="archiveConfirmationModalLabel">Archive assessment Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="archive-message alert alert-success" style="display:none"> </div>
                    <div class="archive-error alert alert-danger" style="display:none"> </div>
                    <div class="modal-body">
                        Are you sure you want to archive this assessment <b><span class="assessment-name badge badge-warning" ></span></b> ? This action cannot be undone and you won't be able to edit student scores after archiving.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmArchiveBtn">Archive</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="resultConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="resultConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="resultConfirmationModalLabel">Use in Final Result Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="result-message alert alert-success" style="display:none"></div>
                    <div class="result-error alert alert-danger" style="display:none"></div>
                    <div class="modal-body">
                        Are you sure you want to <span id="action"></span> <b><span class="assessment-name badge badge-warning"></span></b> while compiling the student results?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmResultBtn">Continue</button>
                    </div>
                </div>
            </div>
        </div>



    </section>



@endsection

@section('scripts')

<script>
    $(document).ready(function(){
        // Collapse all assessments' students except the first one initially
        $('.collapse[id^="assessment-"]').not(':first').collapse('hide');

        // Handle collapse toggle
        $('.collapse[id^="assessment-"]').on('show.bs.collapse', function () {
            // Hide other expanded assessments' students
            $('.collapse[id^="assessment-"]').not($(this)).collapse('hide');
        });
    });
</script>

<script>
     var assessments = @json($assessments);
        
        $(document).ready(function() {
            // Handle click event for dynamically added edit score icons
            $(document).on('click', '.edit-score', function() {
                var studentId = $(this).data('student-id');
                var assessmentId = $(this).data('assessment-id');

                // Toggle the display of the current score and the edit form for the clicked student
                $('#grade-display-' + studentId + '-' + assessmentId).toggle();
                $('#edit-grade-form-' + studentId + '-' + assessmentId).toggle();
            });
        });



        $(document).ready(function() {
            $('#createAssessmentForm').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                
                var formData = $(this).serialize(); // Serialize form data
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Send AJAX request
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Handle success response
                        $('.assessment-message').text(response.message).show();
                        $('.assessment-error').hide();
                        setTimeout(function() {
                            $('.assessment-message').fadeOut();
                        $('#createAssessmentModal').modal('hide');
                        location.reload()
                        }, 3000); // 3 seconds
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.log(xhr.responseText)
                        var errorMessage = xhr.responseJSON.error;
                        $('.assessment-error').text(errorMessage).show();
                        $('.assessment-message').hide();
                        setTimeout(function() {
                            $('.assessment-error').fadeOut();
                        }, 3000); // 3 seconds
                    }
                });
            });
        });


        $(document).on('click', '.edit-assessment-btn', function () {
            var assessmentId = $(this).data('assessment-id');
            var assessmentObj = assessments.find(function (pkg) {
                return pkg.id == assessmentId;
            });

            // Set values for name and description fields
            $('#editAssessmentFrom input[name="name"]').val(assessmentObj.name);
            $('#editAssessmentFrom textarea[name="description"]').val(assessmentObj.description);

            $('#editAssessmentFrom input[name="complete_score"]').val(assessmentObj.complete_score);

            // Check if the due date is null
            if (assessmentObj.due_date) {
                // Format the due date
                var dueDate = new Date(assessmentObj.due_date);
                var timeZoneOffset = dueDate.getTimezoneOffset() * 60000; // Convert minutes to milliseconds
                var adjustedDueDate = new Date(dueDate.getTime() - timeZoneOffset);
                var formattedDueDate = adjustedDueDate.toISOString().split('T')[0]; // Convert to yyyy-mm-dd format
                $('#editAssessmentFrom input[name="due_date"]').val(formattedDueDate);
            } else {
                // If due date is null, clear the input field
                $('#editAssessmentFrom input[name="due_date"]').val('');
            }

            // Set the assessment ID as a data attribute in the form
            $('#editAssessmentFrom').attr('data-assessment-id', assessmentId);

            // Show the modal
            $('#editAssessmentModal').modal('show');
        });



    // Submit form using AJAX for the edit form
    $('#editAssessmentFrom').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var assessmentId = $(this).data('assessment-id');
        console.log(assessmentId)
        var url = '/assessment/' + assessmentId + '/edit';
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
            success: function(response) {
                // Display success message
                $('.assessment-message').text(response.message).show();
                $('.assessment-error').hide();
                
                // Hide the message and close the modal after 3 seconds
                setTimeout(function() {
                    $('.assessment-message').fadeOut();
                    $('#editAssessmentModal').modal('hide');
                    window.location.reload(); // Reload the page
                }, 3000); // 3 seconds
            },


            error: function(xhr, status, error) {
                console.log(xhr.responseText)
                // Handle error response
                var errorMessage = xhr.responseJSON.error;
                $('.assessment-error').text(errorMessage).show();
                $('.assessment-message').hide();
                setTimeout(function() {
                    $('.assessment-error').fadeOut();
                }, 3000); // 3 seconds
            }
        });
    });

    // Handle click event on the "Delete" button for class section
    $(document).on('click', '.delete-assessment-btn', function () {
        var assessmentId = $(this).data('assessment-id');

        // Show the confirmation modal
        $('#confirmationModal').modal('show');

        // Store the sectionId in the modal's data attribute
        $('#confirmationModal').data('assessment-id', assessmentId);
    });


    // Handle click event on the "Delete" button inside the modal
    $(document).on('click', '#confirmDelete', function () {
        var assessmentId = $('#confirmationModal').data('assessment-id');

        // Make an AJAX request to delete the class section
        $.ajax({
            type: 'DELETE',
            url: '/assessment/' + assessmentId,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Display success message
                $('.assessment-message').text(response.message).show();
                $('.assessment-error').hide();
                
                // Hide the message and close the modal after 3 seconds
                setTimeout(function() {
                    $('.assessment-message').fadeOut();
                    $('#confirmationModal').modal('hide');
                    window.location.reload(); // Reload the page
                }, 3000); // 3 seconds
            },


            error: function(xhr, status, error) {
                console.log(xhr.responseText)
                // Handle error response
                var errorMessage = xhr.responseJSON.error;
                $('.assessment-error').text(errorMessage).show();
                $('.assessment-message').hide();
                setTimeout(function() {
                    $('.assessment-error').fadeOut();
                }, 3000); // 3 seconds
            }
        });

        // Hide the confirmation modal after processing
        // $('#confirmationModal').modal('hide');
    });

</script>

<script>
    $(document).ready(function() {
        // Use event delegation to handle form submission for dynamically added forms
        $(document).on('submit', '.grade-form', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            
            var formData = $(this).serialize(); // Serialize form data
            console.log(formData)
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var studentId = $(this).find('input[name="user_id"]').val(); // Get student ID
            var assessmentId = $(this).find('input[name="assessment_id"]').val(); // Get assessment ID

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '{{ route("saveGradeAssessment") }}',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Replace the form with the score
                    var score = response.score;
                    var scoreElement = $('<span>').text(score);
                    $('#edit-grade-form-' + studentId + '-' + assessmentId).html(scoreElement); // Use html() to replace the content

                    // Display success message for the particular student
                    $('#message-' + studentId + '-' + assessmentId).text("Updated").show();
                    $('#error-' + studentId + '-' + assessmentId).hide();
                    setTimeout(function() {
                        $('#message-' + studentId + '-' + assessmentId).fadeOut();
                    }, 3000); // 3 seconds
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText)
                    // Handle error response for the particular student
                    var errorMessage = xhr.responseJSON.error;
                    $('#error-' + studentId + '-' + assessmentId).text(errorMessage).show();
                    $('#message-' + studentId + '-' + assessmentId).hide();
                    setTimeout(function() {
                        $('#error-' + studentId + '-' + assessmentId).fadeOut();
                    }, 3000); // 3 seconds
                }
            });
        });
    });
    $(document).ready(function() {
        $('.archive-assessment-btn').click(function() {
            var assessmentId = $(this).data('assessment-id');
            var assessmentName = $(this).data('assessment-name');
            var model = 'Assessment';
            
            // Show archive confirmation modal
            $('#archiveConfirmationModal').modal('show');
            $('.assessment-name').text(assessmentName);
            
            // Handle click on modal's "Archive" button
            $('#confirmArchiveBtn').click(function() {
                // Send AJAX request to archive the assessment
                $.ajax({
                    type: 'POST',
                    url: '/archive/' + model + '/' + assessmentId,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Display success message on the modal
                        $('.archive-message').text(response.message).show();
                        $('.archive-error').hide();
                        
                        // Hide the modal after 3 seconds and reload the page
                        setTimeout(function() {
                            $('#archiveConfirmationModal').modal('hide');
                            location.reload();
                        }, 3000); // 3 seconds
                    },
                    error: function(xhr, status, error) {
                        // Handle error response for the particular student
                        var errorMessage = xhr.responseJSON.error;
                        $('.archive-error').text(errorMessage).show();
                        $('.archive-message').hide();
                    }
                });
            });
        });
    });

    $('.result-assessment-btn').click(function() {
    var assessmentId = $(this).data('assessment-id');
    var currentStatus = $(this).hasClass('btn-outline-warning') ? 0 : 1; // 0 for not used, 1 for used
    var action = currentStatus ? 'remove ' : 'use ';
    var model = 'Assessment';

    // Show result confirmation modal
    $('#resultConfirmationModal').modal('show');
    $('.assessment-name').text($(this).data('assessment-name'));
    $('#action').text(action); // Set the action text

    $('#confirmResultBtn').off('click').on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/toggle-assignment/' + model + '/' + assessmentId,
            data: {
                _token: '{{ csrf_token() }}', // Add CSRF token if using Laravel
                currentStatus: currentStatus
            },
            success: function(response) {
                if (response.message) {
                    $('.result-message').text(response.message).show();
                    $('.result-error').hide();
                    
                    // Hide the modal after 3 seconds and reload the page
                    setTimeout(function() {
                        $('#resultConfirmationModal').modal('hide');
                        location.reload();
                    }, 3000); // 3 seconds
                } else {
                    // Show error message
                    $('.result-error').text('Failed to update assessment status').show();
                    $('.result-message').hide();
                }
            },
            error: function(xhr, status, error) {
                // Show error message
                console.log(xhr.responseText);
                var errorMessage = xhr.responseJSON.error;
                $('.result-error').text(errorMessage).show();
                $('.result-message').hide();
            }
        });
    });
});



    $(document).ready(function() {
        $('.toggle-nested-table').on('click', function() {
            // Get the data-assessment-id attribute from the button
            var assessmentId = $(this).data('assessment-id');

            // Toggle the display of the corresponding nested table
            $('.nested-table[data-assessment-id="' + assessmentId + '"]').toggle();

            // Toggle the icon between plus and minus
            var icon = $('.toggle-icon', this);
            icon.toggleClass('fa-chevron-up fa-chevron-down');
        });
    });
</script>




@endsection




@extends('layouts.app')

@section('title', "Central School system - Manage Schools")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "Manage School")

@section('page_title')
 {{ $school->name }}
@endsection

@section('style')
<style>
    /* Define a background color for expanded course items */
    .course-item.expanded {
        background-color: #e9ecef; /* Light grey background color for expanded items */
    }

    .custom-list-group {
        height: 400px; /* Fixed height */
        overflow-y: auto; /* Enable vertical scrolling */
        border: 1px solid #ddd; /* Add border for clarity */
        border-radius: 8px; /* Rounded corners */
        background-color: #f8f9fa; /* Light background color */
    }

    .custom-list-group .list-group-item {
        border-radius: 0; /* Remove default border-radius */
    }

    .custom-list-group .list-group-item:first-child {
        border-top-left-radius: 8px; /* Rounded top corners for the first item */
        border-top-right-radius: 8px;
    }

    .custom-list-group .list-group-item:last-child {
        border-bottom-left-radius: 8px; /* Rounded bottom corners for the last item */
        border-bottom-right-radius: 8px;
    }

    .custom-list-group .list-group-item:hover {
        background-color: #e9ecef; /* Light background color on hover */
    }

    .custom-list-group .list-group-item.active {
        background-color: #007bff; /* Active item background color */
        color: #fff; /* Text color for active item */
    }

    .custom-list-group .list-group-item.list-group-item-info {
        background-color: #007bff; /* Header background color */
        color: #fff; /* Text color for header */
        font-weight: bold; /* Bold font for header */
        border: none; /* Remove border for header */
    }

    /* Style for details section */
    .details-section {
        max-height: 300px; /* Max height for details section */
        overflow-y: auto; /* Enable vertical scrolling for details */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        margin-top: 10px;
    }
</style>



@endsection

@section('content')
@include('sidebar')
<section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <!-- Admins -->
          <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'admin']) }}" class="text-decoration-none">
 

                  <div class="info-box bg-info">
                      <span class="info-box-icon bg-info elevation-1">
                          <div class="inner mt-2">
                              <i class="fas fa-users"></i>
                          </div>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Admins</span>
                          <span class="info-box-number"><h5> {{$school->getConfirmedAdmins()->count()}}</h5></span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Teachers -->
          <div class="col-12 col-sm-6 col-md-4">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'teachers']) }}" class="text-decoration-none">

                  <div class="info-box bg-info mb-3">
                      <span class="info-box-icon bg-purple elevation-1">
                          <div class="inner mt-2">
                              <i class="fas fa-chalkboard-teacher"></i>
                          </div>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Teachers</span>
                          <span class="info-box-number"><h5> {{$school->teachers()->count()}}</h5></span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Students -->
          <div class="col-12 col-sm-6 col-md-4">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'students']) }}" class="text-decoration-none">

                  <div class="info-box bg-info mb-3">
                      <span class="info-box-icon bg-success elevation-1">
                          <i class="fas fa-user-graduate"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Students</span>
                          <span class="info-box-number"> {{ $school->students->count() }} </span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Classes -->
          <div class="col-12 col-sm-6 col-md-4">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'classes']) }}" class="text-decoration-none">

                  <div class="info-box bg-info mb-3">
                      <span class="info-box-icon bg-secondary elevation-1">
                          <i class="fas fa-school"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Classes</span>
                          <span class="info-box-number">{{ $school->classes->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Events -->
          <div class="col-12 col-sm-6 col-md-4">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'events']) }}" class="text-decoration-none">
  
                  <div class="info-box bg-info mb-3">
                      <span class="info-box-icon bg-warning elevation-1">
                          <i class="fas fa-calendar-alt"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Events</span>
                          <span class="info-box-number">{{ $school->events->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Curriculum -->
          <div class="col-12 col-sm-6 col-md-4">
              <a href="{{ route('manage-courses', ['schoolId' => $school->id]) }}" class="text-decoration-none">
 
                  <div class="info-box bg-info mb-3">
                      <span class="info-box-icon bg-black elevation-1">
                          <i class="fas fa-book"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Courses</span>
                          <span class="info-box-number">{{ $school->courses->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>
      </div>



        <!-- /.row -->

        <div class="row">
          
          <!-- /.col -->
          <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Course Grade Analysis</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- Canvas for Grade Distribution Chart -->
                    <canvas id="gradeDistributionChart" width="800" height="400"></canvas>
                    <p id="gradeLabel"></p>
                </div>
                <!-- /.col -->
                <div class="col-md-4">
    <div class="list-group custom-list-group">
        <!-- List of courses -->
        <h5 class="list-group-item list-group-item-info text-center">Courses</h5>
        @foreach($school->courses as $course)
            <div class="course-item">
                <!-- Course name link (click to show/hide details) -->
                <a href="#" class="list-group-item list-group-item-action graph-option course-link custom-list-item" data-option="{{ $course->code }}" data-class="" data-assessment="" data-academic_session="" data-term="">
                    <strong>{{ $course->name }}</strong>
                </a>
                <!-- Details section (initially hidden) -->
                <div class="details-section p-2 ml-2" style="display: none;">
                    <!-- Display classes as a select -->
                    <select class="class-select form-control" data-course="{{ $course->code }}">
                        <option value="">Select Class</option>
                        @foreach($course->classes() as $class)
                            <option value="{{ $class->id }}">{{ $class->code }}</option>
                        @endforeach
                    </select>
                    <br>
                    <!-- Display assessment types -->
                    <div class="mt-0">
                        <p><strong>Assessment Types:</strong></p>
                        <label><input type="radio" class="assessment-type" name="assessment_type" value="assignment_id"> Assignment</label><br>
                        <label><input type="radio" class="assessment-type" name="assessment_type" value="assessment_id"> Assessment</label><br>
                        <label><input type="radio" class="assessment-type" name="assessment_type" value="exam_id"> Exam</label>
                    </div>

                    <!-- Display academic sessions and terms -->
                    <div class="mt-3">
                        <strong>Academic Session:</strong><br>
                        <select class="form-control academic-session-select" data-course="{{ $course->code }}">
                            <option value="">Select Academic Session</option>
                            @foreach($academicSessions as $academicSession)
                                <option value="{{ $academicSession->id }}">{{ $academicSession->name }}</option>
                            @endforeach
                        </select>
                        <br>
                        <strong>Term:</strong><br>
                        <select class="form-control term-select" data-academic-session="">
                            <option value="">Select Term</option>
                            <!-- Terms will be dynamically populated based on selected academic session -->
                        </select>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>




                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <div class="row">
                <!-- Add any additional footer content -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-footer -->
    </div>
    <!-- /.card -->
</div>

        </div>
        <!-- /.row -->

      </div><!--/. container-fluid -->
    </section>


@endsection

@section('scripts')

<script>
  
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize the Chart.js instance
        var ctx = document.getElementById('gradeDistributionChart').getContext('2d');

        ctx.canvas.width = 720; // Set the width of the canvas
    ctx.canvas.height = 540; // Set the height of the canvas
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    label: 'Grade Distribution',
                    data: [],
                    backgroundColor: [
                        '#5E07AE',   // A+ (Purple)
                        '#9743E4',   // A (Light Purple)
                        '#E443E0',   // B (Red)
                        '#E44390',   // C (Orange)
                        '#E49743',   // D (Yellow)
                        '#E0E443',   // E (Teal)
                        '#E44743'    // F (Light Grey)
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Grade Distribution' // Default title
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.labels[tooltipItem.index] || '';
                            var value = data.datasets[0].data[tooltipItem.index] || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        });

      // Function to update chart based on selected parameters
function updateChart(courseCode, classId, assessmentType, academicSessionId, termId, courseName, myChart) {
    $.ajax({
        type: 'GET',
        url: `/course/${courseCode}/gradedistribution`,
        data: {
            classId: classId,
            assessmentType: assessmentType,
            academicSessionId: academicSessionId,
            termId: termId
        },
        success: function (response) {
    console.log(response)
    // Calculate the sum of all values in gradeDistribution
    var sumOfValues = Object.values(response.gradeDistribution).reduce((a, b) => a + b, 0);

    
    if (sumOfValues === 0) {
        // No data available, hide the chart and display a message to the user
        $("#gradeDistributionChart").hide();
        $("#gradeLabel").css({
            'background-color': '#f0f0f0', // Background color
            'padding': '10px',             // Padding
            'border-radius': '5px',        // Border radius
            'text-align': 'center',        // Text alignment
            'margin-top': '10px',          // Top margin
            'font-weight': 'bold'          // Font weight
        }).text("No data available for "+ response.label);
    }else {
        // Data available, show the chart and update it with the received grade distribution
        $("#gradeDistributionChart").show();
        // Display insights with styling
        var insightsHTML = '<div class="insights-container">';
        insightsHTML += '<p class="total-students">Total Students: ' + response.totalStudents + '</p>';
        insightsHTML += '<span class="grade-counts">';
        
        // Grade labels with respective colors
        var gradeColors = {
            'A+': '#5E07AE',   // Purple
            'A': '#9743E4',    // Light Purple
            'B': '#E443E0',    // Red
            'C': '#E44390',    // Orange
            'D': '#E49743',    // Yellow
            'E': '#E0E443',    // Teal
            'F': '#E44743'     // Light Grey
        };
        
        Object.keys(response.gradeCounts).forEach(function(grade) {
            insightsHTML += '<span class="grade-box text-white ml-2 p-2" style="background-color: ' + gradeColors[grade] + '; border-radius:9px;">';
            insightsHTML += '<span>' + grade + '</span>: <span>' + response.gradeCounts[grade] + '</span>';
            insightsHTML += '</span>';
        });
        insightsHTML += '</span></div>';

        $("#gradeLabel").html(insightsHTML);
        
        myChart.data.labels = Object.keys(response.gradeDistribution);
        myChart.data.datasets[0].data = Object.values(response.gradeDistribution);
        myChart.options.title.text = response.label;
        myChart.update();
    }
},

        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            console.error('Request failed with status:', xhr.status);
            console.error(error);
        }
    });
}



        // Event listener for class select change
        $('.class-select').change(function () {
            var selectedClassId = $(this).val();
            var courseItem = $(this).closest('.course-item');
            courseItem.find('.course-link').attr('data-class', selectedClassId);
            // console.log(selectedClassId)
            triggerUpdateChart(courseItem);
        });

        // Event listener for assessment type radio button change
        $('.assessment-type').change(function () {
            var assessmentType = $(this).val();
            var courseItem = $(this).closest('.course-item');
            courseItem.find('.course-link').attr('data-assessment', assessmentType);
            // console.log(assessmentType)
            triggerUpdateChart(courseItem);
        });

        // Event listener for academic session select change
        $('.academic-session-select').change(function () {
            var selectedAcademicSessionId = $(this).val();
            var courseItem = $(this).closest('.course-item');
            // console.log(selectedAcademicSessionId)
            courseItem.find('.course-link').attr('data-academic_session', selectedAcademicSessionId);

            // Update term options based on selected academic session (if needed)
            // Example: updateTermOptions(selectedAcademicSessionId);

            triggerUpdateChart(courseItem);
        });

        // Event listener for term select change
        $('.term-select').change(function () {
            var selectedTermId = $(this).val();
            var courseItem = $(this).closest('.course-item');
            courseItem.find('.course-link').attr('data-term', selectedTermId);
            // console.log(selectedTermId)
            triggerUpdateChart(courseItem);
        });

        // Function to trigger updateChart based on updated attributes within a specific course item
        function triggerUpdateChart(courseItem) {
            var courseLink = courseItem.find('.course-link');
            var courseCode = courseLink.attr('data-option');
            var classId = courseLink.attr('data-class');
            var assessmentType = courseLink.attr('data-assessment');
            var academicSessionId = courseLink.attr('data-academic_session');
            var termId = courseLink.attr('data-term');
            var courseName = courseLink.text().trim();
            console.log(classId,assessmentType,academicSessionId,termId,courseName)

            updateChart(courseCode, classId, assessmentType, academicSessionId, termId, courseName, myChart);
        }

        // Handle click event on graph option links
        $('.graph-option').click(function (e) {
            e.preventDefault();
            var courseItem = $(this).closest('.course-item');
            var classId = courseItem.find('.class-select').val();
            var assessmentType = courseItem.find('input[name="assessment_type"]:checked').val();
            var academicSessionId = courseItem.find('.academic-session-select').val();
            var termId = courseItem.find('.term-select').val();
            var courseCode = $(this).attr('data-option');
            var courseName = $(this).text().trim();

            triggerUpdateChart(courseItem);
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Handle course link click to toggle details section visibility
        $('.course-link').click(function(e) {
            e.preventDefault();

            var currentCourseItem = $(this).parent('.course-item');
            var currentDetailsSection = $(this).next('.details-section');

            // Close all other expanded details sections except the current one
            $('.course-item.expanded').not(currentCourseItem).removeClass('expanded');
            $('.details-section').not(currentDetailsSection).slideUp();

            // Toggle visibility of details section for the clicked course
            currentDetailsSection.slideToggle();
            // Toggle expanded class on the course item
            currentCourseItem.toggleClass('expanded');
        });

        // Handle academic session select change to fetch terms dynamically
        $('.academic-session-select').change(function() {
            var academicSessionId = $(this).val();
            var termSelect = $(this).closest('.details-section').find('.term-select');

            // Clear term options if no academic session is selected
            if (!academicSessionId) {
                termSelect.html('<option value="">Select Term</option>');
                return; // Exit early if no academic session is selected
            }

            // Fetch terms based on the selected academic session
            fetch(`/terms/${academicSessionId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing options
                    termSelect.html('<option value="">Select Term</option>');
                    
                    // Populate terms select with fetched data
                    data.forEach(term => {
                        const option = $('<option>');
                        option.val(term.id).text(term.name);
                        termSelect.append(option);
                    });
                })
                .catch(error => console.error('Error fetching terms:', error));
        });
    });
</script>




@endsection






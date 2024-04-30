@extends('layouts.app')

@section('title', "Central School System - Student Results")

@section('breadcrumb1')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb2', "Results")

@section('page_title')
    {{ $school->name }}
@endsection

@section('style')
    <!-- Your styles -->
    <style>
        /* Custom styles */
        .certificate-container {
            position: relative;
            /* background-image: url('{{ asset('storage/' . $school->logo) }}'); */
            background-size: cover;
            background-position: center;
            padding: 50px;
            color: #333;
            font-family: 'Sarala','Balsamiq Sans', sans-serif;
            text-align: center;
        }

        .certificate-table {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .certificate-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .certificate-table th {
            font-weight: bold;
        }

        .certificate-table td {
            padding: 10px;
        }

        .table-responsive {
          
        }

        /* Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.3); /* Adjust the transparency as needed */
            border-radius: 10px;
        }

        /* Hidden comment form */
        .comment-form-container {
            /* display: none; */
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
@include('sidebar')
    <div class="certificate-container">
        <h1 class="certificate-title text-dark">{{ $school->name }}</h1>
        <div class="certificate-table">
            <h4 class="text-dark small-text">Result for <b>{{ $studentResults[0]->student->profile->full_name }}</b> - <em><b>{{ $studentResults[0]->academicSession->name }} - {{ $studentResults[0]->term->name }} - {{ $studentResults[0]->student->schoolClass()->name }} ({{ $studentResults[0]->student->userClassSection->name }} {{$studentResults[0] ->student->id}} )</b></em> </h4>
            <div class="table-responsive">
                <div class="overlay"></div> <!-- Overlay div -->
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Net Assignment Score</th>
                            <th>Net Assessment Score</th>
                            <th>Net Exam Score</th>
                            <th>Total Score</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $totalScores = 0;
                        $totalCourses = 0; // Initialize total courses counter
                    @endphp
                    @foreach($studentResults as $studentResult)
                        @php
                            // Exclude courses where exam score is zero from total score and count
                            if ($studentResult->exam_score > 0) {
                                $totalScores += $studentResult->total_score;
                                $totalCourses++; // Increment total courses counter
                            }
                        @endphp
                        <tr>
                            <td>{{ $studentResult->course_name }}</td>
                            <td>{{ $studentResult->assignment_score }}</td>
                            <td>{{ $studentResult->assessment_score }}</td>
                            <td>{{ $studentResult->exam_score }}</td>
                            <td>{{ $studentResult->total_score }}</td>
                            <td>{{ $studentResult->grade }}</td>
                        </tr>
                    @endforeach
                    @if($totalCourses > 0)
                        <tr>
                            <td colspan="4" style="text-align: right;">Total Average:</td>
                            <td colspan="2">{{ number_format($totalScores / $totalCourses, 2) }}</td>
                        </tr>
                    @endif

                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.certificate-table -->
        
        <!-- Horizontal Bar Chart -->
        <canvas id="gradeChart" ></canvas>
        <p class="small-text" id="student-result-insight"></p>
    </div>
    <!-- /.certificate-container -->
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const courseNames = {!! json_encode($courseNames) !!};
    const averageScores = {!! json_encode($averageScores) !!};
    const courseGrades = {!! json_encode($courseGrades) !!};

    // Define grade colors
    const gradeColors = {
        'A+': '#5E07AE',   // Purple
        'A': '#9743E4',    // Light Purple
        'B': '#E443E0',    // Red
        'C': '#E44390',    // Orange
        'D': '#E49743',    // Yellow
        'E': '#E0E443',    // Teal
        'F': '#E44743'     // Light Grey
    };

    // Chart.js configuration
    const ctx = document.getElementById('gradeChart').getContext('2d');

    const gradeChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: courseNames,
        datasets: [{
            label: 'Average Scores',
            data: Object.values(averageScores),
            backgroundColor: courseNames.map(courseName => gradeColors[courseGrades[courseName]]),
            borderColor: courseNames.map(courseName => `${gradeColors[courseGrades[courseName]]}80`),
            borderWidth: 1,
            barThickness: 60 // Adjust the width of the bars (in pixels)
        }]
    },
    options: {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var courseName = data.labels[tooltipItem.index];
                    var averageScore = data.datasets[0].data[tooltipItem.index];
                    var grade = courseGrades[courseName];
                    return `Course: ${courseName}, Average Score: ${averageScore}, Grade: ${grade}`;
                }
            }
        },
        plugins: {
            annotation: {
                annotations: courseNames.map((courseName, index) => {
                    return {
                        type: 'line',
                        mode: 'horizontal',
                        scaleID: 'y-axis-0',
                        value: index,
                        borderColor: 'black',
                        borderWidth: 1,
                        label: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            content: `Grade: ${courseGrades[courseName]}`,
                            enabled: true
                        }
                    };
                })
            }
        },
        elements: {
            rectangle: {
                barPercentage: 0.8, // 80% of available space
                categoryPercentage: 0.6 // 60% of the space in the category
            }
        }
    }
});

    
    // Calculate insights
    const maxScoreIndex = Object.values(averageScores).indexOf(Math.max(...Object.values(averageScores)));
    const minScoreIndex = Object.values(averageScores).indexOf(Math.min(...Object.values(averageScores)));
    const bestCourse = courseNames[maxScoreIndex];
    const worstCourse = courseNames[minScoreIndex];

    // Retrieve student's grade and score for best-performing course
    const bestCourseGrade = courseGrades[bestCourse];
    const bestCourseScore = averageScores[bestCourse];

    // Retrieve student's grade and score for worst-performing course
    const worstCourseGrade = courseGrades[worstCourse];
    const worstCourseScore = averageScores[worstCourse];

    // Calculate overall performance summary
    const totalAverageScore = Object.values(averageScores).reduce((acc, score) => acc + score, 0) / Object.values(averageScores).length;
    const overallGrade = calculateOverallGrade(totalAverageScore);

    // Calculate distribution of grades
    const gradeDistribution = calculateGradeDistribution(courseGrades);

    // Display insights
    $('#student-result-insight').html(`
        Overall Performance Summary:<br>
        Total Average Score: ${totalAverageScore.toFixed(2)}<br>
        Overall Grade: ${overallGrade}<br><br>
        Best-performing course: ${bestCourse} (Grade: ${bestCourseGrade}, Score: ${bestCourseScore})<br>
        Worst-performing course: ${worstCourse} (Grade: ${worstCourseGrade}, Score: ${worstCourseScore})<br><br>
        Distribution of Grades:<br>
        ${Object.entries(gradeDistribution).map(([grade, count]) => `${grade}: ${count}`).join('<br>')}
    `);

    // Function to calculate overall grade based on average score
function calculateOverallGrade(averageScore) {
    // Define grade boundaries (matching backend)
    const gradeBoundaries = {
        'A+': 80,
        'A': 70,
        'B': 60,
        'C': 50,
        'D': 40,
        'E': 30,
        'F': 0
    };

    // Determine overall grade based on average score
    for (const [grade, boundary] of Object.entries(gradeBoundaries)) {
        if (averageScore >= boundary) {
            return grade;
        }
    }
    return 'F'; // Default grade if score is below minimum boundary
}

// Function to calculate distribution of grades
function calculateGradeDistribution(courseGrades) {
    const gradeDistribution = {};
    for (const grade of Object.values(courseGrades)) {
        gradeDistribution[grade] = (gradeDistribution[grade] || 0) + 1;
    }
    return gradeDistribution;
}

});

</script>
@endsection

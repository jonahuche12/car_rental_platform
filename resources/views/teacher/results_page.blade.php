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
            <h4 class="text-dark small-text">Result for <b>{{ $compiledResults['student_info']['student_name'] }}</b> - <em><b>{{ $compiledResults['student_info']['academic_session'] }} - {{ $compiledResults['student_info']['term'] }}- {{ $compiledResults['student_info']['class_name'] }} ({{ $compiledResults['student_info']['class_section_name'] }})</b></em> </h4>
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
                            $totalAverage = 0;
                            $count = 0;
                        @endphp
                        @foreach($compiledResults as $key => $value)
                            @if(is_array($value) && array_key_exists('course_name', $value))
                                @if($value['net_exam_score'] > 0)
                                    @php
                                        $totalAverage += $value['total_score'];
                                        $count++;
                                    @endphp
                                @endif
                                <tr>
                                    <td>{{ $value['course_name'] }}</td>
                                    <td>{{ $value['net_assignment_score'] }}</td>
                                    <td>{{ $value['net_assessment_score'] }}</td>
                                    <td>{{ $value['net_exam_score'] }}</td>
                                    <td>{{ $value['total_score'] }}</td>
                                    <td>{{ $value['grade'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                        @if ($count > 0)
                            <tr>
                                <td colspan="4" style="text-align: right;">Total Average:</td>
                                <td colspan="2">{{ number_format($totalAverage / $count, 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
            <div class="comment-form-container mt-3">
                <form action="{{ route('publish_result') }}" id="publishResultForm" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            

                            <input type="text" class="form-control mb-2" id="comment" name="comment" placeholder="Teachers Remark" required>
                            <div class="invalid-feedback">
                                Please provide a comment.
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-2">Publish</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
        <!-- /.certificate-table -->
    </div>
    <!-- /.certificate-container -->
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // Script to show comment form when 'Publish Result' button is clicked
    $('#publishResultBtn').on('click', function() {
        console.log('clicked')
        $('.comment-form-container').show();
    });
});
</script>
@endsection

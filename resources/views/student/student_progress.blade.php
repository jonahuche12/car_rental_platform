@extends('layouts.app')

@section('title', "Central School System - Student Results")

@section('breadcrumb1')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb2', "Academic Progress")

@section('breadcrumb3')
    <a href="#">{{$student->profile->full_name}}</a>
@endsection


@section('style')
    <!-- Your styles -->
    <style>
           .toggle-session-container {
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 10px;
    margin-bottom: 10px; /* Adjust as needed */
}

.toggle-session {
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}

.toggle-session:hover {
    /* background-color: #f5f5f5; */
}

.toggle-session i {
    transition: transform 0.3s ease-in-out;
}

.collapsed .toggle-session i {
    transform: rotate(-90deg);
}

    </style>
    
@endsection

@section('content')
@include('sidebar')

<div class="col-md-8">
    <div class="card">
        <div class="card-header p-2">
        <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link active small-text" href="#academic" data-toggle="tab">
                <span class="d-non d-sm-inline">Progress</span>
                <i class="d-inline d-sm-non fas fa-chart-line"></i> <!-- Icon for small screens -->
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link small-text" href="#your_results" data-toggle="tab">
                <span class="d-noe d-sm-inline">Results</span>
                <i class="d-inline d-sm-non fas fa-clipboard-list"></i> <!-- Icon for small screens -->
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link small-text" href="#attendance" data-toggle="tab">
                <span class="d-noe d-sm-inline">Attendance</span>
                <i class="d-inline d-s-none fas fa-calendar-check"></i> <!-- Icon for small screens -->
            </a>
        </li>
      </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
        <div class="tab-content">
        <div class="active tab-pane" id="academic">
            <!-- Box header -->
            <div class="dropdown" style="position: relative;">
                <button class="btn btn-link dropdown-toggle" type="button" id="academicDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{$student->profile->full_name}} Study Connects: <i class="fas fa-graduation-cap"></i>{{ $student->profile->school_connects ?? 0}}
                </button>
                <div class="dropdown-menu" aria-labelledby="academicDropdown">
                    <a class="dropdown-item bg-purple text-white" href="#" data-toggle="modal" data-target="#studyConnectModal">
                    <i class="fas fa-graduation-cap"></i> Top Up School Connects
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title small-text">Courses Offered By <b>{{$student->profile->full_name}}</b></h3>
                        </div>
                        @php
                            $school = $student->school;
                            $school_session = $school->academicSession;
                            $school_term = $school->term;
                        @endphp
                        <!-- ./card-header -->
                        <div class="card-body">
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="small-text">#</th>
                                            <th class="small-text">Course</th>
                                            <th class="small-text">Teacher</th>
                                            <th class="small-text">Compulsory</th>
                                            <th class="small-text">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->student_courses as $course)
                                        <tr class="course-item">
                                            <td class="small-text">{{$loop->iteration}}</td>
                                            <td class="small-text">
                                                <button class="btn btn-link small-text course-link" type="button" data-toggle="collapse" data-target="#collapse_{{$course->id}}" aria-expanded="false" aria-controls="collapse_{{$course->id}}">
                                                    {{$course->name}}
                                                </button>
                                            </td>
                                            <td class="small-text">
                                                @if($teacher = $course->getTeacherForClassSection($student->class_section_id))
                                                {{$teacher->profile->full_name}}
                                                @endif
                                            </td>
                                            <td class="small-text">
                                                @if($course->compulsory)
                                                <span class="badge badge-success">Compulsory</span>
                                                @else
                                                <span class="badge badge-warning">Elective</span>
                                                @endif
                                            </td>
                                            <td class="small-text">{{$course->description}}</td>
                                        </tr>
                                        <tr>
                                            <td class="small-text" colspan="5">
                                                <div id="collapse_{{$course->id}}" class="collapse" aria-labelledby="heading_{{$course->id}}" data-parent="#accordion_{{$course->id}}">
                                                    <div id="accordion_{{$course->id}}">
                                                        @foreach($school_session->terms->sortByDesc('created_at') as $term)
                                                        @if($term->name == $school_term->name)
                                                        <div class="card">
                                                            <div class="card-header" id="heading_{{$term->id}}_{{$course->id}}">
                                                                <h2 class="mb-0 small-text">
                                                                    <button class="btn btn-link  small-text" type="button" data-toggle="collapse" data-target="#collapse_{{$term->id}}_{{$course->id}}" aria-expanded="true" aria-controls="collapse_{{$term->id}}_{{$course->id}}">
                                                                        {{$course->name}}
                                                                        <span class="badge badge-warning"> <b> <span class="">{{$school_session->name ?? ''}}</b>
                                                                        <b class="">{{$term->name}}</b></span>

                                                                    </button>
                                                                </h2>
                                                            </div>
                                                            <div id="collapse_{{$term->id}}_{{$course->id}}" class="collapse" aria-labelledby="heading_{{$term->id}}_{{$course->id}}" data-parent="#accordion_{{$course->id}}">
                                                                <div class="card-body">
                                                                    <h6 class="badge badge-warning small-text">Assignments</h6>
                                                                    @include('partials.assignment_table', ['grades' => $student->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body smalll-text"> 
                                                                    <h6 class="badge badge-info">Assessments</h6>
                                                                    @include('partials.assessment_table', ['grades' => $student->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body small-text">
                                                                    <h6 class="badge badge-primary">Exams</h6>
                                                                    @include('partials.exam_table', ['grades' => $student->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                            </div>
                                                            @php
                                                                $total_assignment_score = 0;
                                                                $num_assignments = 0;
                                                                $total_assessment_score = 0;
                                                                $num_assessments = 0;
                                                                $total_exam_score = 0;
                                                                $num_exams = 0;

                                                                foreach($student->grades->where('course_id', $course->id) as $grade){
                                                                    if($grade->assignment && $grade->assignment->academicSession->id == $school_session->id && $grade->assignment->term->name == $term->name){
                                                                        $total_assignment_score += $grade->score;
                                                                        $num_assignments++;
                                                                    }
                                                                    if($grade->assessment && $grade->assessment->academicSession->id == $school_session->id && $grade->assessment->term->name == $term->name){
                                                                        $total_assessment_score += $grade->score;
                                                                        $num_assessments++;
                                                                    }
                                                                    if($grade->exam && $grade->exam->academicSession->id == $school_session->id && $grade->exam->term->name == $term->name){
                                                                        $total_exam_score += $grade->score;
                                                                        $num_exams++;
                                                                    }
                                                                }

                                                                $average_assignment_score = ($num_assignments > 0) ? $total_assignment_score / $num_assignments : 0;
                                                                $average_assessment_score = ($num_assessments > 0) ? $total_assessment_score / $num_assessments : 0;
                                                                $average_exam_score = ($num_exams > 0) ? $total_exam_score / $num_exams : 0;
                                                            @endphp

                                                            <div class="card mb-3">
                                                                <div class="card-body">
                                                                    <h6 class="card-title small-text">Summary</h6>

                                                                    <div class="row">
                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="summary-section bg-light p-3 small-text shadow-sm mb-3">
                                                                                <h6 class="mb-3 small-text">Assignments</h6>
                                                                                <p class="mb-1"><span class="badge badge-primary small-text">Total Score:  {{ $total_assignment_score }}</span> </p>
                                                                                <p class="mb-1 small-text"><span class="badge badge-info ">Average Score: {{ number_format($average_assignment_score, 2) }}</span>  </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="summary-section bg-light p-3 shadow-sm mb-3">
                                                                                <h6 class="mb-3 small-text">Assessments</h6>
                                                                                <p class="mb-1 small-text"><span class="badge badge-primary">Total Score: {{ $total_assessment_score }}</span>  </p>
                                                                                <p class="mb-1 small-text"><span class="badge badge-info">Average Score: {{ number_format($average_assessment_score, 2) }}</span> </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="summary-section bg-light p-3 shadow-sm mb-3">
                                                                                <h6 class="mb-3 small-text">Exams</h6>
                                                                                <p class="mb-1 small-text"><span class="badge badge-primary">Total Score: {{ $total_exam_score }}</span>  </p>
                                                                                <p class="mb-1 small-text"><span class="badge badge-info">Average Score: {{ number_format($average_exam_score, 2) }}</span>  </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        @else
                                                        <div class="card">
                                                            <div class="card-header" id="heading_{{$term->id}}_{{$course->id}}">
                                                                <h2 class="mb-0">
                                                                    <button class="btn btn-link small-text" type="button" data-toggle="collapse" data-target="#collapse_{{$term->id}}_{{$course->id}}" aria-expanded="false" aria-controls="collapse_{{$term->id}}_{{$course->id}}">
                                                                        {{$course->name}}
                                                                        <span class="badge badge-warning"> <b> <span class="">{{$school_session->name ?? ''}}</b>
                                                                        <b class="small-text">{{$term->name}}</b></span>
                                                                    </button>
                                                                </h2>
                                                            </div>
                                                            <div id="collapse_{{$term->id}}_{{$course->id}}" class="collapse" aria-labelledby="heading_{{$term->id}}_{{$course->id}}" data-parent="#accordion_{{$course->id}}">
                                                                <div class="card-body">
                                                                    <h6 class="badge badge-warning small-text">Assignments</h6>
                                                                    @include('partials.assignment_table', ['grades' => $student->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body">
                                                                    <h6 class="badge badge-info small-text">Assessments</h6>
                                                                    @include('partials.assessment_table', ['grades' => $student->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body">
                                                                    <h6 class="badge badge-primary small-text">Exams</h6>
                                                                    @include('partials.exam_table', ['grades' => $student->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                            </div>
                                                            @php
                                                                $total_assignment_score = 0;
                                                                $num_assignments = 0;
                                                                $total_assessment_score = 0;
                                                                $num_assessments = 0;
                                                                $total_exam_score = 0;
                                                                $num_exams = 0;

                                                                foreach($student->grades->where('course_id', $course->id) as $grade){
                                                                    if($grade->assignment && $grade->assignment->academicSession->id == $school_session->id && $grade->assignment->term->name == $term->name){
                                                                        $total_assignment_score += $grade->score;
                                                                        $num_assignments++;
                                                                    }
                                                                    if($grade->assessment && $grade->assessment->academicSession->id == $school_session->id && $grade->assessment->term->name == $term->name){
                                                                        $total_assessment_score += $grade->score;
                                                                        $num_assessments++;
                                                                    }
                                                                    if($grade->exam && $grade->exam->academicSession->id == $school_session->id && $grade->exam->term->name == $term->name){
                                                                        $total_exam_score += $grade->score;
                                                                        $num_exams++;
                                                                    }
                                                                }

                                                                $average_assignment_score = ($num_assignments > 0) ? $total_assignment_score / $num_assignments : 0;
                                                                $average_assessment_score = ($num_assessments > 0) ? $total_assessment_score / $num_assessments : 0;
                                                                $average_exam_score = ($num_exams > 0) ? $total_exam_score / $num_exams : 0;
                                                            @endphp

                                                            <div class="card mb-3">
                                                                <div class="card-body">
                                                                    <h6 class="card-title small-text">Summary</h6>

                                                                    <div class="row">
                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="summary-section bg-light p-3 small-text shadow-sm mb-3">
                                                                                <h6 class="mb-3 small-text">Assignments</h6>
                                                                                <p class="mb-1 small-text"><span class="badge badge-primary">Total Score: {{ $total_assignment_score }} </span></p>
                                                                                <p class="mb-1 small-text"><span class="badge badge-info">Average Score: </span>  {{ number_format($average_assignment_score, 2) }}</p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="summary-section bg-light p-3 shadow-sm mb-3">
                                                                                <h6 class="mb-3 small-text">Assessments</h6>
                                                                                <p class="mb-1 small-text"><span class="badge badge-primary">Total Score: {{ $total_assessment_score }} </span>  </p>
                                                                                <p class="mb-1 small-text"><span class="badge badge-info">Total Score: </span>  {{ number_format($average_assessment_score, 2) }}</p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="summary-section bg-light p-3 shadow-sm mb-3">
                                                                                <h6 class="mb-3 small-text">Exams</h6>
                                                                                <p class="mb-1 small-text"> <span class="badge badge-primary">Total Score: {{ $total_exam_score }}</span> </p>
                                                                                <p class="mb-1 small-text"><span class="badge badge-info">Average Score: </span>{{ number_format($average_exam_score, 2) }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>







                    <div class="modal fade" id="studyConnectModal" tabindex="-1" aria-labelledby="studyConnectModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-purple text-white">
                                    <h5 class="modal-title" id="studyConnectModalLabel">Top Up Study Connects</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-success" id="connects-message" style="display:none;"></div>
                                    <div class="alert alert-danger" id="connects-error" style="display:none;"></div>
                                
                                    <div id="connectsForm">
                                    <div class="form-group">
                                        <label for="connectsAmountSuccess">Select Number of Connects:</label>
                                        <select class="form-control" name="connectsAmountSuccess" id="connectsAmountSuccess">
                                            <option value="500">90 Connects - ₦500</option>
                                            <option value="1000">210 Connects - ₦1000</option>
                                            <option value="2000">450 Connects - ₦2000</option>
                                            <option value="3000">1000 Connects - ₦3000</option>
                                        </select>
                                    </div>
                                    <button id="confirmBuySucessConnectsBtn" class="btn btn-success">Top Up Connects</button>

                                    </div>
                                </div>
                                <div class="modal-footer" id="conect-modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!-- <button type="button" class="btn btn-primary" id="confirmPlayBtn">Continue</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="your_results">
                    <!-- The performance -->
                    <div class="your_results your_results-inverse">
                        <!-- your_results time label -->
                        @php
                            $userResults = $student->studentResults;
                            $groupedResults = $userResults->groupBy('academic_session_id');
                            $academicSessions = \App\Models\AcademicSession::whereIn('id', $groupedResults->keys())->orderBy('created_at', 'desc')->get();
                        @endphp

                        @foreach($academicSessions as $academicSession)
                            @php
                                $academicSessionId = $academicSession->id;
                                $academicSessionResults = $groupedResults[$academicSessionId] ?? null;
                                $currentSessionClass = ($academicSessionId == $school_session->id) ? 'bg-primary' : (($academicSessionId > $school_session->id) ? 'bg-secondary' : 'bg-info');
                            @endphp

                            @if($academicSessionResults)
                                <div class="toggle-session-container {{ $currentSessionClass }}">
                                    <h6 class="toggle-session" style="cursor:pointer;" data-toggle="collapse" data-target="#session-term-display-{{$academicSessionId}}">
                                        <span><b>Academic Session:</b></span> {{ $academicSession->name ?? 'N/A' }}
                                        <i class="fas fa-chevron-down ml-2"></i>
                                    </h6>
                                </div>

                                <div id="session-term-display-{{$academicSessionId}}" class="collapse">
                                    @foreach($academicSessionResults->groupBy('term_id') as $termId => $termResults)
                                        @php
                                            $term = $termResults->first()->term ?? null;
                                            $currentTermClass = ($termId == $school_term->id) ? 'bg-primary' : (($termId > $school_term->id) ? 'bg-secondary' : 'bg-info');
                                        @endphp
                                        @if($term)
                                            <a href="{{ route('view_student_result', ['student_id' => auth()->user()->id, 'academic_session_id' => $academicSession->id , 'term_id' => $term->id ]) }}" class="p-2 ml-3 mb-2 toggle-term dropdown-item {{ $currentTermClass }} text-white p-2" style="opacity:0.6;">
                                                <span class=""></span> {{ $term->name ?? 'N/A' }} Results
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach

                        @if($academicSessions->isEmpty())
                            <p>No Result Available for {{ auth()->user()->profile->full_name }}</p>
                        @endif
                    </div>
                    <!-- /.your_results -->
                    <!-- /.performance -->
                </div>




                <div class="tab-pane" id="attendance">
                    <div class="post">
                        <div class="user-block">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Attendance Records for <b>{{ $student->profile->full_name}}</b></h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body table-responsive p-0" style="max-height: 300px;">
                                            @php
                                                use Carbon\Carbon;
                                                $latestAcademicSession = $student->school->academicSession;
                                                $latestTerm = $student->school->term;
                                            @endphp
                                            @forelse($student->attendance()->latest()->take(14)->get()->groupBy('academic_session_id') as $academicSessionId => $attendancesByAcademicSession)
                                                @php
                                                    $academicSession = $attendancesByAcademicSession->first()->academicSession ?? $latestAcademicSession;
                                                    $currentSessionClass = ($academicSessionId == $school_session->id) ? 'bg-primary' : (($academicSessionId > $school_session->id) ? 'bg-secondary' : 'bg-info');
                                                @endphp
                                                <div class="toggle-session-container {{ $currentSessionClass }}">
                                                    <h6 class="toggle-session" style="cursor:pointer;" data-toggle="collapse" data-target="#session-term-display-{{$academicSessionId}}">
                                                        {{ $academicSession->name ?? 'N/A' }}:<span><b>Academic Session</b></span> 
                                                        <i class="fas fa-chevron-down ml-2"></i>
                                                    </h6>
                                                </div>

                                                <div id="session-term-display-{{$academicSessionId}}" class="collapse">
                                                    @foreach($attendancesByAcademicSession->groupBy('term_id') as $termId => $attendancesByTerm)
                                                        @php
                                                            $term = $attendancesByTerm->first()->term ?? $latestTerm;
                                                            $currentTermClass = ($termId == $school_term->id) ? 'bg-primary' : (($termId > $school_term->id) ? 'bg-secondary' : 'bg-info');
                                                            $totalDaysPresent = 0; // Define totalDaysPresent here
                                                        @endphp
                                                        <h6 class="toggle-term p-2 ml-3 {{$currentTermClass}}" data-toggle="collapse" data-target="#term-display-{{$academicSessionId}}-{{$termId}}" style="opacity:0.6;">
                                                            {{ $term->name ?? 'N/A' }}
                                                        </h6>
                                                        <div id="term-display-{{$academicSessionId}}-{{$termId}}" class="collapse ml-3">
                                                            <table class="table table-head-fixed ml-3 text-nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date</th>
                                                                        <th>School</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @forelse($attendancesByTerm as $attendance)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td class="small-text">{{ \Carbon\Carbon::parse($attendance->date)->format('l, F j, Y') }}</td>
                                                                        <td class="small-text">{{ $attendance->school->name }}</td>
                                                                        <td>
                                                                        @if($attendance->attendance)
                                                                            <span class="badge bg-success small-text">Present</span>
                                                                            @php
                                                                                $totalDaysPresent++;
                                                                            @endphp
                                                                        @else
                                                                            <span class="badge bg-danger small-text">Absent</span>
                                                                        @endif
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="4">No Attendance Record yet.</td>
                                                                    </tr>
                                                                @endforelse
                                                                </tbody>
                                                            </table>
                                                            <p>Total Days Present for {{ $term->name }}: {{ $totalDaysPresent }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @empty
                                                <p class="pl-4 pt-2">No attendance records found.</p>
                                            @endforelse
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
         

@endsection

@section('scripts')



<script>
      $(document).on('click', '#confirmBuySucessConnectsBtn', function() {
        const connectsAmountSuccess = $('#connectsAmountSuccess').val(); // Get selected connects amount
        console.log('Selected Connects Amount:', connectsAmountSuccess);

        if (connectsAmountSuccess) {
            buyConnects(connectsAmountSuccess); // Call buyConnects function with selected amount
        } else {
            console.error('Selected Connects Amount is empty or invalid');
        }
    });

    // Function to handle buying connects via AJAX
    function buyConnects(selectedConnectsAmount) {
        var studentId = '{{$student->id}}'
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Perform AJAX request to buy more connects with selected price value
        $.ajax({
            url: '/buy-connects-for-student/'+studentId,
            method: 'POST',
            data: {
                amount: selectedConnectsAmount,
                _token: csrfToken
            },
            success: function(response) {
                // Handle success response
                console.log('Buy Connects Response:', response); // Log the response for debugging

                if (response && response.redirect_url) {
                    window.location.href = response.redirect_url; // Redirect to the specified URL
                } else {
                    console.error('Invalid response format');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
                console.error('Error buying connects:', error);
            }
        });
    }
</script>

<script>
    $(document).ready(function(){
        $('.toggle-session').on('click', function(){
            var icon = $(this).find('i');
            icon.toggleClass('fa-chevron-down fa-chevron-up');

            // Collapse all other sessions except the clicked one
            var target = $($(this).data('target'));
            $('.collapse').not(target).collapse('hide');
            $('.toggle-session').not($(this)).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        });

        // Update icon when collapse event is triggered
        $('.collapse').on('hidden.bs.collapse', function () {
            var toggleSession = $(this).prev('.toggle-session');
            toggleSession.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        });

        $('.collapse').on('shown.bs.collapse', function () {
            var toggleSession = $(this).prev('.toggle-session');
            toggleSession.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        });
    });
</script>


@endsection

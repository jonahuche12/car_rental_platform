@extends('layouts.app')

@section('title', "Central School System - Admin Profile")

@section('style')
<style>
  .complete_profile{
    display: none;
  }
  .profile_pic_style{
    cursor: pointer; 
    position: absolute; 
    bottom: -10px; 
    right: 100px;
  }
  .qualification-container {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
  }


  .expand-icon {
    cursor: pointer;
}

.expand-icon.rotate-down {
    transform: rotate(180deg);
}



  .dropdown-toggle::after {
            content: none !important;
        }
        
        .dropdown-item {
            text-align: left;
            padding: 5px;
        }
        .dropdown-header {
            font-weight: bold;
            padding: 5px 15px;
        }
        .dropdown-divider {
            margin: 5px 0 !important;
        }
        .dropdown-menu.show {
            display: block;
            opacity: 1;
            visibility: visible;
        }
        .dropdown-item:hover {
            font-weight:900;
        background-color: #007bff;
        color: #f8f9fa; 
    }
    
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
    .lessons-container {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}

.lessons-container::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
}




</style>
@endsection
@section('page_title', "Dashboard")
@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb3', "Dashboard")

@section('content')

@include('sidebar')

    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
         
        @php
            $student = auth()->user();
            $school = auth()->user()->school;
            $school_session = $school->academicSession;
            $school_term = $school->term;
            
          @endphp
          <!-- /.col -->
          @if($student->schoolClass() && $previous_class_level != null)
            <a href="{{ route('scholarship_program', ['class_level' => $previous_class_level]) }}" class="btn btn-primary btn-sm">Enter Scholarship Program</a>
          @endif
          <div>
         
          </div>
          <div class="col-md-12">
            <div class="card">
            @if ($user->userPackage && $user->userPackage->name === 'Basic Package')
                    <div class="alert alert-warning alert-dismissible fade show small-text" role="alert">
                        You are on the Basic Package. Please <a href="{{ route('user.package') }}" class="alert-link">upgrade your account</a> to Unlock more Features.
                        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif ($user->userPackage && $user->userPackage->name !== 'Premium Package')
                    <div class="alert alert-info alert-dismissible fade show small-text" role="alert">
                        Upgrade to our <a href="{{ route('user.package') }}" class="text-light badge bg-success p-2">Premium package</a> to unlock more features.
                        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
              <div class="card-header p-2">
                <ul class="nav nav-pills">


                <li class="nav-item small-text">
                    <a class="nav-link active" href="#courses" data-toggle="tab">
                        <i class="fas fa-book nav-icon"></i> <!-- Use "fas fa-book" for the book icon -->
                        Courses
                    </a>
                </li>
                <li class="nav-item small-text">
                    <a class="nav-link" href="#fav_lessons" data-toggle="tab">
                        <i class="fas fa-heart nav-icon"></i> <!-- Use "far fa-star" for the empty star icon -->
                        Fav Lessons
                    </a>
                </li>
                <li class="nav-item small-text">
                    <a class="nav-link" href="#viewed_lessons" data-toggle="tab">
                        <i class="fas fa-eye nav-icon"></i> <!-- Use "far fa-eye" for the empty eye icon -->
                        Viewed Lessons
                    </a>
                </li>
                <li class="nav-item small-text">
                    <a class="nav-link" href="#your_results" data-toggle="tab">
                        <i class="fas fa-poll nav-icon"></i> <!-- Use "fas fa-poll" for the analytics icon -->
                        Your Results
                    </a>
                </li>
                <!-- <li class="nav-item small-text">
                    <a class="nav-link" href="#analytics" data-toggle="tab">
                        <i class="fas fa-chart-bar nav-icon"></i> 
                        Analytics
                    </a>
                </li> -->


                </ul>
                <p id="course-offer-success" class="alert alert-success mt-2 p-2" style="display:none"></p>
                <p id="course-offer-error" class="alert alert-danger mt-2 p-2" style="display:none"></p>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="courses">
                    <!-- Post -->
                    <div class="post">
                    <div class="user-block">
                    <div class="post">
                      <div class="row">
                        <div class="col-12">
                        <div class="card">
    <div class="card-header">
        <h3 class="card-title small-text">Courses Offered By <b>{{auth()->user()->profile->full_name}}</b></h3>
    </div>
   
    <!-- ./card-header -->

    <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
        <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="studentActionsDropdown{{ $student->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><sup class="text-success"><b>{{$student->availableCourses()->count()}}</b></sup>
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <div class="dropdown-menu" style="width: 250px;" aria-labelledby="studentActionsDropdown{{ $student->id }}">
            @if ($student->id == auth()->id())
                <h6 class="dropdown-header text-left">Available Courses:</h6>
                <div class="dropdown-divider"></div>
                @foreach($student->availableCourses() as $course)
                    <a href="#" class="dropdown-item course_item" data-course-id="{{ $course->id }}" data-student-id="{{ $student->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0">{{ $course->name }}</p>
                                <p class="small text-muted mb-0">Teacher: {{ $course->getTeacherForClassSection($student->class_section_id)->profile->full_name }}</p>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach

                @if($student->availableCourses()->isEmpty())
                    <div class="dropdown-item">
                        <p class="mb-0">No available courses</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

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
                    @foreach(auth()->user()->student_courses as $course)
                    <tr class="course-item">
                        <td class="small-text">{{$loop->iteration}}</td>
                        <td class="small-text">
                            <button class="btn btn-link small-text course-link" type="button" data-toggle="collapse" data-target="#collapse_{{$course->id}}" aria-expanded="false" aria-controls="collapse_{{$course->id}}">
                                {{$course->name}}
                            </button>
                        </td>
                        <td class="small-text">
                            @if($teacher = $course->getTeacherForClassSection(auth()->user()->class_section_id))
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
                                    @php
                                        $bgClass = ($term->name == $school_term->name && $school_session->id == $school->academicSession->id) ? 'bg-success' : 'bg-secondary';
                                    @endphp
                                    @if($term->name == $school_term->name)
                                    <div class="card">
                                        <div class="card-header  {{$bgClass}}" id="heading_{{$term->id}}_{{$course->id}}">
                                            <h2 class="mb-0 small-text text-white">
                                                <button class="btn btn-link  small-text text-white" type="button" data-toggle="collapse" data-target="#collapse_{{$term->id}}_{{$course->id}}" aria-expanded="true" aria-controls="collapse_{{$term->id}}_{{$course->id}}">
                                                    {{$course->name}}
                                                    <span class="badge badge-warning"> <b> <span class="">{{$school_session->name ?? ''}}</b>
                                                    <b class="">{{$term->name}}</b></span>
                                                </button>
                                            </h2>
                                        </div>
                                                            <div id="collapse_{{$term->id}}_{{$course->id}}" class="collapse" aria-labelledby="heading_{{$term->id}}_{{$course->id}}" data-parent="#accordion_{{$course->id}}">
                                                                <div class="card-body">
                                                                    <h6 class="badge badge-warning small-text">Assignments</h6>
                                                                    @include('partials.assignment_table', ['grades' => auth()->user()->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body smalll-text"> 
                                                                    <h6 class="badge badge-info">Assessments</h6>
                                                                    @include('partials.assessment_table', ['grades' => auth()->user()->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body small-text">
                                                                    <h6 class="badge badge-primary">Exams</h6>
                                                                    @include('partials.exam_table', ['grades' => auth()->user()->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                            </div>
                                                            @php
                                                                $total_assignment_score = 0;
                                                                $num_assignments = 0;
                                                                $total_assessment_score = 0;
                                                                $num_assessments = 0;
                                                                $total_exam_score = 0;
                                                                $num_exams = 0;

                                                                foreach(auth()->user()->grades->where('course_id', $course->id) as $grade){
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
                                                            <div class="card-header  {{$bgClass}}" id="heading_{{$term->id}}_{{$course->id}}">
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
                                                                    @include('partials.assignment_table', ['grades' => auth()->user()->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body">
                                                                    <h6 class="badge badge-info small-text">Assessments</h6>
                                                                    @include('partials.assessment_table', ['grades' => auth()->user()->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                                <div class="card-body">
                                                                    <h6 class="badge badge-primary small-text">Exams</h6>
                                                                    @include('partials.exam_table', ['grades' => auth()->user()->grades, 'course_id' => $course->id, 'school_session' => $school_session, 'school_term' => $term])
                                                                </div>
                                                            </div>
                                                            @php
                                                                $total_assignment_score = 0;
                                                                $num_assignments = 0;
                                                                $total_assessment_score = 0;
                                                                $num_assessments = 0;
                                                                $total_exam_score = 0;
                                                                $num_exams = 0;

                                                                foreach(auth()->user()->grades->where('course_id', $course->id) as $grade){
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
                          <!-- /.card -->
                        </div>
                      </div>
                    </div>
                    </div>
                     
                    </div>
                   
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="fav_lessons">
                    <div class="lessons-container" style="max-height: 500px; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none;">
                        <div class="fav_lessons fav_lessons-inverse">
                            <div class="row">
                                @foreach ($fav_lessons as $lesson)
                                    <div class="col-md-4">
                                        <div class="card lesson-card">
                                            @if ($lesson->user_id == auth()->id())
                                                <!-- Dropdown menu for actions (only visible to the lesson owner) -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="lessonActionsDropdown{{ $lesson->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i> <!-- Horizontal ellipsis icon -->
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="lessonActionsDropdown{{ $lesson->id }}">
                                                        <!-- Button to trigger Edit Lesson Modal -->
                                                        <a class="dropdown-item edit-lesson-btn" href="#" data-viewed_lesson-id="{{ $lesson->id }}">Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removelessonModal{{ $lesson->id }}">Remove lesson</a>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Display lesson thumbnail -->
                                            <div class="thumbnail-container position-relative">
                                                <a href="{{ route('lessons.show', $lesson->id) }}" class="fav_lesson" data-fav_lesson-id="{{ $lesson->id }}" data-fav_lesson-title="{{ $lesson->title }}" data-school-connects-required="{{ $lesson->school_connects_required }}">
                                                    <!-- Conditionally display enrollment badge -->
                                                  

                                                    @if ($lesson->thumbnail)
                                                        <!-- Display the lesson thumbnail with play icon overlay -->
                                                        <div class="thumbnail-with-play">
                                                            <img src="{{ asset($lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="img-fluid lesson-thumbnail">
                                                            <div class="play-icon-overlay">
                                                                <i class="fas fa-play"></i>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <!-- Display default thumbnail with play icon -->
                                                        <div class="no-thumbnail">
                                                            <div class="video-icon">
                                                                <i class="fas fa-video"></i>
                                                            </div>
                                                            <div class="overlay"></div>
                                                            <img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">
                                                        </div>
                                                    @endif
                                                </a>
                                                <p class="badge bg-primary" style="position:relative; z-index:99;"><small><b>{{ $lesson->school_connects_required }} SC</b></small></p>
                                                <p class="lesson-date small-text" style="float:right"> {{$lesson->created_at->diffForHumans()}}</p>
                                            </div>

                                            <!-- Additional lesson details -->
                                            <p><small><b>{{ $lesson->teacher->profile->full_name }}</b></small></p>
                                            <h5><small>{{ \Illuminate\Support\Str::limit($lesson->title, 15) }}</small></h5>
                                            <p><small>{{ \Illuminate\Support\Str::limit($lesson->description, 200) }}</small></p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                  </div>




                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="viewed_lessons">
                    <div class="lessons-container" style="max-height: 500px; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none;">
                            <div class="row">
                                @foreach ($viewed_lessons as $lesson)
                                <div class="col-md-4">
                                    <div class="card lesson-card">
                                        @if ($lesson->user_id == auth()->id())
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="lessonActionsDropdown{{ $lesson->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="lessonActionsDropdown{{ $lesson->id }}">
                                                    <a class="dropdown-item edit-lesson-btn" href="#" data-viewed_lesson-id="{{ $lesson->id }}">Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removelessonModal{{ $lesson->id }}">Remove lesson</a>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="thumbnail-container position-relative">
                                        <a href="{{ route('lessons.show', $lesson->id) }}" class="viewed_lessons" data-viewed_lesson-id="{{ $lesson->id }}" data-lesson-title="{{ $lesson->title }}" data-school-connects-required="{{ $lesson->school_connects_required }}">
                                              

                                                @if ($lesson->thumbnail)
                                                    <div class="thumbnail-with-play">
                                                        <img src="{{ asset($lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="img-fluid lesson-thumbnail">
                                                        <div class="play-icon-overlay">
                                                            <i class="fas fa-play"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="no-thumbnail">
                                                        <div class="video-icon">
                                                            <i class="fas fa-video"></i>
                                                        </div>
                                                        <div class="overlay"></div>
                                                        <img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">
                                                    </div>
                                                @endif
                                            </a>

                                            <p class="badge bg-primary" style="position:relative; z-index:99;"><small><b>{{ $lesson->school_connects_required }} SC</b></small></p>
                                            <p class="lesson-date small-text" style="float:right"> {{$lesson->created_at->diffForHumans()}}</p>
                                        </div>

                                        <p><small><b>{{ $lesson->teacher->profile->full_name }}</b></small></p>
                                        <h5><small>{{ \Illuminate\Support\Str::limit($lesson->title, 15) }}</small></h5>
                                        <p><small>{{ \Illuminate\Support\Str::limit($lesson->description, 200) }}</small></p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                  <div class="tab-pane" id="your_results">
                    <!-- The your_results -->
                    <div class="your_results your_results-inverse">
                        <!-- your_results time label -->
                        @php
                            $userResults = auth()->user()->studentResults;
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
                            <p>No Result Available for {{ auth()->user()->profile->namez }}</p>
                        @endif
                    </div>
                    <!-- /.your_results -->
                </div>


                  
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

@endsection

@section('scripts')
<script>
    function toggleCoursesForm() {
        $('.courses-form-container').toggle();
    }
    function submitCourse() {
      var formData = $('#qualificationForm').serialize();
      var csrfToken = $('meta[name="csrf-token"]').attr('content');

      
      $.ajax({
          type: 'POST',
          url: '/submit-course',
          data: formData,
          dataType: 'json',
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          success: function (response) {
              console.log('Courses Status Updated:', response);
              // Display success message
              $('.course-message').text('Courses Status Updated.').show().delay(3000).fadeOut();
          },
          error: function (xhr, status, error) {
              console.log(xhr.responseText)
              console.error(error);
              // Display error message
              $('.course-error').text('Failed to enroll in course.').show().delay(3000).fadeOut();
          }
      });
    }

    $(document).ready(function() {
        $('.dropdown-menu').on('click', '.course_item', function(e) {
            e.preventDefault();
            var courseId = $(this).data('course-id');
            var studentId = $(this).data('student-id');

            // Send AJAX request to assign the student to the course
            $.ajax({
                type: 'POST',
                url: '/offer-course',
                data: {
                    course_id: courseId,
                    student_id: studentId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Display success message
                    $('#course-offer-success').text('You have been successfully Enrolled to '+ response.course.name).show();
                    setTimeout(function() {
                        $('#course-offer-success').fadeOut();
                        location.reload(); // Reload the page
                    }, 3000); // 3 seconds
                },
                error: function(xhr, status, error) {
                    // Display error message
                    $('#course-offer-error').text('Error assigning course ').show();
                    setTimeout(function() {
                        $('#course-offer-error').fadeOut();
                    }, 3000); // 3 seconds
                }
            });
        });
    });


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

<script>
   var canLoadMore = {
    viewed_lessons: true,
    fav_lessons: true
};
var currentPageLessons = {
    viewed_lessons: 0,
    fav_lessons: 0
};
var displayedViewedLessonIds = [];
var displayedFavLessonIds = [];
var loadingLessons = false;

function populateDisplayedIds() {
    $('.viewed_lesson').each(function() {
        var viewedLessonId = $(this).data('viewed_lesson-id');
        if (viewedLessonId && !displayedViewedLessonIds.includes(viewedLessonId)) {
            displayedViewedLessonIds.push(viewedLessonId);
        }
    });

    $('.fav_lesson').each(function() {
        var favLessonId = $(this).data('fav_lesson-id');
        if (favLessonId && !displayedFavLessonIds.includes(favLessonId)) {
            displayedFavLessonIds.push(favLessonId);
        }
    });
}

$(document).ready(function() {
    populateDisplayedIds();

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var activeTabId = $(e.target).attr('href').substring(1);
        loadMoreData(activeTabId);
    });

    $('.lessons-container').on('scroll', function() {
        if (isNearBottom(this)) {
            var activeTabId = $('.tab-pane.active').attr('id');
            loadMoreData(activeTabId);
        }
    });
});

function isNearBottom(element) {
    return element.scrollHeight - element.scrollTop <= element.clientHeight + 100;
}

function createLessonHtml(lesson) {
    var lessonHtml = '<div class="col-md-4">';
    lessonHtml += '<div class="card lesson-card">';
    lessonHtml += '<div class="thumbnail-container position-relative">';

    lessonHtml += '<a href="/lessons/' + lesson.id + '">';

    if (lesson.thumbnail) {
        lessonHtml += '<div class="thumbnail-with-play">';
        lessonHtml += '<img src="' + lesson.thumbnail + '" alt="' + lesson.title + '" class="img-fluid lesson-thumbnail">';
        lessonHtml += '<div class="play-icon-overlay"><i class="fas fa-play"></i></div>';
        lessonHtml += '</div>';
    } else {
        lessonHtml += '<div class="no-thumbnail">';
        lessonHtml += '<div class="video-icon"><i class="fas fa-video"></i></div>';
        lessonHtml += '<div class="overlay"></div>';
        lessonHtml += '<img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">';
        lessonHtml += '</div>';
    }

    lessonHtml += '<p class="badge bg-primary" style="position:relative; z-index:99; float:left"><small><b>' + lesson.school_connects_required + ' SC</b></small></p>';
    lessonHtml += '<p class="lesson-date small-text" style="float:right">' + lesson.created_at + '</p>';
    lessonHtml += '</a>';

    lessonHtml += '</div>';
    lessonHtml += '<div class="lesson-details">';
    lessonHtml += '<p><small><b>' + lesson.teacher_name + '</b></small></p>';
    lessonHtml += '<h5><small>' + lesson.title.substring(0, 15) + '</small></h5>';
    lessonHtml += '<p><small>' + lesson.description.substring(0, 200) + '</small></p>';
    lessonHtml += '</div>';
    lessonHtml += '</div>';
    lessonHtml += '</div>';
    return lessonHtml;
}

function loadMoreData(activeTabId) {
    if (!loadingLessons && canLoadMore[activeTabId]) {
        loadingLessons = true;
        var route, displayedIds;

        if (activeTabId === 'viewed_lessons') {
            route = "{{ route('load.more.viewedlessons') }}";
            displayedIds = displayedViewedLessonIds;
        } else if (activeTabId === 'fav_lessons') {
            route = "{{ route('load.more.favlessons') }}";
            displayedIds = displayedFavLessonIds;
        }

        $.ajax({
            url: route,
            type: "GET",
            data: {
                page: currentPageLessons[activeTabId],
                displayedLessonIds: displayedIds
            },
            beforeSend: function() {
                $('#loader').append('<div class="loader-container"><div class="loader"><i class="fas fa-spinner fa-spin"></i> Loading Lessons...</div></div>');
            },
            success: function(response) {
                if (response && response.lessons && response.lessons.length > 0) {
                    currentPageLessons[activeTabId]++;
                    $.each(response.lessons, function(index, item) {
                        if (!displayedIds.includes(item.id)) {
                            var newItem = createLessonHtml(item);
                            $('#' + activeTabId + ' .lessons-container .row').append(newItem);
                            displayedIds.push(item.id);
                        }
                    });
                } else {
                    canLoadMore[activeTabId] = false;
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Lessons Error:", error);
            },
            complete: function() {
                loadingLessons = false;
                $('.loader-container').remove();
            }
        });
    }
}

</script>

@endsection
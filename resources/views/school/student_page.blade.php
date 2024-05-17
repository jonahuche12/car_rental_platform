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

</style>
@endsection
@section('page_title', "Profile")
@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb3', "Profile")

@section('content')

@include('sidebar')

    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <div class="col-md-4">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
              <div class="text-center position-relative">
                      @if($student->profile->profile_picture)
                          @php
                              $imageUrl = asset('storage/' . $student->profile->profile_picture);
                          @endphp
                          <div class="profile-picture-container">
                              <img id="profile-picture" class="profile-user-img img-fluid img-circle" src="{{ $imageUrl }}" alt="User profile picture">
                              
                          </div>
                      @else
                          <div class="profile-picture-container">
                              <img id="profile-picture" class="profile-user-img img-fluid img-circle" src="{{ asset('assets/img/avatar.jpg') }}" alt="Default avatar">
                              
                          </div>
                      @endif
                      
                  </div>

                  <h3 class="profile-username text-center">{{$student->profile->full_name}}</h3>

                  <p class="text-muted text-center">{{$student->profile->role}}</p>

                  <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                      <b>Email</b> <a class="float-right">{{ $student->profile->email }}</a>
                  </li>
                  <li class="list-group-item">
                      <b>Phone Number</b> <a class="float-right">{{$student->profile->phone_number}}</a>
                  </li>
                  <li class="list-group-item">
                      <b>Gender</b> <a class="float-right">{{$student->profile->gender}}</a>
                  </li>
                  <li class="list-group-item">
                      <b>Address</b> <a class="float-right"> <small><a class="float-right" id="address_data">
                          {{$student->profile->address }} {{$student->profile->city }} {{$student->profile->state }} {{$student->profile->country }} </a></small></a>
                  </li>
                  </ul>

                  <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">About <b>{{$student->profile->full_name}}</b></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Class </strong>

                <p class="text-muted">
                <h6>{{$student->userClassSection->name}}</h6>
                </p>



                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Bio </strong>

                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
          <!-- /.col -->
          <div class="col-md-8">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  
                  <li class="nav-item"><a class="nav-link active" href="#attendance" data-toggle="tab">Attendance</a></li>
                  @if(auth()->id()== $student->id)
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                  @endif
                  <li class="nav-item"><a class="nav-link" href="#courses" data-toggle="tab">Courses
                    
                  </a></li>
                  <!-- <li class="nav-item"><a class="nav-link" href="#analytics" data-toggle="tab">Analytics</a></li> -->
                </ul> 
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="attendance">
                    <!-- Post -->
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
                                                @endphp
                                                <h6 class="toggle-session p-2" data-toggle="collapse" data-target="#session-term-display-{{$academicSessionId}}">
                                                    <span class="p-4"><b>Academic Session:</b></span> {{ $academicSession->name ?? 'N/A' }}
                                                </h6>
                                                <div id="session-term-display-{{$academicSessionId}}" class="collapse">
                                                    @foreach($attendancesByAcademicSession->groupBy('term_id') as $termId => $attendancesByTerm)
                                                        @php
                                                            $term = $attendancesByTerm->first()->term ?? $latestTerm;
                                                            $totalDaysPresent = 0;
                                                        @endphp
                                                        <h6 class="toggle-term p-2" data-toggle="collapse" data-target="#term-display-{{$academicSessionId}}-{{$termId}}">
                                                            <span class="p-4"><b>Term:</b></span> {{ $term->name ?? 'N/A' }}
                                                        </h6>
                                                        <div id="term-display-{{$academicSessionId}}-{{$termId}}" class="collapse">
                                                            <table class="table table-head-fixed text-nowrap">
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
                                                <p>No attendance records found.</p>
                                            @endforelse
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                        </div>
                    </div>
                     
                    <!-- /.post -->
                </div>
                  <!-- /.tab-pane -->
                  @if(auth()->id()== $student->id)
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-danger">
                          10 Feb. 2014
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-envelope bg-primary"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 12:05</span>

                          <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                          <div class="timeline-body">
                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                            quora plaxo ideeli hulu weebly balihoo...
                          </div>
                          <div class="timeline-footer">
                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-user bg-info"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                          <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend request
                          </h3>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-comments bg-warning"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                          <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                          <div class="timeline-body">
                            Take me to your leader!
                            Switzerland is small and neutral!
                            We are more like Germany, ambitious and misunderstood!
                          </div>
                          <div class="timeline-footer">
                            <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-success">
                          3 Jan. 2014
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-camera bg-purple"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                          <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                          <div class="timeline-body">
                            <img src="https://placehold.it/150x100" alt="...">
                            <img src="https://placehold.it/150x100" alt="...">
                            <img src="https://placehold.it/150x100" alt="...">
                            <img src="https://placehold.it/150x100" alt="...">
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <div>
                        <i class="far fa-clock bg-gray"></i>
                      </div>
                    </div>
                  </div>
                  @endif
                  
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="courses">
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
    
    document.addEventListener("DOMContentLoaded", function() {
        const courseLinks = document.querySelectorAll('.course-link');

        courseLinks.forEach(link => {
            link.addEventListener('click', function() {
                const target = this.dataset.target;

                // Close all other course collapses
                const allCourseCollapses = document.querySelectorAll('.collapse.show');
                allCourseCollapses.forEach(collapse => {
                    if (collapse.id !== target) {
                        $(collapse).collapse('hide');
                    }
                });
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.collapse').on('show.bs.collapse', function() {
            $(this).siblings('.card-header').find('.btn').addClass('active');
        });

        $('.collapse').on('hide.bs.collapse', function() {
            $(this).siblings('.card-header').find('.btn').removeClass('active');
        });
    });
</script>
<script>
// Retrieve the data for assignments, assessments, and exams (replace with your actual data)
var assignmentData = {
        labels: ['A', 'B', 'C', 'D', 'F'],
        datasets: [{
            label: 'Assignment Grades',
            data: [20, 30, 25, 15, 10], // Sample data (replace with your actual data)
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ]
        }]
    };

    var assessmentData = {
        labels: ['A', 'B', 'C', 'D', 'F'],
        datasets: [{
            label: 'Assessment Grades',
            data: [15, 25, 20, 30, 10], // Sample data (replace with your actual data)
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ]
        }]
    };

    var examData = {
        labels: ['A', 'B', 'C', 'D', 'F'],
        datasets: [{
            label: 'Exam Grades',
            data: [10, 20, 15, 25, 30], // Sample data (replace with your actual data)
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ]
        }]
    };

    // Create charts for assignments, assessments, and exams
    var assignmentChart = new Chart(document.getElementById('assignmentGradeChart'), {
        type: 'bar',
        data: assignmentData,
        options: {
            responsive: true,
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Assignment Grade Distribution'
            }
        }
    });

    var assessmentChart = new Chart(document.getElementById('assessmentGradeChart'), {
        type: 'bar',
        data: assessmentData,
        options: {
            responsive: true,
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Assessment Grade Distribution'
            }
        }
    });

    var examChart = new Chart(document.getElementById('examGradeChart'), {
        type: 'bar',
        data: examData,
        options: {
            responsive: true,
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Exam Grade Distribution'
            }
        }
    });
</script>>

@endsection
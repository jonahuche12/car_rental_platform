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
                  <li class="nav-item"><a class="nav-link" href="#analytics" data-toggle="tab">Analytics</a></li>
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
                              <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-head-fixed text-nowrap">
                                  <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Date</th>
                                      <th>School</th>
                                      <!-- <th>Teacher</th> -->
                                      <th></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  @forelse($student->attendance()->latest()->take(14)->get() as $attendance)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>{{ $attendance->date }}</td>
                                          <td>{{ $attendance->school->name }}</td>
                                          <td>
                                              <!-- Attendance Checkbox -->
                                              <input type="checkbox" {{ $attendance->attendance ? 'checked' : '' }} disabled>
                                          </td>
                                      </tr>
                                  @empty
                                      <tr>
                                          <td colspan="4">No Attendance Record yet.</td>
                                      </tr>
                                  @endforelse

                                    
                                  </tbody>
                                </table>
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
                            <h3 class="card-title">Courses Offered By <b>{{$student->profile->full_name}}</b></h3>
                          </div>
                          <!-- ./card-header -->
                          <div class="card-body">
                            <table class="table table-bordered table-hover">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Course</th>
                                  <th>Teacher</th>
                                  <th>Compulsory</th>
                                  <!-- <th>Reason</th> -->
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($student->student_courses as $course)
                                <tr data-widget="expandable-table" aria-expanded="false">
                                  <td>{{$loop->iteration }}</td>
                                  <td>{{$course->name}}</td>
                                  <td>{{$course->getTeacherForClassSection($student->userClassSection->id)->profile->full_name}}</td>
                                  <td>
                                    @if($course->compulsory)
                                    <span class="text-success"><i class="fa fa-check"></i>compulsory</span>
                                    @else
                                    <span class="text-secondary"><i class="fa fa-check"></i>elective</span>
                                    @endif
                                  </td>
                                  <td>{{$course->description}}</td>
                                </tr>
                                <tr class="expandable-body">
                                  <td colspan="5">
                                    <p>
                                    
                                    </p>
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




                  <div class="tab-pane" id="analytics">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputName" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputName2" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
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
 
    
</script>

@endsection
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
            <div class="card card-purple card-outline">
              <div class="card-body box-profile">
                <div class="text-center position-relative">
                    @if(auth()->user()->profile->profile_picture)
                        @php
                            $imageUrl = asset('storage/' . auth()->user()->profile->profile_picture);
                        @endphp
                        <div class="profile-picture-container">
                            <img id="profile-picture" class="profile-user-img img-fluid img-circle" src="{{ $imageUrl }}" alt="User profile picture">
                            <label for="profile-image-input" class="profile_pic_style">
                                <i class="ion ion-camera" style="font-size: 20px;"></i>
                            </label>
                        </div>
                    @else
                        <div class="profile-picture-container">
                            <img id="profile-picture" class="profile-user-img img-fluid img-circle" src="{{ asset('assets/img/avatar.jpg') }}" alt="Default avatar">
                            <label for="profile-image-input" class="profile_pic_style">
                                <i class="ion ion-camera" style="font-size: 20px;"></i>
                            </label>
                        </div>
                    @endif
                    <input type="file" id="profile-image-input" style="display: none;">
                    <div class="alert alert-danger validation-error" style="display:none"></div>
                    <div class="alert alert-success success-message" style="display:none"></div>
                </div>

                <h3 class="profile-username text-center">{{auth()->user()->profile->full_name}}</h3>

                <p class="text-muted text-center">{{auth()->user()->profile->role}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <!-- Email Field -->
                    <li class="list-group-item">
                        <b>Email :</b> <a class="float-right">{{ auth()->user()->profile->email }}</a>
                    </li>

                    <!-- Phone Number Field -->
                    <li class="list-group-item">
                        <b class="float-left">Phone Number: </b>
                        <div class="">
                            <a class="float-right" id="phone_number_data">{{ auth()->user()->profile->phone_number }}</a>
                            @if(auth()->user()->profile->phone_number == null)

                            <i class="ion ion-edit text-info float-right" id="phone_number-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                                <span class="edit-field" id="" style="display:none; vertical-align: middle;">
                                    <input type="text" style="width:100%" class='form-control' id="phone_number_input" placeholder="Enter phone number">
                                    <button class="btn btn-small bg-purple" id="phone_number-button" onclick="saveData('phone_number')">Save</button>
                                </span>
                            @endif
                            <div class="alert alert-danger phone_number-error" style="display:none; float:clear; margin:0"></div>
                            <div class="alert alert-success phone_number-message" style="display:none"></div>
                        </div>
                    </li>

                    <!-- Gender Field -->
                    <li class="list-group-item">
                        <b class="float-left">Gender: </b>
                        <a class="float-right" id="gender_data">{{ auth()->user()->profile->gender }}</a>
                            @if(auth()->user()->profile->gender == null)
                            <i class="ion ion-edit text-info float-right" id="gender-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                            
                            <br><span class="edit-field col-12" id="" style="display:none; vertical-align: middle;">
                                <select class="form-control" id="gender_input" name="gender">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                                <button class="btn btn-small bg-purple gender-button" id="gender-button" onclick="saveData('gender')">Save</button>
                            </span>
                            
                            <div class="alert alert-danger gender-error" style="display:none"></div>
                            <div class="alert alert-success gender-message" style="display:none"></div>
                            @endif
                        
                    </li>

                    <!-- Current Class Field -->
                    


                    <!-- Address Field -->
                    <li class="list-group-item">
                        <b>Location: </b>
                        <small><a class="float-right" id="address_data">
                        {{ auth()->user()->profile->address }} {{ auth()->user()->profile->city }} {{ auth()->user()->profile->state }} {{ auth()->user()->profile->country }} </a></small>
                        @if(auth()->user()->profile->address == null || auth()->user()->profile->city == null ||auth()->user()->profile->state == null || auth()->user()->profile->country == null )
                        <div class="float-right">
                            <i class="ion ion-edit text-info" id="address-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                        </div>
                        
                        <span class="edit-field" id="address_input" style="display:none; vertical-align: middle;">
                            <div class="form-group mb-3">
                                <label for="country">Choose Country:</label>
                                <select class="form-control" name="country" id="country_input" onchange="populateStates()">
                                    <option value="{{ $profile->country }}">{{ $profile->country }}</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Kenya">Kenya</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="state">Select State:</label>
                                <select class="form-control" name="state" id="state_input" onchange="populateCities()">
                                    <option value="{{ $profile->state }}">{{ $profile->state }}</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="city">City:</label>
                                <select class="form-control" name="city" id="city_input">
                                    <option value="{{ $profile->city }}">{{ $profile->city }}</option>
                                </select>
                            </div>
                            <input type="text" class='form-control' id="address_field_input" value="{{auth()->user()->profile->address}}" placeholder="Enter Your Address">
                            <button class="btn btn-small bg-purple" id="address-button" onclick="saveData('address')">Save</button>
                        </span>
                        
                        <div class="alert alert-danger address-error" style="display:none"></div>
                        <div class="alert alert-success address-message" style="display:none"></div>
                        @endif
                    </li>

                    <!-- Date of birth Field -->
                    <li class="list-group-item">
                      <b class="float-left">Date Of Birth: </b>
                      @if(auth()->user()->profile->date_of_birth == null)
                      <i class="ion ion-edit float-right text-info" id="date_of_birth-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                      @endif
                          <a class="text-info float-right" id="date_of_birth_data">{{ auth()->user()->profile->date_of_birth }}</a>
                          @if(auth()->user()->profile->date_of_birth == null)
                              <span class="edit-field" style="display:none; vertical-align: middle;">
                                  <input type="date" class='form-control' id="date_of_birth_input" placeholder="Enter Your Date Of Birth">
                                  <button class="btn btn-sm bg-purple" id="date_of_birth-button" onclick="saveData('date_of_birth')">Save</button>
                              </span>
                          @endif
                          <div class="alert alert-danger date_of_birth-error" style="display:none"></div>
                          <div class="alert alert-success date_of_birth-message" style="display:none"></div>
                      
                  </li>

                  <li class="list-group-item">
                      <b class="float-left">School: </b>
                      @if(auth()->user()->school)
                        <a class="float-right" id="school_data" >
                        <div class="d-flex float-right flex-column align-items-start">
                              <span class="school-display">{{ auth()->user()->school->name }}</span>

                              @if (auth()->user()->profile->teacher_confirmed)
                                
                                  <a href="#" class="badge bg-purple badge-sm">Teacher Confirmed <i class="fas fa-check-circle"></i></a>
                              @endif

                              @if (auth()->user()->profile->student_confirmed)
                                  
                                  <a href="#" class="badge bg-purple badge-sm">Student Confirmed <i class="fas fa-check-circle"></i></a>
                              @endif


                              @if (auth()->user()->profile->admin_confirmed)
                                  
                                  <a href="#" class="badge bg-purple badge-sm">Admin Confirmed <i class="fas fa-check-circle"></i></a>
                              @endif
                          </div>
                        </a>
                        @endif

                        @php
                          $role = auth()->user()->profile->role;
                          $role_confirmed = false;
                          if($role == "admin" && auth()->user()->profile->admin_confirmed){
                            $role_confirmed = true;

                          }elseif($role == "student" && auth()->user()->profile->student_confirmed){
                            $role_confirmed = true;

                          }elseif($role == "teacher" && auth()->user()->profile->teacher_confirmed){
                            $role_confirmed = true;

                          }elseif($role == "staff" && auth()->user()->profile->staff_confirmed){
                            $role_confirmed = true;

                          }
                        @endphp
                          @if(!$role_confirmed)
                          <i class="ion ion-edit text-info float-right" id="school-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                          @endif
                          
                          <br><span class="edit-field col-12" id="" style="display:none; vertical-align: middle;">
                          <input type="text" class="form-control" id="school_search" placeholder="Search for a School" data-type="school">
                          <input type="text" class="form-control" id="school_input" style="display:none" name="school_input">

                              <button class="btn btn-small bg-purple gender-button" id="school-button" onclick="saveData('school')">Save</button>
                          </span>
                          
                          <div class="alert alert-danger school-error" style="display:none"></div>
                          <div class="alert alert-success school-message" style="display:none"></div>
                      
                         
                      
                  </li>

                  @if(auth()->user()->school && auth()->user()->profile->role == 'student')
                  <li class="list-group-item">
                      <b class="float-left">Class: </b>
                      @if(auth()->user()->schoolClass())
                        <a class="float-right" id="school_data" >
                        <div class="d-flex float-right flex-column align-items-start">
                              <span class="class-display">{{ auth()->user()->schoolClass()->name }}</span>
                              @if (auth()->user()->profile->class_confirmed)
                                
                                  <a href="#" class="badge bg-purple badge-sm">Class Confirmed <i class="fas fa-check-circle"></i></a>
                              @endif
                              
                          </div>
                        </a>
                        @endif
                        @if(!auth()->user()->profile->class_confirmed)
                          <i class="ion ion-edit text-info float-right" id="school-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                          

                          @endif

                          
                          <br><span class="edit-field col-12" id="" style="display:none; vertical-align: middle;">
                          <!-- In your Blade view, assuming you have a variable $classes available -->
                            <select class="form-control" id="class_input" name="class_input">
                                <option value="">Select a class</option>
                                @php
                                $classes = auth()->user()->school->classes;

                                @endphp
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>

                            <!-- Hide the input field -->
                            <input type="text" class="form-control" id="class_search" style="display:none;" placeholder="Search for a Class">

                              <button class="btn btn-small bg-purple gender-button" id="school-button" onclick="saveData('class')">Save</button>
                          </span>
                          
                          <div class="alert alert-danger class-error" style="display:none"></div>
                          <div class="alert alert-success class-message" style="display:none"></div>
                      
                         
                      
                  </li>
                  @endif
                  <li class="list-group-item">
                      <b class="float-left">User Package: </b>
                      <a class="float-right" id="user_package_data">
                        @if(auth()->user()->userPackage)
                            @php
                                $user = auth()->user();
                                $package = $user->userPackage;
                                $badgeColor = $user->active_package
                                    ? (function() use ($package) {
                                        switch ($package->name) {
                                            case 'Standard Package':
                                                return 'badge-info';
                                            case 'Basic Package':
                                                return 'badge-dark';
                                            case 'Premium Package':
                                                return 'badge-warning';
                                            default:
                                                return 'badge-warning';
                                        }
                                    })()
                                    : 'badge-warning';
                                $expired = $user->active_package && $user->expected_expiration && $user->expected_expiration->isPast();
                            @endphp

                            <div class="d-flex float-right flex-column align-items-start">
                                <span class="user-package-display badge {{ $badgeColor }}">{{ $package->name }}</span>

                                @if (!$user->active_package)
                                    <a href="{{ route('payment.activate', ['package_id' => $package->id]) }}" class="badge bg-purple badge-sm">Activate</a>
                                @elseif ($expired)
                                    <a href="{{ route('payment.activate', ['package_id' => $package->id]) }}" class="badge bg-purple badge-sm">Renew</a>
                                @endif
                            </div>
                        @else
                            <span class="badge badge-warning">No Package Yet</span>
                        @endif
                      </a>





                          <i class="ion ion-edit text-info float-right" id="user_package-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                         <br> <span class="edit-field col-12 " id="" style="display:none; vertical-align: middle;">

                            <select name="user_package_input" id="user_package_input" class="form-control">
                            <option value="">Select Package</option>
                              @foreach(auth()->user()->getUserPackages() as $user_package)
                                  <option value="{{ $user_package->id }}">
                                      {{ $user_package->name }}
                                      <span class="text-success">(N{{  number_format($user_package->price, 2)  }})</span>
                                  </option>
                              @endforeach
                            </select>
                              <button class="btn btn-small bg-purple user_package-button" id="user_package-button" onclick="saveData('user_package')">Save</button>
                          </span>
                          
                          <div class="alert alert-danger user_package-error" style="display:none"></div>
                          <div class="alert alert-success user_package-message" style="display:none"></div>
                      
                         
                      
                  </li>


                </ul>


                <!-- <a href="#" class="btn bg-purple btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- Modal for User Package Confirmation -->
            <div class="modal fade" id="userPackageModal" tabindex="-1" role="dialog" aria-labelledby="userPackageModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="userPackageModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to change your user package? This action cannot be undone.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-purple" onclick="proceedWithUserPackage()">Continue</button>
                  </div>
                </div>
              </div>
            </div>

              <div class="modal fade" id="schoolEditModal" tabindex="-1" role="dialog" aria-labelledby="schoolEditModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="schoolEditModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to change your School? You will lose your Progress.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-purple" onclick="proceedWithSchoolUpdate()">Continue</button>
                  </div>
                </div>
              </div>
            </div>


            <!-- Modal for Class Confirmation -->
            <div class="modal fade" id="schoolClassModal" tabindex="-1" role="dialog" aria-labelledby="schoolClassModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="schoolClassModalModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are About To update your Class? This action cannot be undone.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-purple" onclick="proceedWithClassUpdate()">Continue</button>
                  </div>
                </div>
              </div>
            </div>


            <!-- About Me Box -->
            <div class="card card-purple">
              <div class="card-header">
                <h3 class="card-title">About You</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong class="float-left"><i class="fas fa-book mr-1 -left"></i> Education</strong>
                @if(auth()->user()->qualifications->count() < 5 )
                <strong class="float-right"><i id="plusButton" class="fas fa-plus mr-1 -left" onclick="toggleForm()"></i></strong>
                @endif
                  <ul class="list-group list-group-unbordered mt-5 ml-4">
                    @foreach(auth()->user()->qualifications as $qualification)
                        <li class="list-group-item">
                             <b class="text-info">{{ $qualification->certificate . "," . $qualification->starting_year . " to ". $qualification->completion_year }}</b>
                        </li>
                    @endforeach
                  </ul>




                <div class="qualification-message alert alert-success" style="display:none"></div>
                <div class="qualification-error alert alert-danger" style="display:none"></div>
                <br>

                <div class="container mt-2  qualification-form-container" style="display:none; vertical-align: middle;">

                  <form id="qualificationForm">
                      <div id="qualificationsContainer">
                          <h3>Qualification </h3>
                          <div class="mb-3">
                              <div class="form-group">
                                  <label for="certificate">Certificate:</label>
                                  <input type="text" class="form-control" name="certificate" id="certificate" required>
                              </div>
                              <div class="form-group">
                                  <label for="school_attended">School Attended:</label>
                                  <input type="text" class="form-control" name="school_attended" id="school_attended" required>
                              </div>
                              <div class="form-group">
                                  <label for="starting_year">Starting Year:</label>
                                  <input type="number" class="form-control" name="starting_year" id="starting_year" required>
                              </div>
                              <div class="form-group">
                                  <label for="completion_year">Completion Year:</label>
                                  <input type="number" class="form-control" name="completion_year" id="completion_year" required>
                              </div>
                              
                          </div>
                      </div>
                      
                      <button type="button" class="btn btn-sm bg-purple" onclick="addQualification()">
                          <i class="icon ion-md-add"></i> Add More
                      </button>
                      <button type="button" class="btn btn-success" onclick="submitQualification()">Submit</button>
                  </form>
              </div>

                <span class="mb-3"></span>

                <hr>

                <!-- <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted">Malibu, California</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                <p class="text-muted">
                  <span class="tag tag-danger">UI Design</span>
                  <span class="tag tag-success">Coding</span>
                  <span class="tag tag-info">Javascript</span>
                  <span class="tag tag-warning">PHP</span>
                  <span class="tag tag-primary">Node.js</span>
                </p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p> -->
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
                  <li class="nav-item small-text"><a class="nav-link " href="#courses" data-toggle="tab">Courses</a></li>
                  <li class="nav-item small-text"><a class="nav-link" href="#lessons" data-toggle="tab">lessons</a></li>
                  <li class="nav-item small-text"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                  <li class="nav-item small-text"><a class="nav-link active" href="#analytics" data-toggle="tab">Analytics</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class=" tab-pane" id="courses">
                    <!-- Post -->
                    <div class="post">
                      <div class="row">
                        <div class="col-12">
                          <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Courses You handle </h3>
                            </div>
                            <!-- ./card-header -->
                            <div class="card-body table-responsive">
                              <table class="table table-bordered table-hover">
                                <thead>
                                  <tr class="bg-dark" >
                                    <th>#</th>
                                    <th>Course</th>
                                    <!-- <th>Class</th> -->
                                    <!-- <th>No of Students</th> -->
                                    <th>Compulsory</th>
                                    <th>Description</th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach(auth()->user()->teacher_courses()->get() as $course)
                                <tr data-widget="expandable-table"  aria-expanded="false">
                                    <td>{{$loop->iteration }}</td>
                                    <td><a class="text-purple"> {{$course->name}}</a></td>
                                    <td class="text-success">
                                        @if($course->compulsory)
                                            <span class=""><i class="fa fa-check"></i>compulsory</span>
                                        @else
                                            <span class="text-secondary"><i class="fa fa-check"></i>elective</span>
                                        @endif
                                    </td>
                                    <td>{{$course->description}}</td>
                                </tr>
                                <tr class="expandable-body">
                                    <td colspan="5">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Class Name</th>
                                                    <th>Number of Students</th>
                                                    <th>Records</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($course->class_sections()->wherePivot('teacher_id', auth()->id())->get() as $class)
                                                    <tr>
                                                        <td>{{$class->name}}</td>
                                                        <td>{{$class->students->count()}}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="{{ route('assignment', ['courseId' => $course->id, 'classSectionId' => $class->id, 'teacherId' => auth()->id()]) }}" class="btn btn-sm bg-purple">
                                                                    <i class="fas fa-file"></i><span class="d-none d-sm-inline"> Assignment</span>
                                                                </a>
                                                                <a href="{{ route('assessment', ['courseId' => $course->id, 'classSectionId' => $class->id, 'teacherId' => auth()->id()]) }}" class="btn btn-sm bg-purple">
                                                                    <i class="fas fa-tasks"></i><span class="d-none d-sm-inline"> Assessment</span>
                                                                </a>
                                                                <a href="{{ route('exam', ['courseId' => $course->id, 'classSectionId' => $class->id, 'teacherId' => auth()->id()]) }}" class="btn btn-sm bg-purple">
                                                                    <i class="fas fa-book"></i><span class="d-none d-sm-inline"> Exam</span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="lessons">
                    @include('partials.create_lesson')
                    <div class="lessons-container">
                      <div class="row">
                          @php
                              // Retrieve lessons associated with the authenticated user, ordered by creation date in descending order
                              $lessons = auth()->user()->lessons()->orderBy('created_at', 'desc')->get();
                          @endphp

                          @foreach ($lessons as $lesson)
                              <div class="col-md-4">
                                  <div class="card lesson-card">
                                  <div class="dropdown">
                                    <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="lessonActionsDropdown{{ $lesson->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="lessonActionsDropdown{{ $lesson->id }}">
                                        <!-- Button to trigger Edit Lesson Modal -->
                                        <!-- Button to trigger Edit Lesson Modal -->
                                        <a class="dropdown-item edit-lesson-btn" href="#" data-lesson-id="{{ $lesson->id }}">Edit</a>


                                        @if ($lesson->user_id == auth()->id())
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removelessonModal{{ $lesson->id }}">Remove lesson</a>

                                        @endif
                                    </div>
                                </div><br>

                                     
                                      <!-- Display lesson thumbnail if available -->
                                      <div class="thumbnail-container">
                                        <a href="{{ route('lessons.show', $lesson) }}">
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
                                     </div>
                                     <h5><small>{{ \Illuminate\Support\Str::limit($lesson->title, 15) }}</small></h5>

                                      <p><small>{{ \Illuminate\Support\Str::limit($lesson->description, 200) }}</small></p>
                                      <!-- Add more details as needed -->
                                  </div>
                              </div>

                              <!-- Remove lesson Modal -->
                              <div class="modal fade" id="removelessonModal{{ $lesson->id }}" tabindex="-1" role="dialog" aria-labelledby="removelessonModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="removelessonModalLabel">Remove lesson</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="alert alert-success lesson-message"  style="display:none;"></div>
                                          <div class="alert alert-danger" id="lesson-error" style="display:none;"></div>
                                        <div class="modal-body">
                                          
                                            Are you sure you want to Delete <b>{{ $lesson->title }} </b> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="removeLessonBtn" onclick="removelesson({{ $lesson->id }})">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          @endforeach
                      </div>
                  </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="active tab-pane" id="analytics">
                      <canvas id="lessonAnalyticsChart" style="height: 400px; width: 100%;"></canvas>

                      <div id="lessonSummary" class="table-responsive">
    <h5 class="mb-4">
        Lesson Analytics
        <button class="btn btn-sm btn-link text-decoration-none" type="button" data-toggle="collapse" data-target="#lessonTableCollapse" aria-expanded="false" aria-controls="lessonTableCollapse" onclick="toggleCollapseIcon(this)">
            <i id="collapseIcon" class="fas fa-chevron-down text-purple"></i> <!-- Icon for collapse -->
        </button>
    </h5>
    <div class="collapse" id="lessonTableCollapse">
        @if ($lessonAnalyticsData->isEmpty())
            <p>No lessons found.</p>
        @else
            <table class="table table-striped">
                <thead class="bg-purple">
                    <tr>
                        <th class="small-text" scope="col">Lesson Title</th>
                        <th class="small-text" scope="col">Views</th>
                        <th class="small-text" scope="col">Lesson Earnings</th>
                        <th class="small-text" scope="col">Teacher Earnings</th>
                        <th class="small-text" scope="col">School Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lessonAnalyticsData as $lesson)
                        <tr>
                            <td class="small-text">{{ $lesson['title'] }}</td>
                            <td class="small-text">{{ $lesson['views'] }}</td>
                            <td class="small-text">${{ number_format($lesson['lesson_earnings'], 2) }}</td>
                            <td class="small-text">${{ number_format($lesson['teacher_earnings'], 2) }}</td>
                            <td class="small-text">${{ number_format($lesson['school_earnings'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>



                  </div>

                  <!-- /.tab-pane -->
                </div>




                  <!-- Edit Lesson Modal -->
                  <div class="modal fade" id="editLessonModal" tabindex="-1" role="dialog" aria-labelledby="editLessonModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLessonModalLabel">Edit Lesson</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <form id="editLessonForm" enctype="multipart/form-data">
                                          <div class="alert alert-success" id="success-edit-message" style="display:none;"></div>
                                          <div class="alert alert-danger" id="error-edit-message" style="display:none;"></div>
                                  <div class="form-group">
                                      <label for="editLessonTitle">Title</label>
                                      <input type="text" class="form-control" id="editLessonTitle" name="edit_title">
                                  </div>
                                  <div class="form-group">
                                      <label for="editLessonSubject">Subject</label>
                                      <select name="edit_subject" class="form-control" id="editLessonSubject"></select>
                                  </div>
                                  <div class="form-group">
                                      <label for="editLessonClassLevel">Class Level</label>
                                      <select name="edit_class_level" class="form-control" id="editLessonClassLevel"></select>
                                  </div>
                                  <div class="form-group">
                                      <label for="editLessonDescription">Description</label>
                                      <textarea class="form-control" id="editLessonDescription" name="edit_description"></textarea>
                                  </div>
                                  <div class="form-group">
                                      <label for="lessonEditThumbnail" class="file-label">
                                          <i class="fas fa-image"></i> Thumbnail
                                      </label>
                                      <input type="file" class="form-control-file" id="lessonEditThumbnail" name="edit_thumbnail" onchange="previewEditThumbnail('lessonEditThumbnail', 'thumbnailEditPreview')" accept="image/*">
                                      <span class="file-info">Upload a thumbnail (Max size: 2MB)</span>
                                      <div id="thumbnailEditPreviewContainer">
                                          <img id="thumbnailEditPreview" src="#" alt="Thumbnail Preview" style="max-width: 100%; max-height: 100%; display: none;">
                                          <button type="button" class="btn btn-link" onclick="$('#lessonEditThumbnail').click();">
                                              <i class="fas fa-edit"></i> Change Thumbnail
                                          </button>
                                      </div>
                                  </div>
                                  <!-- Hidden field to store lesson ID -->
                                  <input type="hidden" id="editLessonId" name="lesson_id">
                              </form>




                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn bg-purple" id="saveLessonChangesBtn">Save Changes</button>
                                </div>
                            </div>
                        </div>
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
  var uniqueSubjectNames = {!! json_encode($uniqueSubjectNames) !!};



</script>


<script>
    function toggleCollapseIcon(button) {
        var icon = button.querySelector('i');
        if (icon.classList.contains('fa-chevron-down')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
</script>
<script>
     $(document).ready(function () {
            // Retrieve analytics data for lessons (assuming lessonAnalyticsData is passed from the controller)
            var lessonAnalyticsData = {!! json_encode($lessonAnalyticsData) !!};

            // Extract data for the chart
            var lessonTitles = lessonAnalyticsData.map(function (lesson) {
                return lesson.title;
            });

            var lessonViews = lessonAnalyticsData.map(function (lesson) {
                return lesson.views;
            });

            var teacherEarnings = lessonAnalyticsData.map(function (lesson) {
                return lesson.teacher_earnings;
            });

            var schoolEarnings = lessonAnalyticsData.map(function (lesson) {
                return lesson.school_earnings;
            });

            // Chart.js configuration
            var ctx = document.getElementById('lessonAnalyticsChart').getContext('2d');

            var lessonAnalyticsChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: lessonTitles,
                    datasets: [{
                        label: 'Views',
                        data: lessonViews,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
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
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Lesson Views'
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var lessonIndex = tooltipItem.index;
                                var lesson = lessonAnalyticsData[lessonIndex];
                                var views = lesson.views;
                                var teacherEarnings = lesson.teacher_earnings;
                                var schoolEarnings = lesson.school_earnings;

                                // Format tooltip label with views and earnings information
                                return 'Views: ' + views + ' | Teacher: $' + teacherEarnings.toFixed(2) + ' | School: $' + schoolEarnings.toFixed(2);
                            }
                        }
                    },
                    plugins: {
                        annotation: {
                            annotations: lessonAnalyticsData.map(function (lesson, index) {
                                return {
                                    type: 'line',
                                    mode: 'horizontal',
                                    scaleID: 'y-axis-0',
                                    value: index,
                                    borderColor: 'black',
                                    borderWidth: 1,
                                    label: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        content: 'Teacher: $' + lesson.teacher_earnings.toFixed(2) + ' | School: $' + lesson.school_earnings.toFixed(2),
                                        enabled: true
                                    }
                                };
                            })
                        }
                    }
                }
            });
        });
</script>


@endsection
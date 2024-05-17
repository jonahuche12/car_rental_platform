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
          <div class="col-md-6">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
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
                    <div class="alert alert-success profile-message" style="display:none"></div>
                    <div class="alert alert-success profile-error" style="display:none"></div>

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
                                    <button class="btn btn-small bg-primary" id="phone_number-button" onclick="saveData('phone_number')">Save</button>
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
                                <button class="btn btn-small bg-primary gender-button" id="gender-button" onclick="saveData('gender')">Save</button>
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
                            <button class="btn btn-small bg-primary" id="address-button" onclick="saveData('address')">Save</button>
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
                                  <button class="btn btn-sm bg-primary" id="date_of_birth-button" onclick="saveData('date_of_birth')">Save</button>
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
                                
                                  <a href="#" class="badge badge-success badge-sm">Teacher Confirmed <i class="fas fa-check-circle"></i></a>
                              @endif

                              @if (auth()->user()->profile->student_confirmed)
                                  
                                  <a href="#" class="badge badge-success badge-sm">Student Confirmed <i class="fas fa-check-circle"></i></a>
                              @endif


                              @if (auth()->user()->profile->admin_confirmed)
                                  
                                  <a href="#" class="badge badge-success badge-sm">Admin Confirmed <i class="fas fa-check-circle"></i></a>
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

                              <button class="btn btn-small bg-primary gender-button" id="school-button" onclick="saveData('school')">Save</button>
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
                                
                                  <a href="#" class="badge badge-success badge-sm">Class Confirmed <i class="fas fa-check-circle"></i></a>
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

                              <button class="btn btn-small bg-primary gender-button" id="school-button" onclick="saveData('class')">Save</button>
                          </span>
                          
                          <div class="alert alert-danger class-error" style="display:none"></div>
                          <div class="alert alert-success class-message" style="display:none"></div>
                      
                         
                      
                  </li>
                  @endif
                  <li class="list-group-item">

                  <div class="alert alert-danger package-error" style="display:none"></div>
                          <div class="alert alert-success package-message" style="display:none"></div>
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
                                    <a href="{{ route('payment.activate', ['package_id' => $package->id]) }}" class="badge badge-success badge-sm">Activate</a>
                                @elseif ($expired)
                                    <a href="{{ route('payment.activate', ['package_id' => $package->id]) }}" class="badge badge-success badge-sm">Renew</a>
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
                              <button class="btn btn-small bg-primary user_package-button" id="user_package-button" onclick="saveData('user_package')">Save</button>
                          </span>
                          
                      
                         
                      
                  </li>


                </ul>


                <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- Modal for User Package Confirmation -->
            <div class="modal fade" id="userPackageModal" tabindex="-1" role="dialog" aria-labelledby="userPackageModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title" id="userPackageModalLabel">Confirmation</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to change your user package? This action cannot be undone.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="proceedWithUserPackage()">Continue</button>
                  </div>
                </div>
              </div>
            </div>

              <div class="modal fade" id="schoolEditModal" tabindex="-1" role="dialog" aria-labelledby="schoolEditModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title" id="schoolEditModalLabel">Confirmation</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to change your School? .
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="proceedWithSchoolUpdate()">Continue</button>
                  </div>
                </div>
              </div>
            </div>


            <!-- Modal for Class Confirmation -->
            <div class="modal fade" id="schoolClassModal" tabindex="-1" role="dialog" aria-labelledby="schoolClassModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title" id="schoolClassModalModalLabel">Confirmation</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are About To update your Class? This action cannot be undone.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="proceedWithClassUpdate()">Continue</button>
                  </div>
                </div>
              </div>
            </div>


           
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-6">
            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About You</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong class="float-left"><i class="fas fa-book mr-1 -left"></i> Courses</strong>
                @if(auth()->user()->userClassSection)
                <strong class="float-right"><i id="plusButton" class="fas fa-chevron-down mr-1 -left" onclick="toggleCoursesForm()"></i></strong>
                @endif
                <ul class="list-group list-group-unbordered mt-5 ml-4">
                </ul>

                <div class="course-message alert alert-success" style="display:none"></div>
                <div class="course-error alert alert-danger" style="display:none"></div>
               

                <div class="container mt-2  courses-form-container" style="vertical-align: middle;">
                    <form id="qualificationForm">
                        <div id="qualificationsContainer">
                            <h3>Available Courses </h3>
                            @if(auth()->user()->userClassSection)
                            @foreach(auth()->user()->userClassSection->courses as $course)
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="course_{{ $course->id }}" name="courses[]" value="{{ $course->id }}" {{ $course->students->contains(auth()->user()->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="course_{{ $course->id }}">{{ $course->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                            @endif


                        </div>
                        <button type="button" class="btn btn-success" onclick="submitCourse()">Submit</button>
                    </form>
                </div>


                <hr>
<!--  -->
              </div>
              <!-- /.card-body -->
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
              setTimeout(function() {
                $('.course-message').fadeOut();
                location.reload(); // Reload the page
            }, 3000); // 3 seconds
          },
          error: function (xhr, status, error) {
              console.log(xhr.responseText)
              console.error(error);
              // Display error message
              $('.course-error').text('Failed to enroll in course.').show().delay(3000).fadeOut();
          }
      });
    }

   


</script>


@endsection
@extends('layouts.app')

@section('title', "Central School System - Super Admin Profile")

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
                        <b>Email</b> <a class="float-right">{{ auth()->user()->profile->email }}</a>
                    </li>

                    <!-- Phone Number Field -->
                    <li class="list-group-item">
                        <b class="float-left">Phone Number</b>
                        <div class="">
                            <a class="float-right">{{ auth()->user()->profile->phone_number }}</a>
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
                        <b class="float-left">Gender</b>
                        <a class="float-right">{{ auth()->user()->profile->gender }}</a>
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
                        <b>Location</b>
                        <small><a class="float-right">
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
                      <b class="float-left">Date Of Birth</b>
                      @if(auth()->user()->profile->date_of_birth == null)
                      <i class="ion ion-edit float-right text-info" id="date_of_birth-icon" style="vertical-align: middle;"><span style="vertical-align: middle; margin-right: 5px;"></span></i>
                      @endif
                          <a class="text-info float-right">{{ auth()->user()->profile->date_of_birth }}</a>
                          @if(auth()->user()->profile->date_of_birth == null)
                              <span class="edit-field" style="display:none; vertical-align: middle;">
                                  <input type="date" class='form-control' id="date_of_birth_input" placeholder="Enter Your Date Of Birth">
                                  <button class="btn btn-sm bg-primary" id="date_of_birth-button" onclick="saveData('date_of_birth')">Save</button>
                              </span>
                          @endif
                          <div class="alert alert-danger date_of_birth-error" style="display:none"></div>
                          <div class="alert alert-success date_of_birth-message" style="display:none"></div>
                      
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
                      
                      <button type="button" class="btn btn-sm btn-primary" onclick="addQualification()">
                          <i class="icon ion-md-add"></i> Add More
                      </button>
                      <button type="button" class="btn btn-success" onclick="submitQualification()">Submit</button>
                  </form>
              </div>

                <span class="mb-3"></span>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

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
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                        <span class="username">
                          <a href="#">Jonathan Burke Jr.</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                        <span class="description">Shared publicly - 7:30 PM today</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        Lorem ipsum represents a long-held tradition for designers,
                        typographers and the like. Some people hate it and argue for
                        its demise, but others ignore the hate as they create awesome
                        tools to help create filler text for everyone from bacon lovers
                        to Charlie Sheen fans.
                      </p>

                      <p>
                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                        <span class="float-right">
                          <a href="#" class="link-black text-sm">
                            <i class="far fa-comments mr-1"></i> Comments (5)
                          </a>
                        </span>
                      </p>

                      <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                    </div>
                    <!-- /.post -->

                    <!-- Post -->
                    <div class="post clearfix">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                        <span class="username">
                          <a href="#">Sarah Ross</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                        <span class="description">Sent you a message - 3 days ago</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        Lorem ipsum represents a long-held tradition for designers,
                        typographers and the like. Some people hate it and argue for
                        its demise, but others ignore the hate as they create awesome
                        tools to help create filler text for everyone from bacon lovers
                        to Charlie Sheen fans.
                      </p>

                      <form class="form-horizontal">
                        <div class="input-group input-group-sm mb-0">
                          <input class="form-control form-control-sm" placeholder="Response">
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-danger">Send</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <!-- /.post -->

                    <!-- Post -->
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="User Image">
                        <span class="username">
                          <a href="#">Adam Jones</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                        <span class="description">Posted 5 photos - 5 days ago</span>
                      </div>
                      <!-- /.user-block -->
                      <div class="row mb-3">
                        <div class="col-sm-6">
                          <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo">
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-sm-6">
                              <img class="img-fluid mb-3" src="../../dist/img/photo2.png" alt="Photo">
                              <img class="img-fluid" src="../../dist/img/photo3.jpg" alt="Photo">
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                              <img class="img-fluid mb-3" src="../../dist/img/photo4.jpg" alt="Photo">
                              <img class="img-fluid" src="../../dist/img/photo1.png" alt="Photo">
                            </div>
                            <!-- /.col -->
                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <p>
                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                        <span class="float-right">
                          <a href="#" class="link-black text-sm">
                            <i class="far fa-comments mr-1"></i> Comments (5)
                          </a>
                        </span>
                      </p>

                      <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                    </div>
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
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
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
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
function submitQualification() {
    // Serialize the form data as a URL-encoded string
    var formData = $("#qualificationForm").serialize();
    console.log(formData)

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send the data to the server using AJAX
    $.ajax({
        type: "POST",
        url: "/qualifications",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            // Handle success, update UI or perform any additional tasks
            console.log(response);

            // Hide the form and button with fade animation
        },
        error: function (xhr, status, error) {
            // Handle error, show a message to the user, etc.
            console.error(error);
            console.error(xhr.responseText);

            // Display a specific error message from the server with fade animation
            $(".qualification-error").text("Failed to update. Please try again.").fadeIn();

            // Hide the error message after 3 seconds with fade animation
            setTimeout(function () {
                $(".qualification-error").fadeOut();
            }, 3000);
        }
    });
}


</script>

<script>
  let isFormVisible = false; // Initial state of the form visibility
    const plusButtonIcon = document.getElementById('plusButton');
    const formContainer = document.querySelector('.qualification-form-container');

    function toggleForm() {
        // Toggle the visibility of the form container
        if (isFormVisible) {
            formContainer.style.display = 'none';
            plusButtonIcon.classList.remove('fa-minus');
            plusButtonIcon.classList.add('fa-plus');
        } else {
            formContainer.style.display = 'block';
            plusButtonIcon.classList.remove('fa-plus');
            plusButtonIcon.classList.add('fa-minus');
        }

        isFormVisible = !isFormVisible;
    }
</script>

@endsection
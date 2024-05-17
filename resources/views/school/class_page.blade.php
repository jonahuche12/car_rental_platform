@extends('layouts.app')

@section('title')
CSS - {{$class->name}}
@endsection

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection


@section('breadcrumb2')
<a href="#" class="btn-clear">{{$class->code}}</a>
@endsection

@section('style')
<style>
    /* Background overlay for expanded details */
    .collapsed-details {
        height: 100%; /* Adjust the maximum height for scrollable area */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #fff; /* Text color for details */
    }

    /* Toggle button style */
    .toggle-details-btn {
        margin-top: 10px;
        padding: 8px 12px;
        background-color: #17a2b8; /* Your desired button color */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .toggle-details-btn i {
        margin-left: 5px;
    }

    /* Styling for detail labels and values */
    .detail-item {
        margin-bottom: 8px;
    }

    .detail-label {
        font-weight: bold;
    }

    .detail-value {
        color: #fff; /* Text color for detail values */
    }
</style>


@endsection

@section('content')
@include('sidebar')

<section class="content">
      <div class="container-fluid">
        <div class="row">

        <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#people" data-toggle="tab">People</a></li>
                  <li class="nav-item"><a class="nav-link" href="#course" data-toggle="tab">Courses</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#lessons" data-toggle="tab">Lessons</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  
                <div class="tab-pane" id="people">
                      <!-- The timeline -->
                      <ul class="users-list clearfix">
                        @foreach ($students as $student)
                            <li class="col-md-4 col-">
                                <div class="card people-card" data-people-id="{{ $student->id }}" data-people-name="{{ $student->profile->full_name }}">
                                    <div class="card-body">
                                        <div class="user-profile">
                                            <img src="{{ asset($student->profile->profile_picture ? 'storage/' . $student->profile->profile_picture : 'dist/img/avatar5.png') }}" alt="User Image" width="150px">
                                            <a class="users-list-name" href="#" data-people-name="{{ $student->profile->full_name }}">
                                                {{ $student->profile->full_name }} <br>
                                                <span class="badge p-1 badge-info">{{ $student->userClassSection->code }}</span>
                                            </a>
                                        </div>
                                        <div class="user-details collapsed-details" id="student-details-{{ $student->id }}">
                                        <h5 style="cursor:pointer;"  class="detail-heading toggle-details-btn " data-target="student-details-{{ $student->id }}">Details <i class="toggle-icon fas fa-chevron-down"></i></h5>
                                            <p class="small-text"><strong>Email:</strong> {{ $student->email }}</p>
                                            <p class="small-text"><strong>Phone:</strong> {{ $student->profile->phone_number ?? 'N/A' }}</p>
                                            <p class="small-text"><strong>Gender:</strong> {{ $student->profile->gender }}</p>
                                            <p class="small-text"><strong>Date of Birth:</strong> {{ $student->profile->date_of_birth ?? 'N/A' }}</p>
                                        </div>
                                        <h5 style="cursor:pointer;"  class="detail-heading toggle-details-btn " data-target="student-details-{{ $student->id }}">Details <i class="toggle-icon fas fa-chevron-down"></i></h5>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                </div>

                <div class="tab-pane" id="course">
                    <div class="card-body p-0">
                        <div class="card-body">
                            <ul class="course-list clearfix">
                                @forelse ($school->courses as $course)
                                <li class="col-md-3 col-">
                                    <div class="card admin-card" data-admin-id="{{ $course->id }}" data-admin-name="{{ $course->name }}">
                                        <div class="card-body">
                                            <div class="user-profile shadow p-3 mb-5 bg-white rounded">
                                                <h2 class="mb-3 p-3"><b>{{ $course->code }}</b></h2>
                                                <h4>
                                                    <a class="course-list-name" href="#" data-admin-name="{{ $course->name }}">
                                                        {{ $course->name }}
                                                    </a>
                                                </h4>
                                            </div>

                                            <div class="user-permissions">
                                                <h5 style="cursor:pointer;" class="course-heading btn btn-info toggle-details-btn" data-target="user-details-{{ $course->id }}">
                                                    Details <i class="toggle-icon fas fa-chevron-down"></i>
                                                </h5>
                                                <div id="user-details-{{ $course->id }}" class="collapsed-details">
                                                <h5 style="cursor:pointer;" class="course-heading btn btn-info toggle-details-btn" data-target="user-details-{{ $course->id }}">
                                                    Details <i class="toggle-icon fas fa-chevron-down"></i>
                                                </h5>
                                                    <div class="detail-item">
                                                        <span class="detail-label"><strong>No of Teachers:</strong></span>
                                                        <span class="detail-value">{{ $course->teachers->count() }} Teachers</span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="detail-label"><strong>Total Students:</strong></span>
                                                        <span class="detail-value">{{ $course->students->count() }} Students</span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <p class="detail-value">{{ $course->description }}</p>
                                                    </div>
                                                    <!-- Link to view curriculum -->
                                                    <div class="text-center">
                                                        <a href="{{ route('curriculum.show', ['course' => $course->id, 'class_id' => $class->id]) }}" class="btn btn-primary mt-3">View Curriculum</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <p id="no-admin" class="p-2">No courses found for this school.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>


                  <div class="active tab-pane" id="lessons">
                    <div class="lessons-container">
                      <div class="row">
                       

                      @foreach ($top_lessons as $lesson)
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
                                              <a class="dropdown-item edit-lesson-btn" href="#" data-lesson-id="{{ $lesson->id }}">Edit</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removelessonModal{{ $lesson->id }}">Remove lesson</a>
                                          </div>
                                      </div>
                                  @endif

                                  <!-- Display lesson thumbnail -->
                                  <div class="thumbnail-container position-relative">
                                      <a href="#" class="lesson-link" data-lesson-id="{{ $lesson->id }}" data-lesson-title="{{ $lesson->title }}" data-school-connects-required="{{ $lesson->school_connects_required }}">
                                        <!-- Conditionally display enrollment badge -->
                                        @if ($lesson->enrolledUsers()->where('user_id', auth()->id())->exists())
                                              <span class="badge bg-purple" style="position:absolute; top:10; right:10; z-index:99;"><i class="fas fa-check"></i></span> <!-- Success badge with check icon -->
                                          @endif

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

                                  <p class="badge bg-purple" style="position:relative;  z-index:99;"><small><b>{{ $lesson->school_connects_required }} SC</b></small></p>
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


<div class="modal fade" id="schoolConnectsModal" tabindex="-1" aria-labelledby="schoolConnectsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title" id="schoolConnectsModalLabel">School Connects Required</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="connects-message" style="display:none;"></div>
                <div class="alert alert-danger" id="connects-error" style="display:none;"></div>
                <p>This lesson <b><span id="lessonName"></span></b> requires <span id="requiredConnects"></span> school connects to access.</p>
                <div id="connectsForm"  style="display:none">
                <div class="form-group">
                    <label for="connectAmount">Select Number of Connects:</label>
                    <select class="form-control" name="connectAmount" id="connectAmount">
                        <option value="500">90 Connects - ₦500</option>
                        <option value="1000">210 Connects - ₦1000</option>
                        <option value="2000">450 Connects - ₦2000</option>
                        <option value="3000">1000 Connects - ₦3000</option>
                    </select>
                </div>
                <button id="confirmBuyConnectsBtn" class="btn btn-success">Buy Connects</button>

                </div>
            </div>
            <div class="modal-footer" id="conect-modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmPlayBtn">Continue</button>
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
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

@endsection

@section('scripts')
<script>
    

    $(document).ready(function() {
        var canLoadMore = true;
        var currentPageLessons = 0;
        var currentPagePeople= 0;
        var displayedLessonIds = [];
        var displayedPeopleIds = [];
        var loadingLessons = false;
        var loadingPeople = false;

        // Function to populate displayedPeopleIds and displayedLessonIds from existing items on the page
    function populateDisplayedIds() {
        $('.people-card').each(function() {
            var peopleId = $(this).data('people-id');
            if (peopleId && !displayedPeopleIds.includes(peopleId)) {
                displayedPeopleIds.push(peopleId);
            }
        });
        console.log(displayedPeopleIds)

        $('.lesson-link').each(function() {
            var lessonId = $(this).data('lesson-id');
            if (lessonId && !displayedLessonIds.includes(lessonId)) {
                displayedLessonIds.push(lessonId);
            }
        });
    }

    // Call the function to populate displayed IDs when the page loads
    populateDisplayedIds();

        // Retrieve class_level value from PHP (Laravel) variable
        var class_level = '{{ $class->class_level }}'; // Echo the value directly
        var class_id = '{{ $class->id }}'; // Echo the value directly
        // var course = 'English Language'; // Echo the value directly

        // Log class_level to console for debugging
        console.log('Class Level:', class_level);

        // Event listener for tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            loadMoreData();
        });

        // Event listener for window scroll
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                console.log("Loading more data...");
                if (canLoadMore) {
                    loadMoreData(); // Trigger loading more data when near the bottom
                }
            }
        });

        // Function to load more data based on active tab
        function loadMoreData() {
            var activeTabId = $('.tab-pane.active').attr('id');

            if (activeTabId === 'people' && !loadingPeople) {
                loadMorePeople(activeTabId);
            } else if (activeTabId === 'lessons' && !loadingLessons) {
                loadMoreLessons(activeTabId);
            }
        }

        // Function to load more lessons
        function loadMoreLessons(activeTabId) {
            loadingLessons = true;

            $.ajax({
                url: "{{ route('load.more.lessons') }}",
                type: "GET",
                data: {
                    page: currentPageLessons,
                    displayedLessonIds: displayedLessonIds,
                    class_level: class_level, // Pass the class_level parameter
                    // course: course // Pass the class_level parameter
                },
                beforeSend: function() {
                    $('#loader').append('<div class="loader-container"><div class="loader"><i class="fas fa-spinner fa-spin"></i> Loading Lessons...</div></div>');
                },
                success: function(response) {
                    if (response && response.lessons && response.lessons.length > 0) {
                        currentPageLessons++;

                        $.each(response.lessons, function(index, item) {
                            if (!displayedLessonIds.includes(item.id)) {
                                // Create HTML for new lesson item
                                var newItem = createLessonHtml(item);

                                // Append new lesson HTML to container
                                $('#' + activeTabId + ' .lessons-container .row').append(newItem);

                                // Add lesson ID to displayedLessonIds
                                displayedLessonIds.push(item.id);
                            }
                        });
                    } else {
                        console.log("No more lessons data available.");
                        canLoadMore = false;
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText)
                    console.log("AJAX Lessons Error:", error);
                },
                complete: function() {
                    loadingLessons = false;
                    $('.loader-container').remove();
                }
            });
        }

         // Function to create HTML for a lesson item
         function createLessonHtml(item) {
            var html = '<div class="col-md-4">';
            html += '<div class="card lesson-card">';
            // html += '<p class="card lesson-card">'+item.id+'</p>';
            html += '<a href="#" class="lesson-link text-dark" data-lesson-id="' + item.id + '" data-lesson-title="' + item.title + '" data-school-connects-required="' + item.school_connects_required + '">';

            if (item.is_enrolled) {
                html += '<span class="badge bg-purple" style="position: absolute; top: 10px; left: 10px; z-index: 99;"><i class="fas fa-check"></i></span>';
            }

            html += '<div class="thumbnail-container position-relative">';
            if (item.thumbnail) {
                html += '<div class="thumbnail-with-play">';
                html += '<img src="' + item.thumbnail + '" alt="' + item.title + '" class="img-fluid lesson-thumbnail">';
                html += '<div class="play-icon-overlay"><i class="fas fa-play"></i></div>';
                html += '</div>';
            } else {
                html += '<div class="no-thumbnail">';
                html += '<div class="video-icon"><i class="fas fa-video"></i></div>';
                html += '<div class="overlay"></div>';
                html += '<img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">';
                html += '</div>';
            }
            html += '</div>';

            html += '<p><small><b>' + item.teacher_name + '</b></small></p>';
            html += '<h5><small>' + item.title + '</small></h5>';
            html += '<p><small>' + item.description + '</small></p>';
            html += '</a>';
            html += '</div>';
            html += '</div>';

            return html;
        }


       // Function to load more People
        function loadMorePeople(activeTabId) {
            loadingPeople = true;

            $.ajax({
                url: "{{ route('load.more.people') }}",
                type: "GET",
                data: {
                    page: currentPagePeople,
                    displayedPeopleIds: displayedPeopleIds,
                    class_level: class_level,
                    class_id: class_id,
                },
                beforeSend: function() {
                    $('#loader').append('<div class="loader-container"><div class="loader"><i class="fas fa-spinner fa-spin"></i> Loading People...</div></div>');
                },
                success: function(response) {
                    if (response && response.people && response.people.length > 0) {
                        currentPagePeople++;

                        $.each(response.people, function(index, person) {
                            if (!displayedPeopleIds.includes(person.id)) {
                                // Create HTML for new person item
                                var newPeopleHtml = createPeopleHtml(person);

                                // Append new person HTML to users-list
                                $('.users-list').append(newPeopleHtml);

                                // Add person ID to displayedPeopleIds
                                displayedPeopleIds.push(person.id);
                                console.log(displayedPeopleIds)
                                // console.log(displayedPeopleIds)
                            }
                        });
                    } else {
                        console.log("No more People data available.");
                        canLoadMore = false;
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText)
                    console.log("AJAX People Error:", error);
                },
                complete: function() {
                    loadingPeople = false;
                    $('.loader-container').remove();
                }
            });
        }

        function createPeopleHtml(person) {
            var html = '<li class="col-md-4 col-">';
            html += '<div class="card people-card" data-people-id="' + person.id + '" data-people-name="' + person.full_name + '">';
            html += '<div class="card-body">';
            html += '<div class="user-profile">';
            if (person.profile_picture) {
                html += '<img src="' + assetPath(person.profile_picture) + '" alt="User Image" width="150px">';
            } else {
                html += '<img src="' + assetPath('dist/img/avatar5.png') + '" alt="User Image" width="150px">';
            }
            html += '<a class="users-list-name" href="#" data-people-name="' + person.full_name + '">' + person.full_name + '<br>';
            html += '<span class="badge p-1 badge-info">' + person.class_code + '</span></a>';
            html += '</div>';
            html += '<div class="user-permissions">';
            html += '<h5 style="cursor:pointer;" class="details-heading toggle-details-btn" data-target="student-details-' + person.id + '">Details <i class="toggle-icon fas fa-chevron-down"></i></h5>';
            html += '<div class="collapsed-details" id="student-details-' + person.id + '" style="display: none;">'; // Initially hide the details
            html += ' <h5 style="cursor:pointer;"  class="detai-heading toggle-details-btn " data-target="student-details-'+ person.id+ '">Details <i class="toggle-icon fas fa-chevron-down"></i></h5>';
            html += '<p class="small-text"><strong>Email:</strong> ' + person.email + '</p>';
            html += '<p class="small-text"><strong>Phone:</strong> ' + (person.phone_number ? person.phone_number : 'N/A') + '</p>';
            html += '<p class="small-text"><strong>Gender:</strong> ' + person.gender + '</p>';
            html += '<p class="small-text"><strong>Date of Birth:</strong> ' + (person.date_of_birth ? person.date_of_birth : 'N/A') + '</p>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</li>';

            return html;
        }

        // Function to resolve asset paths based on the environment
        function assetPath(path) {
            // Replace this with your actual logic to resolve asset paths
            return '{{ asset('storage/') }}/' + path;
        }
        // Event listener for toggling details visibility
        $(document).ready(function() {
            // Event listener for toggling details visibility
            $(document).on('click', '.details-heading', function() {
                var targetId = $(this).data('target');
                $('#' + targetId).slideToggle();
                $(this).find('.toggle-icon').toggleClass('fa-chevron-down fa-chevron-up');
            });
        });
        $(document).ready(function() {
            // Event listener for toggling details visibility
            $(document).on('click', '.detai-heading', function() {
                var targetId = $(this).data('target');
                $('#' + targetId).slideToggle();
                $(this).find('.toggle-icon').toggleClass('fa-chevron-down fa-chevron-up');
            });
        });


      
        $(document).on('click', '.lesson-link', function(e) {
            e.preventDefault();
            const lessonId = $(this).data('lesson-id');
            const lessonName = $(this).data('lesson-title');
            const schoolConnectsRequired = $(this).data('school-connects-required');
            checkLessonEnrollment(lessonId, lessonName, schoolConnectsRequired);
        });
         // Function to check lesson enrollment status
    function checkLessonEnrollment(lessonId, lessonName, schoolConnectsRequired) {
        $.ajax({
            url: '/check-enrollment',
            method: 'POST',
            data: {
                lesson_id: lessonId
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.is_enrolled) {
                    // User is already enrolled in the lesson, route to lesson page
                    routeToLessonPage(lessonId);
                } else {
                    console.log(lessonId)
                    // User is not enrolled, display modal with required school connects information
                    displaySchoolConnectsModal(lessonName, schoolConnectsRequired, lessonId);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
                console.error('Error checking enrollment:', error);
                alert('Error checking enrollment. Please try again.');
            }
        });
    }

    // Function to route to lesson page
    function routeToLessonPage(lessonId) {
        window.location.href = '{{ route('lessons.show', ['lesson' => ':lessonId']) }}'.replace(':lessonId', lessonId);
    }

    // Function to display school connects modal
    function displaySchoolConnectsModal(lessonName, requiredConnects, lessonId) {
        $('#lessonName').text(lessonName);
        $('#requiredConnects').text(requiredConnects);
        $('#schoolConnectsModal').modal('show');

        // Handle click event for confirm play button
        $('#confirmPlayBtn').off('click').on('click', function() {
            const selectedConnectsAmount = $('#connectsAmount').val(); // Get selected connects amount
            console.log(selectedConnectsAmount)
            buySchoolConnects(lessonId, requiredConnects, selectedConnectsAmount);
        });
    }
    // Function to buy school connects
    function buySchoolConnects(lessonId, requiredConnects, selectedConnectsAmount) {
        $.ajax({
            url: '/check-school-connects',
            method: 'POST',
            data: {
                lesson_id: lessonId,
                required_connects: requiredConnects
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.has_enough_connects) {
                    // User has enough school connects, route to lesson page
                    routeToLessonPage(lessonId);
                } else {
                    displayMessageAndFadeOut('connects-error', 'You do not have enough connects.', 3000);
                    
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking school connects:', error);
                $('#connects-error').text('Error checking school connects. Please try again.').fadeIn();
                setTimeout(function() {
                    $('#connects-error').fadeOut();
                }, 3000);
            }
        });
    }

    });
</script>

@endsection
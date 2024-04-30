@extends('layouts.app')

@section('title')
CSS - {{auth()->user()->schoolClass()->name}}
@endsection

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection


@section('breadcrumb2')
<a href="#" class="btn-clear">{{auth()->user()->schoolClass()->code}}</a>
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
                  <li class="nav-item"><a class="nav-link active" href="#lessons" data-toggle="tab">Lessons</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  


                  <div class="active tab-pane" id="lessons">
                    <div class="lessons-container">
                      <div class="row">
                       

                      @foreach ($relevantLessons as $lesson)
                          <div class="col-md-4">
                              <div class="card lesson-card">
                                  

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
                                  <p> {{$lesson->class_level}} </p>

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
    var curriculumId = '{{ $curriculumId }}'
    var topicId = '{{ $topicId }}'
    console.log(curriculumId, topicId)
    $('.closeBtn').click(function(e){
        e.preventDefault();
        location.reload();
    });

    // Event listener for lesson link click
    $('.lesson-link').click(function(e) {
        e.preventDefault(); // Prevent default link behavior

        // Extract lesson details from the clicked link
        const lessonId = $(this).data('lesson-id');
        const lessonName = $(this).data('lesson-title');
        const schoolConnectsRequired = $(this).data('school-connects-required');

        // Perform AJAX request to check if user is already enrolled in the lesson
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
                    // User is not enrolled, display modal with required school connects information
                    displaySchoolConnectsModal(lessonName, schoolConnectsRequired, lessonId);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking enrollment:', error);
                alert('Error checking enrollment. Please try again.');
            }
        });
    }

    // Function to route to lesson page
    function routeToLessonPage(lessonId) {
        window.location.href = '{{ route('lessons.show', ['lesson' => ':lessonId']) }}'.replace(':lessonId', lessonId);
    }

    function displayMessageAndFadeOut(messageElementId, message, duration) {
        const messageElement = $(`#${messageElementId}`);
        messageElement.text(message).fadeIn();
        setTimeout(() => {
            messageElement.fadeOut();
            displayConnectsSelectionForm();
        }, duration);
    }

    // Function to display the connects selection form and hide the modal footer
    // function displayConnectsSelectionForm() {
    //     $('#connectsForm').show(); // Show the connects form
    //     $('#conect-modal-footer').hide(); // Hide the modal footer
    //     $('#schoolConnectsModal').modal('show'); // Show the modal
    // }

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
    function displayConnectsSelectionForm() {
        $('#connectsForm').show()
    }

    // Attach event handler using event delegation
    $(document).on('click', '#confirmBuyConnectsBtn', function() {
        const selectedConnectsAmount = $('#connectAmount').val(); // Get selected connects amount
        console.log('Selected Connects Amount:', selectedConnectsAmount);

        if (selectedConnectsAmount) {
            buyConnects(selectedConnectsAmount); // Call buyConnects function with selected amount
        } else {
            console.error('Selected Connects Amount is empty or invalid');
        }
    });
    $(document).on('click', '#confirmBuyConnectsErrorBtn', function() {
        const selectedConnectsAmount = $('#connectAmountError').val(); // Get selected connects amount
        console.log('Selected Connects Amount:', selectedConnectsAmount);

        if (selectedConnectsAmount) {
            buyConnects(selectedConnectsAmount); // Call buyConnects function with selected amount
        } else {
            console.error('Selected Connects Amount is empty or invalid');
        }
    });
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
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Perform AJAX request to buy more connects with selected price value
        $.ajax({
            url: buyConnectsRoute,
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
                console.error('Error buying connects:', error);
            }
        });
    }

</script>

<script>
    $(document).ready(function() {
    var canLoadMore = true;
    var currentPageLessons = 0;
    var displayedLessonIds = [];
    var loadingLessons = false;

    // Function to populate displayedEventIds and displayedLessonIds from existing items on the page
    function populateDisplayedIds() {
        

        $('.lesson-link').each(function() {
            var lessonId = $(this).data('lesson-id');
            if (lessonId && !displayedLessonIds.includes(lessonId)) {
                displayedLessonIds.push(lessonId);
            }
        });
    }

    // Call the function to populate displayed IDs when the page loads
    populateDisplayedIds();

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 108) {
            console.log("Loading more data...");
            if (canLoadMore) {
                loadMoreLessons(); // Trigger loading more data when near the bottom
            }
        }
    });


    function loadMoreLessons() {
        loadingLessons = true;

        $.ajax({
            url: "{{ route('load.more.curriculum.lessons') }}",
            type: "GET",
            data: {
                page: currentPageLessons,
                displayedLessonIds: displayedLessonIds,
                curriculum_id: curriculumId,
                topic_id: topicId,
            },
            beforeSend: function() {
                $('#loader').append('<div class="loader-container"><div class="loader"><i class="fas fa-spinner fa-spin"></i> Loading Lessons...</div></div>');
            },
            success: function(response) {
                if (response && response.lessons && response.lessons.length > 0) {
                    currentPageLessons++;

                    $.each(response.lessons, function(index, item) {
                        if (!displayedLessonIds.includes(item.id)) {
                            var newItem = createLessonHtml(item);
                            $('#lessons' + ' .lessons-container .row').append(newItem);
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

    function createLessonHtml(item) {
        var html = '<div class="col-md-4">';
        html += '<div class="card lesson-card">';
        html += '<a href="#" class="lesson-link text-dark" data-lesson-id="' + item.id + '" data-lesson-title="' + item.title + '" data-school-connects-required="' + item.school_connects_required + '">';

        if (item.is_enrolled == true) {
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
        html += '<p><small><b>' + item.class + '</b></small></p>';
        html += '<p><small><b>' + item.subject + '</b></small></p>';

        html += '<p><small><b>' + item.teacher_name + '</b></small></p>';
        html += '<h5><small>' + item.title + '</small></h5>';
        html += '<p><small>' + item.description + '</small></p>';
        html += '</a>';
        html += '</div>';
        html += '</div>';

        return html;
    }

 


    $(document).on('click', '.lesson-link', function(e) {
        e.preventDefault();
        const lessonId = $(this).data('lesson-id');
        const lessonName = $(this).data('lesson-title');
        const schoolConnectsRequired = $(this).data('school-connects-required');
        checkLessonEnrollment(lessonId, lessonName, schoolConnectsRequired);
    });
});

</script>
@endsection
<!-- resources/views/home.blade.php -->

@extends('layouts.app')

@section('title')
@if($school)
CSS - {{$school->name}}
@else
Central School System
@endif
@endsection

@section('breadcrumb3')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb2')
@if(auth()->user()->profile)
<span>{{auth()->user()->profile->role}}</span>
@endif
@endsection
@section('breadcrumb1')
@if(auth()->user()->profile)
<p>{{auth()->user()->profile->full_name}}</p>
@endif
@endsection
@section('sidebar')
    @include('sidebar')
@endsection
@section('style')
<style>
   /* Custom CSS for modals */
    .modal-content {
        border-radius: 10px;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
    }

    /* Optional: Custom button icons */
    .modal-footer .btn {
        padding: 8px 16px;
        font-size: 14px;
        font-weight: bold;
    }

    /* Example: Custom close button icon */
    .close {
        font-size: 24px;
        font-weight: bold;
    }




</style>
@endsection

@section('content')
<?php
$homepage = "users_homepage/homepage";
$userRole = auth()->user()->profile->role ?? null;

if ($userRole == "school_owner") {
    $homepage = "users_homepage/school_owner_homepage";
}elseif ($userRole == "teacher") {
    $homepage = "users_homepage/teacher_homepage";
}elseif ($userRole == "student") {
    $homepage = "users_homepage/student_homepage";
}elseif ($userRole == "staff") {
    $homepage = "users_homepage/staff_homepage";
}elseif ($userRole == "guardian") {
    $homepage = "users_homepage/guardian_homepage";
}elseif ($userRole == "super_admin") {
    $homepage = "users_homepage/super_admin_homepage";
}elseif ($userRole == "admin") {
    $homepage = "users_homepage/admin_homepage";
}

?>

@include($homepage)

<div class="modal fade" id="roleSelectionModal" tabindex="-1" aria-labelledby="roleSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Select Your Role</h5>
                <button type="button" onclick="hideModal()" class="btn-close btn-danger btn btn-sm" aria-label="Close"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.create') }}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">What Best Describes Your Position?</label>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="student" value="student">
                            <label class="form-check-label" for="student">Student</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="teacher" value="teacher">
                            <label class="form-check-label" for="teacher">Teacher</label>
                        </div>

                        <!-- <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="staff" value="staff">
                            <label class="form-check-label" for="staff">Staff</label>
                        </div> -->

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="admin" value="admin">
                            <label class="form-check-label" for="admin">Admin</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="guardian" value="guardian">
                            <label class="form-check-label" for="guardian">Guardian</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="school_owner" value="school_owner">
                            <label class="form-check-label" for="school_owner">School Owner</label><br>
                            <span class="text-danger"><small><em>select this if you own a school</em></small></span>
                        </div>

                        <!-- Add other role options -->
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary btn-block">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Success!</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-success" id="successMessage"></p>
                <p class="text-success" id="errorSuucessMessage"></p>
                <!-- <a href="" id="errorModalSuccessLink" class="btn btn-primary"></a> -->
                <div class="successForm"  style="display:none">
                <div class="form-group" >
                    <label for="connectsAmountSuccess">Get More Study Connects:</label>
                    <select class="form-control" id="connectsAmountSuccess" name ="connectsAmountSuccess">
                    <option value="500">90 Connects - ₦500</option>
                        <option value="1000">210 Connects - ₦1000</option>
                        <option value="2000">450 Connects - ₦2000</option>
                        <option value="3000">1000 Connects - ₦3000</option>
                    </select>
                </div>
                <a href="#" id="confirmBuySucessConnectsBtn" class="btn btn-success">Buy Connects</a>

                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="errorModalLabel">Error!</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-danger errorMessage" id="errorMessage"></p>
                <!-- <a href="" id="errorModalLink" class="btn btn-primary"></a> -->
                <div id="buyConnectForm">
                <label for="connectAmountError">Select Number of Connects:</label>
                    <select class="form-control" id="connectAmountError">
                        <option value="500">90 Connects - ₦500</option>
                        <option value="1000">210 Connects - ₦1000</option>
                        <option value="2000">450 Connects - ₦2000</option>
                        <option value="3000">1000 Connects - ₦3000</option>
                    </select>
                <button id="confirmBuyConnectsErrorBtn" class="btn btn-success">Buy Connects</button>

                </div>
                <div class="form-group">
                    
                </div>

            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>



<div class="modal fade" id="schoolConnectsModal" tabindex="-1" aria-labelledby="schoolConnectsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
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
@endsection

@section('scripts')
<script>


var uniqueSubjectNames = {!! json_encode($uniqueSubjectNames) !!};

    document.addEventListener('DOMContentLoaded', function () {
        // AJAX request to check if the user has a profile
        axios.get('/check-profile')
            .then(response => {
                if (!response.data.hasProfile) {
                    console.log(response.data.hasProfile);
                    // If the user has no profile, show the modal
                    $('#roleSelectionModal').modal('show');
                }
            })
            .catch(error => {
                console.error('Error checking profile:', error);
            });

        // Function to hide the modal
        window.hideModal = function () {
            $('#roleSelectionModal').modal('hide');
        };
    });
</script>

<script>
    $('.closeBtn').click(function(e){
        e.preventDefault();
        location.reload()
    })
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
    var currentPageEvents = 0;
    var displayedLessonIds = [];
    var displayedEventIds = [];
    var loadingLessons = false;
    var loadingEvents = false;

    // Function to populate displayedEventIds and displayedLessonIds from existing items on the page
    function populateDisplayedIds() {
        $('.timeline-item').each(function() {
            var eventId = $(this).data('event-id');
            if (eventId && !displayedEventIds.includes(eventId)) {
                displayedEventIds.push(eventId);
            }
        });

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
                loadMoreData(); // Trigger loading more data when near the bottom
            }
        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var activeTabId = $(e.target).attr('href');
        loadMoreData();
    });

    function loadMoreData() {
        var activeTabId = $('.tab-pane.active').attr('id');

        if (activeTabId === 'events' && !loadingEvents) {
            loadMoreEvents(activeTabId);
        } else if (activeTabId === 'lessons' && !loadingLessons) {
            loadMoreLessons(activeTabId);
        }
    }

    function loadMoreLessons(activeTabId) {
        loadingLessons = true;

        $.ajax({
            url: "{{ route('load.more.lessons') }}",
            type: "GET",
            data: {
                page: currentPageLessons,
                displayedLessonIds: displayedLessonIds
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
                            $('#' + activeTabId + ' .lessons-container .row').append(newItem);
                            displayedLessonIds.push(item.id);
                        }
                    });
                } else {
                    console.log("No more lessons data available.");
                    canLoadMore = false;
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

    function loadMoreEvents(activeTabId) {
        loadingEvents = true;

        $.ajax({
            url: "{{ route('load.more.events') }}",
            type: "GET",
            data: {
                page: currentPageEvents,
                displayedEventIds: displayedEventIds
            },
            beforeSend: function() {
                $('#loader').append('<div class="loader-container"><div class="loader"><i class="fas fa-spinner fa-spin"></i> Loading Events...</div></div>');
            },
            success: function(response) {
                if (response && response.events && response.events.length > 0) {
                    currentPageEvents++;

                    $.each(response.events, function(index, event) {
                        if (!displayedEventIds.includes(event.id)) {
                            var newEventHtml = createEventHtml(event);
                            $('#' + activeTabId + ' .timeline.timeline-inverse').append(newEventHtml);
                            displayedEventIds.push(event.id);
                        }
                    });
                } else {
                    console.log("No more events data available.");
                    canLoadMore = false;
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Events Error:", error);
            },
            complete: function() {
                loadingEvents = false;
                $('.loader-container').remove();
            }
        });
    }

    function createLessonHtml(item) {
        var html = '<div class="col-md-4">';
        html += '<div class="card lesson-card">';
        html += '<a href="#" class="lesson-link text-dark" data-lesson-id="' + item.id + '" data-lesson-title="' + item.title + '" data-school-connects-required="' + item.school_connects_required + '">';

        if (item.is_enrolled == true) {
            html += '<span class="badge bg-primary" style="position: absolute; top: 10px; left: 10px; z-index: 99;"><i class="fas fa-check"></i></span>';
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

    function createEventHtml(event) {
        var html = '<div class="timeline timeline-inverse">';
        html += '<div class="timeline-item" data-event-id="' + event.id + '">';

        html += '<div class="time-label">';
        html += '<span class="badge bg-blue">' + event.start_date + '</span>';
        html += '</div>';

        html += '<div class="timeline-body">';
        html += '<span class="time"><i class="far fa-clock"></i> ' + event.start_time + '</span>';
        html += '<h4 class="timeline-header"><a href="#">' + event.title + '</a></h4>';
        html += '<b class="timeline-header"><a href="#">' + event.school_name + '</a></b>';

        if (event.banner_picture) {
            html += '<div><a href="#"><img src="' + event.banner_picture + '" alt="Event Banner" class="img-fluid mt-2" style="max-width: 200px;"></a></div>';
        }

        html += '<div class="timeline-description">' + event.description + '</div>';

        html += '<div class="timeline-footer">';
        html += '<div><i class="fa fa-heart p-2"></i><i class="fa fa-comments p-2"></i></div>';
        html += '<p class="badge bg-primary"><a href="#">' + event.academic_session_name + '</a></p>';
        html += '</div>';

        html += '</div>'; // Close timeline-body
        html += '</div>'; // Close timeline-item
        html += '</div>'; // Close timeline

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

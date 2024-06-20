@extends('layouts.app')

@section('title')
    Central School System
@endsection

@section('breadcrumb1')
   <a href="{{route('home')}}">Home</a>
@endsection


@section('breadcrumb2', "Search")
  

@section('sidebar')
    @include('sidebar')
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
    .people-card {
    /* border: 1px solid #000; */
    height:360px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.people-card:hover {
    transform: scale(1.05);
}

.people-card img {
    border: 2px solid #ddd;
}

.people-list .card-body {
    padding: 1.5rem;
}
.school-logo {
        max-width: 120px;
        border-radius: 50%;
        margin-bottom: 15px;
    }

    .user-logo {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 2px solid #ddd;
}

.users-list .card {
    height: 360px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.users-list .card:hover {
    transform: scale(1.05);
}

.users-list .card-body {
    padding: 1.5rem;
}

</style>


@endsection


@section('content')
<!-- Display User Results -->
<div class="lessons-container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link{{ setActiveTab($results, 'lessons') }}" href="#lessons" data-toggle="tab">Lessons</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ setActiveTab($results, 'events') }}" href="#events" data-toggle="tab">Events</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ setActiveTab($results, 'users') }}" href="#people" data-toggle="tab">People</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ setActiveTab($results, 'schools') }}" href="#schools" data-toggle="tab">Schools</a>
                        </li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Lessons Tab -->
                        <div class="tab-pane{{ setActiveTab($results, 'lessons') }}" id="lessons">
                            @include('partials.search_partials.lessons', ['results' => $results['lessons'], 'term' => $term])
                        </div>
                        <!-- Events Tab -->
                        <div class="tab-pane{{ setActiveTab($results, 'events') }}" id="events">
                            @include('partials.search_partials.events', ['results' => $results['events'], 'term' => $term])
                        </div>
                        <!-- People Tab -->
                        <div class="tab-pane{{ setActiveTab($results, 'users') }}" id="people">
                            @include('partials.search_partials.users', ['results' => $results['users'], 'term' => $term])
                        </div>
                        <!-- Schools Tab -->
                        <div class="tab-pane{{ setActiveTab($results, 'schools') }}" id="schools">
                            @include('partials.search_partials.schools', ['results' => $results['schools'], 'term' => $term])
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

@include('partials.search_partials.modals')
@endsection

@php
function setActiveTab($results, $tabName) {
    // Get the count of each tab's results
    $lessonCount = $results['lessons']->count();
    $peopleCount = $results['users']->count();
    $eventCount = $results['events']->count();
    $schoolCount = $results['schools']->count();

    // Determine the tab with the highest count by default
    $maxCount = max($lessonCount, $peopleCount, $eventCount, $schoolCount);

    if ($maxCount === $lessonCount) {
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($maxCount === $peopleCount) {
        return ($tabName === 'users') ? ' active' : '';
    } elseif ($maxCount === $eventCount) {
        return ($tabName === 'events') ? ' active' : '';
    } elseif ($maxCount === $schoolCount) {
        return ($tabName === 'schools') ? ' active' : '';
    }

    // If counts are equal, prioritize based on specific conditions
    if ($lessonCount === $peopleCount && $lessonCount === $eventCount && $lessonCount === $schoolCount) {
        // All counts are equal, prioritize lessons > people > events > schools
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($lessonCount === $peopleCount && $lessonCount === $eventCount) {
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($lessonCount === $peopleCount && $lessonCount === $schoolCount) {
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($lessonCount === $eventCount && $lessonCount === $schoolCount) {
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($peopleCount === $eventCount && $peopleCount === $schoolCount) {
        return ($tabName === 'users') ? ' active' : '';
    } elseif ($lessonCount === $peopleCount) {
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($lessonCount === $eventCount) {
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($lessonCount === $schoolCount) {
        return ($tabName === 'lessons') ? ' active' : '';
    } elseif ($peopleCount === $eventCount) {
        return ($tabName === 'users') ? ' active' : '';
    } elseif ($peopleCount === $schoolCount) {
        return ($tabName === 'users') ? ' active' : '';
    } elseif ($eventCount === $schoolCount) {
        return ($tabName === 'events') ? ' active' : '';
    }

    return ''; // Default: No active tab
}
@endphp



@section('scripts')

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
    var currentPagePeople= 0;
    var displayedLessonIds = [];
    var displayedEventIds = [];
    var loadingLessons = false;
    var loadingEvents = false;
    var displayedPeopleIds = [];
    var loadingPeople = false;
    var term = '{{ $term }}'
    console.log(term)

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
        $('.people-card').each(function() {
            var peopleId = $(this).data('people-id');
            if (peopleId && !displayedPeopleIds.includes(peopleId)) {
                displayedPeopleIds.push(peopleId);
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
        } else if (activeTabId === 'people' && !loadingPeople) {

                console.log(displayedPeopleIds)
                loadMorePeople(activeTabId);
            }
    }

    function loadMoreLessons(activeTabId) {
        loadingLessons = true;

        $.ajax({
            url: "{{ route('load.more.lessons') }}",
            type: "GET",
            data: {
                page: currentPageLessons,
                displayedLessonIds: displayedLessonIds,
                term: term
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

        html += '<p><small><b>' + item.teacher_name + '</b></small></p>';
        html += '<h5><small>' + item.title + '</small></h5>';
        html += '<p><small>' + item.description + '</small></p>';
        html += '</a>';
        html += '</div>';
        html += '</div>';

        return html;
    }

    function loadMoreEvents(activeTabId) {
        loadingEvents = true;

        $.ajax({
            url: "{{ route('load.more.events') }}",
            type: "GET",
            data: {
                page: currentPageEvents,
                displayedEventIds: displayedEventIds,
                term: term
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
        html += '<p class="badge bg-purple"><a href="#">' + event.academic_session_name + '</a></p>';
        html += '</div>';

        html += '</div>'; // Close timeline-body
        html += '</div>'; // Close timeline-item
        html += '</div>'; // Close timeline

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
            term: term
        },
        beforeSend: function() {
            $('#loader').append('<div class="loader-container"><div class="loader"><i class="fas fa-spinner fa-spin"></i> Loading People...</div></div>');
        },
        success: function(response) {
            if (response && response.people && response.people.length > 0) {
                currentPagePeople++;

                $.each(response.people, function(index, person) {
                    if (!displayedPeopleIds.includes(person.id)) {
                        var newPeopleHtml = createPeopleHtml(person);
                        $('.people-list').append(newPeopleHtml);
                        displayedPeopleIds.push(person.id);
                    }
                });

                console.log(displayedPeopleIds);
            } else {
                console.log("No more People data available.");
                canLoadMore = false;
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
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
    html += '<p class=""><b>' + person.full_name + '</b></p>';
    if (person.profile_picture) {
        html += '<img src="' + assetPath(person.profile_picture) + '" alt="User Image" width="150px">';
    } else {
        html += '<img src="{{ asset('dist/img/avatar.png') }}" alt="Default Thumbnail" class="img-fluid" width="150px">';
    }
    html += '<br><span class="badge p-1 badge-info">' + person.role + '</span></a>';
    html += '</div>';
    html += '<div class="user-permissions">';
    html += '<h5 style="cursor:pointer;" class="detail-heading toggle-details-btn btn btn-info" data-target="student-details-' + person.id + '">Details <i class="toggle-icon fas fa-chevron-down"></i></h5>';
    html += '<div class="collapsed-details" id="student-details-' + person.id + '" style="display: none;">'; // Initially hide the details
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
            $(document).on('click', '.detail-heading', function() {
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
});

</script>

@endsection

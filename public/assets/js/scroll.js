$(document).ready(function() {
    var currentPageLessons = 0;
    var currentPageEvents = 0;
    var displayedLessonIds = [];
    var displayedEventIds = [];
    var loadingLessons = false;
    var loadingEvents = false;

    // Function to load more data when scrolling near the bottom
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
            console.log("Loading more data...");
            loadMoreData(); // Trigger loading more data when near the bottom
        }
    });

    // Function to handle tab activation and bind scroll event accordingly
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var activeTabId = $(e.target).attr('href'); // Get the href of the activated tab
        loadMoreData(); // Trigger initial loading when tab is activated
    });

    // Function to load more data based on the active tab
    function loadMoreData() {
        var activeTabId = $('.tab-pane.active').attr('id');

        if (activeTabId === 'events' && !loadingEvents) {
            loadMoreEvents(activeTabId);
        } else if (activeTabId === 'lessons' && !loadingLessons) {
            loadMoreLessons(activeTabId);
        }
    }

    // Function to load more lessons via AJAX
    function loadMoreLessons(activeTabId) {
        loadingLessons = true;

        // AJAX request to load more lessons
        $.ajax({
            url: loadMoreLessonsRoute,
            type: "GET",
            data: {
                page: currentPageLessons,
                displayedLessonIds: displayedLessonIds
            },
            // Handle success and error callbacks
            success: function(response) {
                // Process response data and append to the lessons container
                if (response && response.lessons && response.lessons.length > 0) {
                    // Append lessons to the DOM
                    $.each(response.lessons, function(index, item) {
                        // Construct HTML for each lesson item and append to container
                        var lessonHtml = '<div class="col-md-4">';
                        // Customize HTML based on item data
                        // Append to lessons container
                        $('#' + activeTabId + ' .lessons-container .row').append(lessonHtml);
                    });
                    currentPageLessons++; // Increment page counter
                } else {
                    console.log("No more lessons data available.");
                    $(window).off('scroll'); // Turn off scroll event if no more data
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Lessons Error:", error);
            },
            complete: function() {
                loadingLessons = false; // Reset loading flag
            }
        });
    }

    // Function to load more events
    function loadMoreEvents(activeTabId) {
        loadingEvents = true;

        $.ajax({
            url: loadMoreEventsRoute,
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
                    console.log("AJAX Events Response:", response);
                    currentPageEvents++;

                    $.each(response.events, function(index, event) {
                        if (!displayedEventIds.includes(event.id)) {
                            // Construct HTML for event item
                            var newEvent ='<div class="timeline timeline-inverse">'
                            newEvent += '<div class="time-label">';
                            newEvent += '<span class="badge bg-blue">' + event.start_date + '</span>';
                            newEvent += '</div>';
                            newEvent += '<div class="timeline-item" data-event-id="' + event.id + '">';
                            newEvent += '<span class="time"><i class="far fa-clock"></i> ' + event.start_time + '</span>';
                            newEvent += '<h4 class="timeline-header"><a href="#">' + event.title + '</a></h4>';
                            newEvent += '<div class="timeline-body">';
                            newEvent += '<b class="timeline-header"><a href="#">' + event.school_name + '</a></b>';

                            if (event.banner_picture) {
                                newEvent += '<div><a href="#"><img src="' + event.banner_picture + '" alt="Event Banner" class="img-fluid mt-2" style="max-width: 200px;"></a></div>';
                            }

                            newEvent += event.description;
                            newEvent += '</div>';
                            newEvent += '<div class="timeline-footer">';
                            newEvent += '<div><i class="fa fa-heart p-2"></i><i class="fa fa-comments p-2"></i></div>';
                            newEvent += '<p class="badge bg-purple"><a href="#">' + event.academic_session_name + '</a></p>';
                            newEvent += '</div>';
                            newEvent += '</div>';
                            newEvent += '</div>';

                            // Append the new event item to events container
                            $('#' + activeTabId + ' .timeline.timeline-inverse').append(newEvent);

                            // Track displayed event IDs
                            displayedEventIds.push(event.id);
                        }
                    });
                } else {
                    console.log("No more events data available.");
                    $(window).off('scroll'); // Turn off scroll event to stop further pagination
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Events Error:", error);
            },
            complete: function() {
                loadingEvents = false; // Reset loading flag
                $('.loader-container').remove();
            }
        });
    }


    // Event delegation for dynamically added lesson-link elements
    $(document).on('click', '.lesson-link', function(e) {
        e.preventDefault(); // Prevent default link behavior

        // Extract lesson details from the clicked link
        const lessonId = $(this).data('lesson-id');
        const lessonName = $(this).data('lesson-title');
        const schoolConnectsRequired = $(this).data('school-connects-required');

        // Perform AJAX request to check lesson enrollment
        checkLessonEnrollment(lessonId, lessonName, schoolConnectsRequired);
    });

});

@extends('layouts.app')

@section('title', 'CSS - ' . $lesson->title)

@section('style')
<style>
    .video-container {
        position: relative;
        width: 100%;
        max-width: 800px;
    }

    video {
        width: 100%;
        display: block;
    }

    .custom-controls {
        position: absolute;
        bottom: 10px;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        color: white;
    }

    .control-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 36px;
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .control-range {
        width: 100px;
    }

    .current-time, .duration {
        font-size: 14px;
    }
    
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }

    .input-group .form-control,
    .input-group .btn {
        position: relative;
        flex: 1 1 auto; /* This makes both elements equally share the width */
        height: auto; /* Reset height to auto to match content height */
    }

    .input-group-append {
        display: flex;
    }

    /* Adjust button height and icon alignment */
    .input-group-append .btn {
        height: auto; /* Reset button height to match input height */
        display: flex;
        align-items: center;
    }
</style>

@endsection
@section('breadcrumb3')
<small class="small-text">{{$lesson->title}}</small>
@endsection
@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection

@section('content')
@include('sidebar')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h4>{{ $lesson->title }}</h4>

                <!-- Display the video -->
                <div class="video-container">
                    <video id="lessonVideo" width="100%">
                        <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <button id="playPauseBtn" class="control-btn"><i class="fas fa-play"></i></button>
                    <div class="custom-controls">
                        <input id="volumeRange" type="range" class="control-range" min="0" max="1" step="0.1" value="1">
                        <span id="currentTime" class="current-time">0:00</span>
                        <input id="progressRange" type="range" class="control-range" min="0" max="100" step="0.1" value="0">
                        <span id="duration" class="duration">0:00</span>
                    </div>
                </div>
                <h5 class="mt-3"><b>Description</b></h5>
                <p>{{ $lesson->description }}</p>
                <p>{{ $lesson->class_level }}</p>
                <p>
                    <!-- <a href="#" class="text-sm text-purple mr-2 share-link"><i class="far fa-paper-plane mr-1"></i> Share</a> -->
                    @php
                        $lessonId = $lesson->id;
                        $isLiked = Auth::check() && Auth::user()->likedLessons()->where('lesson_id', $lessonId)->exists();
                        $likeCount = $lesson->likedUsers()->count();

                        $isFavorited = Auth::check() && Auth::user()->favoriteLessons()->where('lesson_id', $lessonId)->exists();
                        $favoriteCount = $lesson->favoritedByUsers()->count();
                    @endphp

                    <!-- Like Link -->
                    <a href="#" class="text-sm text-primary mr-3 like-link {{ $isLiked ? 'liked' : '' }}" data-lesson-id="{{ $lessonId }}">
                        <i class="{{ $isLiked ? 'fas' : 'far' }} fa-thumbs-up mr-1"></i>
                        <span id="liked">{{ $isLiked ? 'Liked' : 'Like' }}</span>
                        <span class="ml-1">( <span id="like-count">{{ $likeCount }}</span> )</span>
                    </a>

                    <!-- Favorite Link -->
                    <a href="#" class="text-sm text-success mr-3 add-to-favorite-link mr-2 {{ $isFavorited ? 'added-to-favorites' : '' }}" data-lesson-id="{{ $lessonId }}">
                        <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart mr-1"></i>
                        <span id="favourite">{{ $isFavorited ? 'Added to Favorites' : 'Add to Favorites' }}</span>
                        <span class="ml-1">( <span id="favourite-count">{{ $favoriteCount }}</span> )</span>
                    </a>






                        @php
                            $totalComments = count($lesson->comments);
                            $totalReplies = 0;

                            $total = $totalComments + $totalReplies;
                        @endphp

                        <span class="float-right">
                            <a href="#" class="link-black text-sm">
                                <i class="far fa-comments mr-1"></i> Comments ( <span id="comment-count">{{ $total }}</span> )
                            </a>
                        </span>

                      </p>

                <!-- Add more details or information about the lesson -->
                <div class="comment-section mt-4 mb-3">

                        <!-- Comment Form -->
                        <p class="alert alert-danger p-1" id="comment-error" style="display: none;"></p>
                        <p class="alert alert-success p-1" id="comment-message" style="display: none;"></p>
                        <p class="alert alert-danger p-1" id="reply-error" style="display: none;"></p>
                        <p class="alert alert-success p-1" id="reply-message" style="display: none;"></p>
                        <form id="commentForm" action="{{ route('comment.store', ['lesson' => $lesson->id]) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="input-group">
                                <input id="commentContent" type="text" name="comment_content" class="form-control form-control-sm" placeholder="Type a comment">
                                <div class="input-group-append">
                                    <button id="submitComment" type="button" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </form>
                                        
                    <!-- Display existing comments -->
                    <div class="card">
                        <div class="card-body comments-section" style="height: 300px; overflow-y: auto;">
                            <h6>Comments</h6>

                            @foreach ($lesson->comments as $comment)
                                <div class="comment mb-3" data-comment-id="{{ $comment->id }}">
                                    <div class="comment-header">
                                        <strong>{{ $comment->user->profile->full_name }}</strong>
                                        <small class="text-muted"><b>{{ $comment->created_at->diffForHumans() }}</b></small>
                                    </div>
                                    <div class="comment-body">
                                        {{ $comment->content }}
                                    </div>
                                    <div class="comment-footer">
                                        @if ($comment->replies->isNotEmpty())
                                            <button class="btn btn-sm btn-clear text-purple toggle-replies" data-comment-id="{{ $comment->id }}">
                                                <i class="fas fa-comment-alt"></i> {{ $comment->replies->count() }} Replies
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-clear text-primary toggle-reply-form" data-comment-id="{{ $comment->id }}">
                                            <i class="fas fa-reply-all"></i> Reply
                                        </button>
                                    </div>

                                    <!-- Reply Form for this comment -->
                                    <form action="{{ route('comment.reply', ['comment' => $comment->id]) }}" method="POST" class="reply-form mt-2" id="comment-reply-{{$comment->id}}" style="display:none;">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" name="reply_content" class="form-control" placeholder="Type a reply">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn bg-purple"><i class="fas fa-paper-plane"></i></button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Display replies to this comment -->
                                    <div class="replies-container" id="replies-container-{{$comment->id}}" style="display:none;">
                                        @foreach ($comment->replies as $reply)
                                            @if (!$reply->parent_reply_id)
                                                <div class="reply ml-4 mb-2" data-reply-id="{{ $reply->id }}">
                                                    <div class="reply-header">
                                                        <strong>{{ $reply->user->profile->full_name }}</strong>
                                                        <small class="text-muted small-text"><b>{{ $reply->created_at->diffForHumans() }}</b></small>
                                                    </div>
                                                    <div class="reply-body">
                                                        {{ $reply->content }}
                                                    </div>
                                                    <div class="reply-footer">
                                                        @if ($reply->replies->isNotEmpty())
                                                            <button class="btn btn-sm btn-clear text-purple toggle-nested-replies" data-reply-id="{{ $reply->id }}">
                                                                <i class="fas fa-comment-alt"></i> {{ $reply->replies->count() }} Replies
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-sm btn-clear text-primary toggle-reply-form" data-comment-id="{{ $comment->id }}" data-parent-reply-id="{{ $reply->id }}">
                                                            <i class="fas fa-reply-all"></i> Reply
                                                        </button>
                                                    </div>

                                                    <!-- Reply Form for this reply -->
                                                    <form action="{{ route('comment.reply', ['comment' => $comment->id]) }}" method="POST" class="reply-form mt-2" id="form-reply-{{$reply->id}}" style="display:none;">
                                                        @csrf
                                                        <input type="hidden" name="parent_reply_id" value="{{ $reply->id }}">
                                                        <div class="input-group">
                                                            <input type="text" name="reply_content" class="form-control" placeholder="Type a reply">
                                                            <div class="input-group-append">
                                                                <button type="submit" class="btn bg-purple"><i class="fas fa-paper-plane"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Nested replies container -->
                                                    <div class="nested-replies ml-4" id="nested-replies-{{$reply->id}}" style="display:none;">
                                                        @include('partials.replies', ['replies' => $reply->replies])
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
    <h5>Related Lessons</h5>

    <div class="list-group">
        @foreach ($relatedLessons as $relatedLesson)
        <a href="#" class="lesson-link list-group-item list-group-item-action" data-lesson-id="{{ $relatedLesson->id }}" data-lesson-title="{{ $relatedLesson->title }}" data-school-connects-required="{{ $relatedLesson->school_connects_required }}">
                <div class="row">
                    <!-- Thumbnail container -->
                    <div class="col-4">
                    @if ($relatedLesson->enrolledUsers()->where('user_id', auth()->id())->exists())
                        <span class="badge bg-purple small-text" style="position:absolute; top:10; right:10; z-index:99;"><i class="fas fa-check"></i></span> <!-- Success badge with check icon -->
                    @endif
                        <div class="thumbnail-container">
                            @if ($relatedLesson->thumbnail)
                                <div class="thumbnail-with-play">
                                    <img src="{{ asset($relatedLesson->thumbnail) }}" alt="{{ $relatedLesson->title }}" class="img-fluid lesson-thumbnail w-100 h-100">
                                    <div class="play-icon-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Default thumbnail with play icon -->
                                <div class="no-thumbnail">
                                    <div class="video-icon">
                                        <i class="fas fa-video"></i>
                                    </div>
                                    <div class="overlay"></div>
                                    <img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Lesson details -->
                    <div class="col-8">
                        <div class="lesson-details small-text">
                            <h6 class="small-text"><b>{{ $relatedLesson->title }}</b></h6>
                            <p class="small-text">{{ \Illuminate\Support\Str::limit($relatedLesson->description, 100) }}</p>
                            <!-- <div class="d-flex justify-content-between align-items-center">
                                <small class="small-text"><strong>Class Level:</strong> {{ $relatedLesson->class_level }}</small>
                                <small class="small-text"><strong>Subject:</strong> {{ $relatedLesson->subject }}</small>
                            </div> -->
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
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



        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    const video = document.getElementById('lessonVideo');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const volumeRange = document.getElementById('volumeRange');
    const currentTimeDisplay = document.getElementById('currentTime');
    const progressRange = document.getElementById('progressRange');
    const durationDisplay = document.getElementById('duration');

    // Play/pause functionality
    function togglePlayPause() {
        if (video.paused) {
            video.play();
            playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            video.pause();
            playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    }

    // Update volume
    volumeRange.addEventListener('input', function() {
        video.volume = volumeRange.value;
    });

    // Update current time and progress
    video.addEventListener('timeupdate', function() {
        currentTimeDisplay.textContent = formatTime(video.currentTime);
        progressRange.value = (video.currentTime / video.duration) * 100;
    });

    // Seek video on progress bar click
    progressRange.addEventListener('input', function() {
        video.currentTime = (progressRange.value / 100) * video.duration;
    });

    // Display total duration
    video.addEventListener('loadedmetadata', function() {
        durationDisplay.textContent = formatTime(video.duration);
    });

    // Format time in MM:SS format
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }

    // Initialize video controls
    playPauseBtn.addEventListener('click', togglePlayPause);
</script>

<script>
   $(document).ready(function() {
    // Submit comment using Ajax
    $('#submitComment').on('click', function() {
        postComment();
    });

    // Enable Enter key to submit comment
    $('#commentContent').keypress(function(event) {
        if (event.which == 13) { // 13 is the Enter key code
            event.preventDefault(); // Prevent default form submission behavior
            postComment();
        }
    });

    // Submit reply using Ajax
    $('.reply-form').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission behavior
        var replyContent = $(this).find('input[name="reply_content"]').val();

        if (replyContent.trim() === '') {
            $('#reply-error').text('Please enter a reply').fadeIn().delay(3000).fadeOut();
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#reply-message').text('Reply posted successfully').fadeIn().delay(3000).fadeOut();
                // Optional: Update UI to display the new reply
                $(event.target).find('input[name="reply_content"]').val(''); // Clear reply input field
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
                console.error('Error posting reply:', error);
                $('#reply-error').text('Error posting reply. Please try again.').fadeIn().delay(3000).fadeOut();
            }
        });
    });

    // Enable Enter key to submit reply
    $('.reply-form input[name="reply_content"]').keypress(function(event) {
        if (event.which == 13) { // 13 is the Enter key code
            event.preventDefault(); // Prevent default form submission behavior
            $(this).closest('form').submit(); // Trigger the form submit for the reply
        }
    });

    // Function to post comment via Ajax
    function postComment() {
        var commentContent = $('#commentContent').val();

        if (commentContent.trim() === '') {
            $('#comment-error').text('Please enter a comment').fadeIn().delay(3000).fadeOut();
            return;
        }

        $.ajax({
            url: $('#commentForm').attr('action'),
            method: 'POST',
            data: $('#commentForm').serialize(),
            success: function(response) {
                $('#comment-message').text('Comment posted successfully').fadeIn().delay(3000).fadeOut();
                $('#commentContent').val(''); // Clear input field after successful submission
                // Optional: Reload comments or update UI as needed
            },
            error: function(xhr, status, error) {
                console.error('Error posting comment:', error);
                $('#comment-error').text('Error posting comment. Please try again.').fadeIn().delay(3000).fadeOut();
            }
        });
    }
});

</script>
<script>
     $(document).ready(function() {
        // Show/hide comment reply form when clicking on a comment
        $('[id^=comment-]').click(function() {
            var commentId = $(this).attr('id').split('-')[1];
            $('#comment-reply-' + commentId).toggle();
        });

        // Show/hide reply form for each reply when clicking on a reply
        $('[id^=reply-]').click(function() {
            var replyId = $(this).attr('id').split('-')[1];
            $('#form-reply-' + replyId).toggle();
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Toggle nested replies visibility
        $('.toggle-nested-replies').click(function() {
        var replyId = $(this).data('reply-id');
        var nestedReplies = $('#nested-replies-' + replyId);

        nestedReplies.toggle();

        // Toggle caret icon based on visibility
        var icon = $(this).find('i.fa-caret-down, i.fa-caret-up');
        icon.toggleClass('fa-caret-down fa-caret-up');
    });

        // Other toggle functions for comments and reply forms
        $('.toggle-replies').click(function() {
            var commentId = $(this).data('comment-id');
            $('#replies-container-' + commentId).toggle();
        });

        $('.toggle-reply-form').click(function() {
            var commentId = $(this).data('comment-id');
            var parentReplyId = $(this).data('parent-reply-id');
            var formId = parentReplyId ? 'form-reply-' + parentReplyId : 'comment-reply-' + commentId;
            $('#' + formId).toggle();
        });
    });
</script>

<script>
 $(document).ready(function() {
    $('.add-to-favorite-link').click(function(e) {
        e.preventDefault();
        
        var link = $(this); // Store the link element
        var lessonId = link.data('lesson-id');
        var token = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/lessons/' + lessonId + '/favorite',
            type: 'POST',
            data: {
                _token: token
            },
            success: function(response) {
                // Toggle icon and text based on response
                if (response.message === 'Lesson added to favorites') {
                    link.find('i').removeClass('far fa-heart').addClass('fas fa-heart'); // Change heart icon to solid
                    // link.removeClass('text-success').addClass('text-success'); // Change text color to success
                    $('#favourite').text('Added to Favorites'); // Update link text
                } else if (response.message === 'Lesson removed from favorites') {
                    link.find('i').removeClass('fas fa-heart').addClass('far fa-heart'); // Change heart icon to empty
                    // link.removeClass('text-success').addClass('text-dark'); // Change text color back to dark
                    $('#favourite').text('Add to Favorites'); // Update link text
                }
                var newFavoriteCount = response.favorite_count;
                console.log(newFavoriteCount)
                $('#favourite-count').text('');
                $('#favourite-count').text(newFavoriteCount);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                // Handle errors
            }
        });
    });
    });

    $(document).ready(function() {
        $('.like-link').click(function(e) {
            e.preventDefault();
            
            var link = $(this); // Store the link element
            var lessonId = link.data('lesson-id');
            var token = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: '/lessons/' + lessonId + '/like',
                type: 'POST',
                data: {
                    _token: token
                },
                success: function(response) {
                    if (response.liked) {
                        link.addClass('liked');
                        link.find('i').removeClass('far fa-thumbs-up').addClass('fas fa-thumbs-up'); // Change icon to solid thumbs-up
                        $('#liked').text('Liked');
                    } else {
                        link.removeClass('liked');
                        link.find('i').removeClass('fas fa-thumbs-up').addClass('far fa-thumbs-up'); // Change icon to regular thumbs-up
                        $('#liked').text('Like');
                    }
                var newLikeCount = response.like_count;
                    console.log(newLikeCount)
                $('#like-count').text('');
                $('#like-count').text(newLikeCount);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle errors
                }
            });
        });
    });


</script>


<script>
 $(document).ready(function() {
        // Function to fetch and append new comments
        function fetchAndAppendNewComments() {
            var lastDisplayedCommentId = $('.comment:last').data('comment-id');

            $.ajax({
                url: '{{ route('lesson.comments', ['lesson' => $lesson->id]) }}',
                type: 'GET',
                data: {
                    last_displayed_comment_id: lastDisplayedCommentId
                },
                success: function(response) {
                    // Check if there are new comments to append
                    if (response.comments.length > 0) {
                        response.comments.forEach(function(comment) {
                            var commentId = comment.id;
                            var createdAt = new Date(comment.created_at).toLocaleString();

                            // Check if comment is not already displayed
                            if ($(`.comment[data-comment-id="${commentId}"]`).length === 0) {
                                // Create new comment HTML
                                var newCommentHtml = `
                                    <div class="comment mb-3" data-comment-id="${commentId}">
                                        <div class="comment-header">
                                            <strong>${comment.user.profile.full_name}</strong>
                                            <small class="text-muted"><b>${createdAt}</b></small>
                                        </div>
                                        <div class="comment-body">
                                            ${comment.content}
                                        </div>
                                        <div class="comment-footer">
                                            <button class="btn btn-sm btn-clear text-primary toggle-repl-form" data-comment-id="${commentId}">
                                                <i class="fas fa-reply-all"></i> Reply
                                            </button>
                                        </div>
                                        <!-- Reply Form for this comment -->
                                        <form action="{{ route('comment.reply', ['comment' => ':commentId']) }}" method="POST" class="reply-for mt-2" id="comment-reply-${commentId}" style="display:none;">
                                            @csrf
                                            <div class="input-group">
                                                <input type="hidden" name="comment_id" value="${commentId}">
                                                <input type="text" name="reply_content" class="form-control" placeholder="Type a reply">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn bg-purple"><i class="fas fa-paper-plane"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                `;

                                // Append new comment HTML to comments section
                                $('.comments-section').append(newCommentHtml);
                            }
                        });

                        // Update comment count
                        $('#comment-count').text(response.comment_count);
                    }
                },
                error: function(xhr) {
                    console.log('Error fetching new comments:', xhr.responseText);
                }
            });
        }


        // Toggle reply form visibility on button click
        $(document).on('click', '.toggle-repl-form', function() {
            var commentId = $(this).data('comment-id');
            $(`#comment-reply-${commentId}`).toggle();
        });

        // Submit reply using Ajax
        $(document).on('submit', '.reply-for', function(event) {
            event.preventDefault(); // Prevent default form submission behavior

            var replyForm = $(this); // Reference to the submitted form
            var replyContent = replyForm.find('input[name="reply_content"]').val();

            if (replyContent.trim() === '') {
                $('#reply-error').text('Please enter a reply').fadeIn().delay(3000).fadeOut();
                return;
            }

            // Get the comment ID from the form's hidden input field
            var commentId = replyForm.find('input[name="comment_id"]').val();

            // Update the form action URL with the correct comment ID
            replyForm.attr('action', `{{ route('comment.reply', ['comment' => ':commentId']) }}`.replace(':commentId', commentId));

            // Perform AJAX request
            $.ajax({
                url: replyForm.attr('action'),
                method: replyForm.attr('method'),
                data: replyForm.serialize(),
                success: function(response) {
                    // Display success message on the page
                    $('#reply-message').text('Reply posted successfully').fadeIn().delay(3000).fadeOut();

                    // Optional: Update UI to display the new reply
                    replyForm.find('input[name="reply_content"]').val(''); // Clear reply input field
                },
                error: function(xhr, status, error) {
                    console.error('Error posting reply:', error);
                    $('#reply-error').text('Error posting reply. Please try again.').fadeIn().delay(3000).fadeOut();
                }
            });
        });

        // Fetch and append new comments every 6 seconds
        setInterval(fetchAndAppendNewComments, 6000);
    });

</script>
<script>
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
@endsection

@extends('layouts.app')

@section('title', 'CSS - ' . $lesson->title)
@section('style')
<style>
.video-container {
    position: relative;
    width: 100%;
    max-width: 800px;
    margin: auto;
    background-color: #000;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

video {
    width: 100%;
    display: block;
    border-radius: 10px;
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
    background: rgba(0, 0, 0, 0.6);
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
    border-radius: 0 0 10px 10px;
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
    transition: opacity 0.3s ease;
}

.fullscreen-btn {
    top: 10%;
    left: 10%;
}

.control-btn:hover {
    opacity: 0.8;
}

.control-range {
    -webkit-appearance: none;
    appearance: none;
    height: 4px;
    background: #fff;
    border-radius: 2px;
    outline: none;
}

.control-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;
    height: 12px;
    background: #fff;
    border-radius: 50%;
    cursor: pointer;
    transition: background 0.3s ease;
}

.control-range::-webkit-slider-thumb:hover {
    background: #f1c40f;
}

.volume-container {
    display: flex;
    align-items: center;
}

.volume-range {
    width: 100px;
    margin-left: 10px;
}

.progress-range {
    width: calc(100% - 250px);
    margin: 0 10px;
}

.current-time, .duration {
    font-size: 14px;
}

.lesson-details {
    text-align: center;
    margin: 20px 0;
}

.lesson-details p {
    margin: 5px 0;
}

.teacher-description-container {
    display: flex;
    align-items: center;
    margin-top: 20px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.teacher-image {
    margin-right: 20px;
}

.teacher-image img, .teacher-image i {
    border: 2px solid #4a90e2;
    padding: 5px;
    border-radius: 50%;
}

.lesson-description {
    flex-grow: 1;
}

.lesson-description h5 {
    margin-bottom: 10px;
    color: #000;
}

.short-description, .full-description {
    display: inline;
    color: #000;
}

.show-more, .show-less {
    color: blue;
    cursor: pointer;
    text-decoration: underline;
    margin-left: 5px;
}

.like-link, .add-to-favorite-link, .link-black {
    cursor: pointer;
}

.comment-section {
    margin-top: 20px;
}

.comment-header, .comment-footer, .reply-header, .reply-footer {
    display: flex;
    justify-content: space-between;
}

.input-group {
    display: flex;
}

.input-group-append {
    display: flex;
    align-items: center;
}

.btn-clear {
    background: none;
    border: none;
    padding: 0;
    font-size: 14px;
    cursor: pointer;
}

.small-text {
    font-size: 12px;
}

.alert {
    margin-bottom: 10px;
}

.like-link, .add-to-favorite-link, .comments-link {
    display: inline-flex;
    align-items: center;
    margin-right: 10px;
    text-decoration: none;
    color: inherit;
}

.like-link .fa-thumbs-up, .add-to-favorite-link .fa-heart, .comments-link .fa-comments {
    margin-right: 5px;
}

.float-right {
    display: inline-flex;
    align-items: center;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}

#comments-section {
    margin-top: 10px;
    border-top: 1px solid #ccc;
    padding-top: 10px;
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

<div class="video-container" id="videoContainer">
    <video id="lessonVideo" width="100%">
        <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <button id="playPauseBtn" class="control-btn play-pause-btn"><i class="fas fa-play"></i></button>
    <div class="custom-controls">
        <button id="fullscreenBtn" class="control-btn fullscreen-btn"><i class="fas fa-expand"></i></button>
        <div class="volume-container">
            <i class="fas fa-volume-up"></i>
            <input id="volumeRange" type="range" class="control-range volume-range" min="0" max="1" step="0.1" value="1">
        </div>
        <span id="currentTime" class="current-time">0:00</span>
        <input id="progressRange" type="range" class="control-range progress-range" min="0" max="100" step="0.1" value="0">
        <span id="duration" class="duration">0:00</span>
    </div>
</div>

<div class="lesson-details mt-2 mb-0">
    <p><b>Teacher: <a href="{{route('user_page',['userId'=> $lesson->teacher->id, 'fullname'=> $lesson->teacher->profile->full_name])}}">{{$lesson->teacher->profile->full_name}}</a></b></p>
    @if($lesson->school)
    <p><b>School: <a href="{{route('school_page',['schoolId'=> $lesson->school->id, 'schoolname'=> $lesson->school->name])}}">{{$lesson->school->name}}</a></b></p>
    @endif
</div>

<div class="teacher-description-container">
    <div class="teacher-image">
        @if($lesson->teacher)
            @if($lesson->teacher->profile->profile_picture)
                <img src="{{ asset('storage/' . $lesson->teacher->profile->profile_picture) }}" class="img-circle elevation-2" alt="{{ $lesson->teacher->profile->full_name }}" width="50px">
            @else
                <i class="fas fa-camera img-circle elevation-2"></i>
            @endif
        @else
            <i class="fas fa-camera img-circle elevation-2"></i>
        @endif
    </div>

    <div class="lesson-description">
        <h5 class="mt-3"><b>Description</b></h5>
        @if (strlen($lesson->description) > 15)
            <span class="short-description">{{ substr($lesson->description, 0, 15) . '...' }}</span>
            <span class="full-description" style="display:none;">{{ $lesson->description }}</span>
            <span class="show-more" onclick="toggleDescription(this)">Show more</span>
            <span class="show-less" style="display:none;" onclick="toggleDescription(this)">Show less</span>
        @else
            {{ $lesson->description }}
        @endif
    </div>
</div>
    <p class="mt-2">
    @php
        $lessonId = $lesson->id;
        $isLiked = Auth::check() && Auth::user()->likedLessons()->where('lesson_id', $lessonId)->exists();
        $likeCount = $lesson->likedUsers()->count();

        $isFavorited = Auth::check() && Auth::user()->favoriteLessons()->where('lesson_id', $lessonId)->exists();
        $favoriteCount = $lesson->favoritedByUsers()->count();
    @endphp

    <a href="#" class="like-link {{ $isLiked ? 'liked' : '' }}" data-lesson-id="{{ $lessonId }}">
        <i class="{{ $isLiked ? 'fas' : 'far' }} fa-thumbs-up"></i>
        <span class="sr-only">Like</span>
        <span class="like-count">{{ $likeCount }}</span>
    </a>

    <a href="#" class="add-to-favorite-link {{ $isFavorited ? 'added-to-favorites' : '' }}" data-lesson-id="{{ $lessonId }}">
        <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart"></i>
        <span class="sr-only">Add to Favorites</span>
        <span class="favorite-count">{{ $favoriteCount }}</span>
    </a>

    @php
        $totalComments = count($lesson->comments);
    @endphp

    <span class="float-right">
        <a href="#" class="comments-link" id="toggle-comments">
            <i class="far fa-comments"></i>
            <span class="sr-only">Comments</span>
            <span class="comment-count">{{ $totalComments }}</span>
        </a>
    </span>
</p>

<div id="comments-section" class="comment-section mt-4 mb-3" style="display: none;">
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
    function toggleDescription(element) {
    const container = element.closest('.lesson-description');
    const shortDescription = container.querySelector('.short-description');
    const fullDescription = container.querySelector('.full-description');
    const showMore = container.querySelector('.show-more');
    const showLess = container.querySelector('.show-less');

    if (fullDescription.style.display === 'none') {
        shortDescription.style.display = 'none';
        fullDescription.style.display = 'inline';
        showMore.style.display = 'none';
        showLess.style.display = 'inline';
    } else {
        shortDescription.style.display = 'inline';
        fullDescription.style.display = 'none';
        showMore.style.display = 'inline';
        showLess.style.display = 'none';
    }
}

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('lessonVideo');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const volumeRange = document.getElementById('volumeRange');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
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

    // Seek video on progress bar input
    progressRange.addEventListener('input', function() {
        video.currentTime = (progressRange.value / 100) * video.duration;
    });

    // Display total duration
    video.addEventListener('loadedmetadata', function() {
        durationDisplay.textContent = formatTime(video.duration);
    });

    // Fullscreen functionality
    fullscreenBtn.addEventListener('click', function () {
        if (video.requestFullscreen) {
            video.requestFullscreen();
        } else if (video.mozRequestFullScreen) { // Firefox
            video.mozRequestFullScreen();
        } else if (video.webkitRequestFullscreen) { // Chrome, Safari and Opera
            video.webkitRequestFullscreen();
        } else if (video.msRequestFullscreen) { // IE/Edge
            video.msRequestFullscreen();
        }
    });

    // Format time in MM:SS format
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }

    // Double-click to fast forward/reverse functionality
    video.addEventListener('dblclick', function(event) {
        const rect = video.getBoundingClientRect();
        const clickPosition = event.clientX - rect.left;
        const videoWidth = rect.width;
        const jumpTime = 10; // Seconds to jump

        if (clickPosition < videoWidth / 2) {
            // Left side double-click: rewind
            video.currentTime = Math.max(0, video.currentTime - jumpTime);
        } else {
            // Right side double-click: fast forward
            video.currentTime = Math.min(video.duration, video.currentTime + jumpTime);
        }
    });

    // Pause video on visibility change
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            video.pause();
            playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    });

    // Initialize video controls
    playPauseBtn.addEventListener('click', togglePlayPause);
});
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

    function postComment() {
        var commentContent = $('#commentContent').val();
        var commentCountElement = $('.comment-count');

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
                var newCommentCount = response.comment_count;
                commentCountElement.text(newCommentCount); // Update comment count
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
        
        var link = $(this);
        var lessonId = link.data('lesson-id');
        var token = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/lessons/' + lessonId + '/favorite',
            type: 'POST',
            data: {
                _token: token
            },
            success: function(response) {
                if (response.message === 'Lesson added to favorites') {
                    link.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
                } else if (response.message === 'Lesson removed from favorites') {
                    link.find('i').removeClass('fas fa-heart').addClass('far fa-heart');
                }
                var newFavoriteCount = response.favorite_count;
                link.find('.favorite-count').text(newFavoriteCount);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    $('.like-link').click(function(e) {
        e.preventDefault();
        
        var link = $(this);
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
                    link.find('i').removeClass('far fa-thumbs-up').addClass('fas fa-thumbs-up');
                } else {
                    link.removeClass('liked');
                    link.find('i').removeClass('fas fa-thumbs-up').addClass('far fa-thumbs-up');
                }
                var newLikeCount = response.like_count;
                link.find('.like-count').text(newLikeCount);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    $('#toggle-comments').click(function(e) {
        e.preventDefault();
        $('#comments-section').toggle();
    });

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

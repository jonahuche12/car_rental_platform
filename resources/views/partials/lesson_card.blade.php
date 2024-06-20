<div class="col-md-4 position-relative">
    <div class="card lesson-card">
        <div class="dropdown">
            <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="lessonActionsDropdown{{ $lesson->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="lessonActionsDropdown{{ $lesson->id }}">
                <a class="dropdown-item edit-lesson-btn" href="#" data-lesson-id="{{ $lesson->id }}">Edit</a>
                @if ($lesson->user_id == auth()->id())
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removelessonModal{{ $lesson->id }}">Remove lesson</a>
                @endif
            </div>
        </div>
        <br>
        <div class="thumbnail-container">
            <a href="{{ route('lessons.show', $lesson->id) }}" class="viewed_lessons" data-viewed_lesson-id="{{ $lesson->id }}" data-lesson-title="{{ $lesson->title }}" data-school-connects-required="{{ $lesson->school_connects_required }}">
                @if ($lesson->thumbnail)
                    <div class="thumbnail-with-play">
                        <img src="{{ asset($lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="img-fluid lesson-thumbnail">
                        <div class="play-icon-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                @else
                    <div class="no-thumbnail">
                        <div class="video-icon"><i class="fas fa-video"></i></div>
                        <div class="overlay"></div>
                        <img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">
                    </div>
                @endif
            </a>
        </div>
        <h5><small>{{ \Illuminate\Support\Str::limit($lesson->title, 15) }}</small></h5>
        <p class="lesson-description">
            <small>{{ \Illuminate\Support\Str::limit($lesson->description, 200) }}</small>
            @if (strlen($lesson->description) > 200)
                <a href="#" class="show-more small-text" data-lesson-id="{{ $lesson->id }}">Show more</a>
            @endif
        </p>
        <div class="full-description-overlay" id="fullDescription{{ $lesson->id }}">
            <div class="full-description-content">
                <h5 class="lesson-title">{{ $lesson->title }}</h5>
                <p class="small-text">{{ $lesson->description }}</p>
                <a href="#" class="show-less small-text" data-lesson-id="{{ $lesson->id }}">Show less</a>
            </div>
        </div>
    </div>
</div>

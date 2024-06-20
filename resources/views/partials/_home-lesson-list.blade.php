<div class="search-bar-container mb-4">
    <input type="text" id="lessonSearchInput" class="form-control" placeholder="Search lessons...">
</div>

<div class="lessons-container" id="lessonsContainer">
    <div class="row">
        @foreach ($top_lessons as $lesson)
            <div class="col-md-4 position-relative">
                <div class="card lesson-card">
                    <div class="thumbnail-container position-relative">
                        <a class="lesson-link" href="{{ route('lessons.show', $lesson) }}" data-lesson-id="{{ $lesson->id }}" data-lesson-title="{{ $lesson->title }}" data-school-connects-required="{{ $lesson->school_connects_required }}">
                            @if ($lesson->enrolledUsers()->where('user_id', auth()->id())->exists())
                                <span class="badge bg-primary" style="position:absolute; top:10px; right:10px; z-index:99;"><i class="fas fa-check"></i></span>
                            @endif
                            @if ($lesson->thumbnail)
                                <div class="thumbnail-with-play position-relative">
                                    <img src="{{ asset($lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="img-fluid lesson-thumbnail">
                                    <div class="play-icon-overlay">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <span class="badge bg-primary school-connects-badge"><small><b>{{ $lesson->school_connects_required }} SC</b></small></span>
                                    <span class="views-badge"><small>{{ $lesson->enrolledUsers()->count() }} <i class="fas fa-eye"></i></small></span>
                                </div>
                            @else
                                <div class="no-thumbnail">
                                    <div class="video-icon">
                                        <i class="fas fa-video"></i>
                                    </div>
                                    <div class="overlay"></div>
                                    <img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">
                                    <span class="badge bg-primary school-connects-badge"><small><b>{{ $lesson->school_connects_required }} SC</b></small></span>
                                    <span class="views-badge"><small>{{ $lesson->enrolledUsers()->count() }} <i class="fas fa-eye"></i></small></span>
                                </div>
                            @endif
                        </a>
                    </div>
                    <p class="small-text mb-0"><small>{{ $lesson->teacher->profile->full_name }}</small></p>
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

            <!-- Remove lesson Modal -->
            <div class="modal fade" id="removelessonModal{{ $lesson->id }}" tabindex="-1" role="dialog" aria-labelledby="removelessonModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="removelessonModalLabel">Remove lesson</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-success lesson-message" style="display:none;"></div>
                        <div class="alert alert-danger" id="lesson-error" style="display:none;"></div>
                        <div class="modal-body">
                            Are you sure you want to Delete <b>{{ $lesson->title }}</b>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="removeLessonBtn" onclick="removelesson({{ $lesson->id }})">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

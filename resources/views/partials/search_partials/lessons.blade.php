@if ($results->isNotEmpty())
    <h5>Lessons</h5>
    <div class="lessons-container">
        <div class="row">
            @foreach ($results as $lesson)
                <div class="col-md-4 col-">
                    <div class="card lesson-card">
                        @if ($lesson->user_id == auth()->id())
                            <!-- Dropdown menu for actions (only visible to the lesson owner) -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-clear dropdown-toggle" type="button" id="lessonActionsDropdown{{ $lesson->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="lessonActionsDropdown{{ $lesson->id }}">
                                    <a class="dropdown-item edit-lesson-btn" href="#" data-lesson-id="{{ $lesson->id }}">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#removelessonModal{{ $lesson->id }}">Remove lesson</a>
                                </div>
                            </div>
                        @endif

                        <!-- Display lesson thumbnail -->
                        <div class="thumbnail-container position-relative">
                            <a href="#" class="lesson-link" data-lesson-id="{{ $lesson->id }}" data-lesson-title="{{ $lesson->title }}" data-school-connects-required="{{ $lesson->school_connects_required }}">
                                @if ($lesson->enrolledUsers()->where('user_id', auth()->id())->exists())
                                    <span class="badge bg-purple" style="position:absolute; top:10px; right:10px; z-index:99;"><i class="fas fa-check"></i></span>
                                @endif
                                @if ($lesson->thumbnail)
                                    <div class="thumbnail-with-play">
                                        <img src="{{ asset($lesson->thumbnail) }}" alt="{{ $lesson->title }}" class="img-fluid lesson-thumbnail">
                                        <div class="play-icon-overlay">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="no-thumbnail">
                                        <div class="video-icon">
                                            <i class="fas fa-video"></i>
                                        </div>
                                        <div class="overlay"></div>
                                        <img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">
                                    </div>
                                @endif
                            </a>
                        </div>

                        <!-- Additional lesson details -->
                        <p><small><b>{{ $lesson->teacher->profile->full_name }}</b></small></p>
                        <h5><small>{{ \Illuminate\Support\Str::limit($lesson->title, 15) }}</small></h5>
                        <p><small>{{ \Illuminate\Support\Str::limit($lesson->description, 200) }}</small></p>
                        <p class="badge bg-purple"><small><b>{{ $lesson->school_connects_required }} school connects</b></small></p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <p>No lessons found for the search term <b>{{ $term }}</b>.</p>
@endif

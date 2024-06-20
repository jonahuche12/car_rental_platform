<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <li class="nav-item small-text">
                                <a class="nav-link active" href="#school-details" data-toggle="tab">
                                    <i class="fas fa-school"></i> School Details
                                </a>
                            </li>
                            <li class="nav-item small-text">
                                <a class="nav-link" href="#lessons" data-toggle="tab">
                                    <i class="fas fa-book-open"></i> Lessons
                                </a>
                            </li>
                           
                            <li class="nav-item small-text">
                                <a class="nav-link " href="#teachers" data-toggle="tab">
                                    <i class="fas fa-chalkboard-teacher"></i> Teachers
                                </a>
                            </li>
                            <li class="nav-item small-text">
                                <a class="nav-link" href="#events" data-toggle="tab">
                                    <i class="far fa-calendar-alt"></i> Events
                                </a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="lessons">
                                <div class="lessons-container">
                                    <div class="row">
                                        @foreach ($lessons as $lesson)
                                        <div class="col-md-4 position-relative">
                                            <div class="card lesson-card">
                                                <div class="thumbnail-container">
                                                <a class="lesson-link " href="{{ route('lessons.show', $lesson) }}" data-lesson-id="{{$lesson->id}}" data-lesson-title="{{$lesson->title}}" data-school-connects-required="{{$lesson->school_connects_required}}">
                                                @if ($lesson->enrolledUsers()->where('user_id', auth()->id())->exists())
                                                        <span class="badge bg-primary" style="position:absolute; top:10; right:10; z-index:99;"><i class="fas fa-check"></i></span> <!-- Success badge with check icon -->
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
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="school-details">
                                <div class="school-details-section container mt-4">
                                    <div class="school-header card mb-4">
                                    <div class="card-body text-center">
                                        <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="school-logo rounded-circle mx-auto mb-3">
                                        <div>
                                            <h2 class="card-title mb-1">{{ $school->name }}</h2>
                                            <p class="card-text text-muted">{{ $school->motto }}</p>
                                            <div class="ranking-stars">
                                                @php
                                                    $ranking = $school->getRanking();
                                                    $fullStars = floor($ranking); // Number of full stars
                                                    $halfStar = ceil($ranking) - $fullStars; // Half star if needed
                                                    $emptyStars = 5 - $fullStars - $halfStar; // Remaining empty stars

                                                    // Determine color class based on ranking
                                                    if ($ranking < 2) {
                                                        $colorClass = 'text-danger'; // Red color for ranking < 2
                                                    } elseif ($ranking >= 2 && $ranking < 4) {
                                                        $colorClass = 'text-primary'; // Blue color for ranking between 2 and 3
                                                    } else {
                                                        $colorClass = 'text-success'; // Green color for ranking between 4 and 5
                                                    }
                                                @endphp

                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <i class="fas fa-star {{ $colorClass }}"></i>
                                                @endfor

                                                @if ($halfStar)
                                                    <i class="fas fa-star-half-alt {{ $colorClass }}"></i>
                                                @endif

                                                @for ($i = 0; $i < $emptyStars; $i++)
                                                    <i class="far fa-star {{ $colorClass }}"></i>
                                                @endfor
                                                <p>ranking {{$ranking}} </p>
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Description</h5>
                                                    <p class="card-text">{{ $school->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Mission</h5>
                                                    <p class="card-text">{{ $school->mission }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Vision</h5>
                                                    <p class="card-text">{{ $school->vision }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="text-primary">Contact Information</h5>
                                                    <div class="school-contact">
                                                        <div class="contact-item">
                                                            <h6><i class="fas fa-map-marker-alt"></i> Address</h6>
                                                            <p class="card-text text-info">{{ $school->address }}, {{ $school->city }}, {{ $school->state }}, {{ $school->country }}</p>
                                                        </div>
                                                        <div class="contact-item">
                                                            <h6><i class="fas fa-envelope"></i> Email</h6>
                                                            <p class="card-text text-info">{{ $school->email }}</p>
                                                        </div>
                                                        <div class="contact-item">
                                                            <h6><i class="fas fa-phone-alt"></i> Phone</h6>
                                                            <p class="card-text text-info">{{ $school->phone_number }}</p>
                                                        </div>
                                                        <div class="contact-item">
                                                            <h6><i class="fas fa-globe"></i> Website</h6>
                                                            <p class="card-text text-info"><a href="{{ $school->website }}" target="_blank">{{ $school->website }}</a></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="text-primary">Social Media</h5>
                                            <div class="school-contact">
                                                @if($school->facebook)
                                                <div class="contact-item">
                                                    <h6><i class="fab fa-facebook text-primary"></i> Facebook</h6>
                                                    <p class="card-text text-info"><a href="{{ $school->facebook }}" target="_blank">{{ $school->facebook }}</a></p>
                                                </div>
                                                @endif
                                                @if($school->instagram)
                                                <div class="contact-item">
                                                    <h6><i class="fab fa-instagram text-primary"></i> Instagram</h6>
                                                    <p class="card-text text-info"><a href="{{ $school->instagram }}" target="_blank">{{ $school->instagram }}</a></p>
                                                </div>
                                                @endif
                                                @if($school->twitter)
                                                <div class="contact-item">
                                                    <h6><i class="fab fa-twitter text-primary"></i> Twitter</h6>
                                                    <p class="card-text text-info"><a href="{{ $school->twitter }}" target="_blank">{{ $school->twitter }}</a></p>
                                                </div>
                                                @endif
                                                @if($school->linkedin)
                                                <div class="contact-item">
                                                    <h6><i class="fab fa-linkedin text-primary"></i> LinkedIn</h6>
                                                    <p class="card-text text-info"><a href="{{ $school->linkedin }}" target="_blank">{{ $school->linkedin }}</a></p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Total Students</h5>
                                                    <p class="card-text">{{ $school->confirmedStudents->count() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Total Teachers</h5>
                                                    <p class="card-text">{{ $school->confirmedTeachers->count() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Total Staff</h5>
                                                    <p class="card-text">{{ $school->getConfirmedAdmins()->count() + $school->confirmedTeachers->count() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane " id="teachers">
                                <h3>Teachers</h3>
                                <div class="row">
                                    @foreach ($school->confirmedTeachers as $teacher)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                @if ($teacher->profile->profile_picture)
                                                <img src="{{ asset('storage/' . $teacher->profile->profile_picture) }}" alt="{{ $teacher->name }}" class="profile-photo rounded-circle mb-3" style="width: 100px; height: 100px;">
                                                @else
                                                <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image" class="profile-photo rounded-circle mb-3" style="width: 100px; height: 100px;">
                                                @endif
                                                <h5 class="">
                                                    <a href="{{ route('user_page', ['userId' => $teacher->id, 'fullname' => $teacher->profile->full_name]) }}" class="text-info">{{ $teacher->profile->full_name }}</a>
                                                </h5>
                                                <p class="card-text">{{ $teacher->profile->role }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>




                            <div class="tab-pane" id="events">
                                <h3>Events</h3>
                                <p>Details about school events can be shown here.</p>
                                <!-- Add event details as needed -->
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <div id="loader"></div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">


    <div class="col-md-12">
    <button class="btn bg-primary" id="getFreeConnects">Get Free Connects</button>
        <div class="card">
            <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item small-text">
                    <a class="nav-link" href="#events" data-toggle="tab">
                        <i class="far fa-calendar-alt"></i> Events
                    </a>
                </li>
                <li class="nav-item small-text">
                    <a class="nav-link active" href="#lessons" data-toggle="tab">
                        <i class="fas fa-book-open"></i> Lessons
                    </a>
                </li>
            </ul>

            </div><!-- /.card-header -->
            <div class="card-body">
            <div class="tab-content">
                
            <div class="tab-pane" id="events">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                        <!-- timeline time label -->
                        <!-- timeline time label -->
                @foreach($top_events as $event)
                <div class="time-label">
                    <span class="badge bg-blue">
                        {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                    </span>
                </div>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <div>
                    <div class="timeline-item" data-event-id="{{ $event->id }}">
                        <span class="time"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>

                        <h4 class="timeline-header"><a href="#">{{ $event->title }}</a></h4>

                        <div class="timeline-body">
                        <b class="timeline-header"><a href="#">{{$event->school->name}}</a></b>

                            @if($event->banner_picture || $school->logo)
                            <!-- Display the image as a link to open in a modal -->
                            <div>
                            <a href="#">
                                <img src="{{ $event->banner_picture ? asset('storage/' . $event->banner_picture) : ($school->logo ? asset('storage/' . $school->logo) : asset('path_to_camera_icon')) }}" alt="Event Banner" class="img-fluid mt-2" style="max-width: 200px;">
                            </a>
                            </div>
                            @endif

                            {{ $event->description }}
                        </div>
                        <div class="timeline-footer">
                            <div>
                            <i class="fa fa-heart p-2"></i>

                            <i class="fa fa-comments p-2"></i>

                            </div>

                        <p class="badge bg-primary "><a href="#">{{$event->academicSession->name}}</a></p>
                        </div>
                    </div>
                </div>
                <!-- END timeline item -->
                @endforeach

                    </div>
                </div>


                <div class="active tab-pane" id="lessons">
                <div class="lessons-container">
                    <div class="row">
                    

                    @foreach ($top_lessons as $lesson)
                        <div class="col-md-4">
                            <div class="card lesson-card">
                                

                                <!-- Display lesson thumbnail -->
                                <div class="thumbnail-container position-relative">
                                    <a href="#" class="lesson-link" data-lesson-id="{{ $lesson->id }}" data-lesson-title="{{ $lesson->title }}" data-school-connects-required="{{ $lesson->school_connects_required }}">
                                    <!-- Conditionally display enrollment badge -->
                                    @if ($lesson->enrolledUsers()->where('user_id', auth()->id())->exists())
                                            <span class="badge bg-primary" style="position:absolute; top:10; right:10; z-index:99;"><i class="fas fa-check"></i></span> <!-- Success badge with check icon -->
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

                                <p class="badge bg-primary" style="position:relative;  z-index:99;"><small><b>{{ $lesson->school_connects_required }} SC</b></small></p>
                                </div>

                                <!-- Additional lesson details -->
                                <p><small><b>{{ $lesson->teacher->profile->full_name }}</b></small></p>
                                        
                                <h5><small>{{ \Illuminate\Support\Str::limit($lesson->title, 15) }}</small></h5>
                                <p><small>{{ \Illuminate\Support\Str::limit($lesson->description, 200) }}</small></p>
                            </div>
                        </div>
                    @endforeach
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
    <div id="loader"></div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

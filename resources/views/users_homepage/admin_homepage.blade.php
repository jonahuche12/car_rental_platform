<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <button class="btn bg-primary" id="getFreeConnects">Get Free Connects</button>
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active small-text" href="#lessons" data-toggle="tab">
                                    <i class="fas fa-book-open nav-icon"></i> Lessons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link small-text" href="#events" data-toggle="tab">
                                    <i class="far fa-calendar-alt nav-icon"></i> Events
                                </a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="events">
                                @include('partials._event-list', ['top_events' => $top_events])
                            </div>
                            <div class="active tab-pane" id="lessons">
                                    
                                @include('partials._home-lesson-list', ['lessons' => $top_lessons])
                                
                            </div>
                        </div><!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

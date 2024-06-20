<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <!-- <li class="nav-item small-text">
                                <a class="nav-link active" href="#lessons" data-toggle="tab">
                                    <i class="fas fa-book-open"></i> Lessons <sup> {{$all_lesson_count}} </sup>
                                </a>
                            </li> -->
                            <li class="nav-item small-text">
                                <a class="nav-link active" href="#user-details" data-toggle="tab">
                                    <i class="fas fa-user"></i> User Details
                                </a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->

                    <div class="card-body">
                        <div class="tab-content">
                       

                        <!-- User Details Tab Pane -->
                        <div class="tab-pane active" id="user-details">
                            @include('partials._user-details')
                        </div>

                        </div>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

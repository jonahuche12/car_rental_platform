<section class="content">
  @php
  $admin = auth()->user()->profile
  @endphp
 

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        @if($admin->admin_confirmed)
        <div class="row">

            @if($admin->permission_confirm_student)
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $admin->user->school->confirmedStudents->count() }}</h3>
                            <b>Students</b>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a class="small-box-footer" href="{{ route('manage-students', ['schoolId' => $school->id]) }}" class="text-decoration-none">Manage Students <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif


            @if($admin->permission_confirm_staff)
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ 0 }}</h3>
                            <b>Staff</b>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">Manage Staff <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            @if($admin->permission_confirm_teacher)
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $admin->user->school->confirmedTeachers->count() }}</h3>
                            <b>Teachers</b>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('manage-teachers', ['schoolId' => $school->id]) }}" class="small-box-footer">Manage teachers <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            @if($admin->permission_create_course)
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>65</h3>
                            <b>Courses/Subjects</b>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('manage-courses', ['schoolId' => $school->id]) }}" class="small-box-footer">Manage Courses <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            @if($admin->permission_create_class)
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $admin->user->school->classes->count() }}</h3>
                            <b>Classes</b>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bell"></i>
                        </div>
                        <a href="{{ route('manage-classes', ['schoolId' => $school->id]) }} " class="small-box-footer">Manage Classes <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            @if($admin->permission_create_event)
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <b>Events</b>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bell"></i>
                        </div>
                        <a href="#" class="small-box-footer">Manage Events <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif

            @if($admin->teacher_confirmed)
            
                <!-- right col -->
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $admin->user->formClasses->count() }}</h3>
                            <b>Form Class(es)</b>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a class="small-box-footer" href="{{ route('manage-form_classes', ['teacherId' => $admin->id]) }}" class="text-decoration-none">Manage Class(es) <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            
            @endif

        </div>
        @endif
        <!-- /.row -->
        
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
   
</section>

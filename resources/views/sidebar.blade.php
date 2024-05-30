<aside class="main-sidebar sidebar-dark-primary elevation-4">
@php
    $admin = auth()->check() ? auth()->user()->profile : null;
    if(!isset($school) && auth()->check() && auth()->user()->school){
        $school = auth()->user()->school;
    }
@endphp
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">CSS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(auth()->check() && $admin)
                    @if($admin->profile_picture)
                        <img src="{{ asset('storage/' . $admin->profile_picture) }}" class="img-circle elevation-2" alt="{{ auth()->user()->name }}">
                    @else
                        <i class="fas fa-camera img-circle elevation-2"></i>
                    @endif
                @else
                    <i class="fas fa-camera img-circle elevation-2"></i>
                @endif
            </div>
            <div class="info">
                @if(auth()->check() && $admin)
                    <a href="#" class="d-block">{{ \Illuminate\Support\Str::limit($admin->full_name, 18) }}</a>
                @else
                    <a href="#" class="d-block">{{ __('Guest') }}</a>
                @endif
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- User Profile Section -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            @if(auth()->check() && $admin)
                                {{ \Illuminate\Support\Str::limit($admin->full_name, 18) }}
                            @else
                                {{ __('Guest') }}
                            @endif
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3 small-text">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="fas fas fa-tachometer-alt nav-icon small-text"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3 small-text">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="fas fa-home nav-icon small-text"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3 small-text">
                            <a href="{{ route('profile') }}" class="nav-link">
                                <i class="fas fa-user nav-icon small-text"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End User Profile Section -->
                @if($admin && $admin->role == "super_admin")
                    @php
                        $totalPackagesCount = App\Models\SchoolPackage::count();
                        $totalSchoolsCount = App\Models\School::count();
                        $totalCurriculumCount = App\Models\Curriculum::count();
                        $totalAcademicSessionCount = App\Models\AcademicSession::count();
                        $totalTestCount = App\Models\Test::count();
                        $totalScholarshipCount = App\Models\Scholarship::count();
                    @endphp
                    <li class="nav-item ml-3">
                        <a href="{{ route('manage_school_packages') }}" class="nav-link">
                            <i class="fas fa-box nav-icon"></i>
                            <p class="small-text">Manage Packages ({{ $totalPackagesCount }})</p>
                        </a>
                    </li>
                    <li class="nav-item ml-3">
                        <a href="{{ route('manage_curriculum') }}" class="nav-link">
                            <i class="fas fa-book nav-icon"></i>
                            <p class="small-text">Curriculum ({{ $totalCurriculumCount }})</p>
                        </a>
                    </li>
                    <li class="nav-item ml-3">
                        <a href="{{ route('manage_all_schools') }}" class="nav-link">
                            <i class="fas fa-school nav-icon"></i>
                            <p class="small-text">All Schools ({{ $totalSchoolsCount }})</p>
                        </a>
                    </li>
                    <li class="nav-item ml-3">
                        <a href="{{ route('manage_academic_sessions') }}" class="nav-link">
                            <i class="fas fa-calendar-alt nav-icon"></i>
                            <p class="small-text">Academic Sessions ({{ $totalAcademicSessionCount }})</p>
                        </a>
                    </li>
                    <li class="nav-item ml-3">
                        <a href="{{ route('manage_tests') }}" class="nav-link">
                            <i class="fas fa-calendar-alt nav-icon"></i>
                            <p class="small-text">Tests ({{ $totalTestCount }})</p>
                        </a>
                    </li>

                    <li class="nav-item ml-3">
                        <a href="{{ route('manage_scholarship') }}" class="nav-link">
                            <i class="fas fa-calendar-alt nav-icon"></i>
                            <p class="small-text">Scholarship ({{ $totalScholarshipCount }})</p>
                        </a>
                    </li>
                @endif
                
                <!-- School Section -->
                <li class="nav-item menu-close">
                    
                    @if(isset($school))
                    @if(auth()->check() && $admin || $admin->role == 'school_owner')
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-school"></i>
                            <p class="small-text">
                                {{ \Illuminate\Support\Str::limit($school->name, 21) }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                               @if(auth()->user()->profile && auth()->user()->profile->class_id)
                                    @if(auth()->user()->schoolClass())
                                    <li class="nav-item ml-3">
                                        @php
                                            $schoolClass = auth()->user()->schoolClass();
                                            $classCode = $schoolClass ? $schoolClass->code : 'N/A';
                                        @endphp
                                        <a href="{{ $schoolClass ? route('class.show', ['class' => $schoolClass->id]) : '#' }}" class="nav-link">
                                            <i class="fas fa-chalkboard nav-icon"></i>
                                            <p class="small-text">{{ \Illuminate\Support\Str::limit($classCode, 21) }}</p>
                                        </a>
                                    </li>
                                    @endif
                                @endif

                                
                                
                            @if($admin->admin_confirmed || $admin->role == "school_owner")
                                <!-- Check permissions and display links accordingly -->
                                @if($admin->permission_create_class || $admin->role == "school_owner")
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('manage-classes', ['schoolId' => $school->id]) }}" class="nav-link">
                                            <i class="fas fa-chalkboard nav-icon"></i>
                                            <p class="small-text">Manage Classes ({{ $school->classes->count() }}) </p>
                                        </a>
                                    </li>
                                @endif
                                @if($admin->permission_create_course || $admin->role == "school_owner")
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('manage-courses', ['schoolId' => $school->id]) }}" class="nav-link">
                                            <i class="fas fa-book nav-icon"></i>
                                            <p class="small-text">Manage Courses ({{ $school->courses->count() }}) </p>
                                        </a>
                                    </li>
                                @endif
                                @if($admin->permission_create_event || $admin->role == "school_owner")
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('manage-events', ['schoolId' => $school->id]) }}" class="nav-link">
                                            <i class="fas fa-calendar-alt nav-icon"></i>
                                            <p class="small-text">Manage Events ({{ $school->events->count() }}) </p>
                                        </a>
                                    </li>
                                @endif
                                @if($admin->permission_confirm_student || $admin->role == "school_owner")
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('manage-students', ['schoolId' => $school->id]) }}" class="nav-link">
                                            <i class="fas fa-user-graduate nav-icon"></i>
                                            <p class="small-text">Manage Students ({{ $school->confirmedStudents->count() }})</p>
                                        </a>
                                    </li>
                                @endif
                                @if($admin->permission_confirm_teacher || $admin->role == "school_owner")
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('manage-teachers', ['schoolId' => $school->id]) }}" class="nav-link">
                                            <i class="fas fa-chalkboard-teacher nav-icon"></i>
                                            <p class="small-text">Manage Teachers ({{ $school->confirmedTeachers->count() }})</p>
                                        </a>
                                    </li>
                                @endif
                                <!-- Add other permissions as needed -->
                            @endif
                            @if($admin->teacher_confirmed)
                                <li class="nav-item ml-3">
                                    <a href="{{ route('manage-form_classes', ['teacherId' => $admin->id]) }}" class="nav-link">
                                        <i class="fas fa-chalkboard nav-icon"></i>
                                        <p class="small-text">Form Class(es) ({{ $admin->user->formClasses->count() }})</p>
                                    </a>
                                </li>

                                <li class="nav-item ml-3">
                                    <a href="{{ route('dashboard') }}" class="nav-link">
                                        <i class="fas fa-book-open nav-icon"></i>
                                        <p class="small-text">Lessons ({{ $admin->user->lessons->count() }})</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    @endif
                    @endif
                </li>
                <!-- End School Section -->
                <li class="nav-item text-light mt-5 dropdown">
                    <div class="dropdown-toggle d-flex align-items-center" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @auth
                            <i class="fas fa-cog text-light nav-icon mr-1"></i>
                            <span class="small-text"></span>
                        @else
                            <i class="fas fa-cog text-light nav-icon mr-1"></i>
                            <span class="small-text text-light">{{ __('Login/Register') }}</span>
                        @endauth
                    </div>
                    <div class="dropdown-menu" style="border:none" aria-labelledby="dropdownMenuLink">
                        @auth
                            <a class="dropdown-item text-" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt nav-icon"></i>
                                <span class="small-text text-sm">{{ __('Logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a class="dropdown-item text-" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt nav-icon"></i>
                                <span class="small-text">{{ __('Login') }}</span>
                            </a>
                            <a class="dropdown-item text-black" href="{{ route('register') }}">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <span class="small-text">{{ __('Register') }}</span>
                            </a>
                        @endauth
                    </div>
                </li>


                <!-- End Logout Section -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

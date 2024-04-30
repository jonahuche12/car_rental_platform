<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">CSS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(auth()->user()->profile)
                    @if(auth()->user()->profile->profile_picture)
                        <!-- Display the user's profile picture -->
                        <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" class="img-circle elevation-2" alt="{{ auth()->user()->name }}">
                    @else
                        <!-- Display a camera icon when profile picture is null -->
                        <i class="fas fa-camera img-circle elevation-2"></i>
                    @endif
                @else
                    <!-- Display a message or handle the case when the user doesn't have a profile -->
                    <p><i class="fas fa-camera img-circle elevation-2"></i></p>
                @endif
            </div>
            <div class="info">
                @if(auth()->user()->profile)
                    <a href="#" class="d-block">{{ \Illuminate\Support\Str::limit(auth()->user()->profile->full_name, 18) }}</a>
                @else
                    <a href="#" class="d-block">{{ auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name }}</a>
                @endif
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- User Profile Section -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            @if(auth()->user()->profile)
                                {{ \Illuminate\Support\Str::limit(auth()->user()->profile->full_name, 18) }}
                            @else
                                {{ auth()->user()->first_name . ' ' . auth()->user()->middle_name . ' ' . auth()->user()->last_name }}
                            @endif
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile') }}" class="nav-link">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-close">
                    @if(auth()->user()->school)
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-school"></i>
                            <p class="small-text">
                                {{ \Illuminate\Support\Str::limit(auth()->user()->school->name, 21) }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if(auth()->user()->profile && auth()->user()->profile->class_id)
                            @if(auth()->user()->schoolClass())
                            <li class="nav-item">
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
                    </ul>
                </li>
                <!-- End User Profile Section -->
                <!-- Logout Section -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <p class="small-text">{{ __('Logout') }}</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                <!-- End Logout Section -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

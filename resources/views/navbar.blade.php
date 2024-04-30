<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('home')}}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
        <form id="searchForm" class="form-inline" action="{{ route('search') }}" method="GET">
    <div class="input-group input-group-sm">
        <input name="term" id="searchInput" class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button id="searchButton" class="btn btn-navbar" type="button">
                <i class="fas fa-search"></i>
            </button>
            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</form>
    <p class="alert alert-danger w-100" style="display:none;" id="errorMessage"></p>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      @auth
      <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="fas fa-graduation-cap"></i> <!-- Icon for school connects -->
              <span class="badge badge-warning navbar-badge">{{ auth()->user()->profile->school_connects ?? 0 }}</span>
          </a>
          
          <!-- Dropdown menu content (if needed) -->
          <div class="dropdown-menu dropdown-menu-right">
              <!-- Dropdown items here -->
              <a class="dropdown-item" href="#">Your School Connects: {{ auth()->user()->profile->school_connects ?? 0}}</a>
              <!-- Additional dropdown items -->
          </div>
      </li>
      @endauth

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="drop-down nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
         
        </a>
      </li>
    </ul>
  </nav>
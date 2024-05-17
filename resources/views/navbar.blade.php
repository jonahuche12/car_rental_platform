<nav class="main-header navbar navbar-dark navbar-expand navbar-light">
    <!-- Left navbar links --->
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
              <input name="term" id="searchInput" class="form-control form-control" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                  <button id="searchButton" class="btn btn-navbar"   type="submit">
                      <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                      <i class="fas fa-times"></i>
                  </button>
              </div>
          </div>
      </form>
    <span class="alert alert-danger w-100 small-text" style="display:none;" id="errorMessage"></span>
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
        <a class="dropdown-item bg-purple text-white" href="#" data-toggle="modal" data-target="#yStudyConnectModal">
            <i class="fas fa-graduation-cap"></i> Top Your Study Connects
        </a>
        <!-- Additional dropdown items -->
    </div>
</li>
      @endauth

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
     
    </ul>




  </nav>
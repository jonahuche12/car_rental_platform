

<section class="content">

<div class="container-fluid">
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
        <h3>{{ $totalPackagesCount }}</h3>

          <b>School Packages</b>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href=" {{route('manage_school_packages')}} " class="small-box-footer">Manage School Packages <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>150</h3>

          <b>Curriculums</b>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="{{route('manage_curriculum')}}" class="small-box-footer">Curriculums<i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $totalSchoolsCount }}</h3>

          <b>Schools</b>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="#" class="small-box-footer">Manage Schools <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-secondary">
      <div class="inner">
        <h3>{{ $totalUserPackagesCount }}</h3>

          <b>User Packages</b>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href=" {{route('manage_user_packages')}} " class="small-box-footer">Manage User Packages <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>65</h3>

          <b>Courses</b>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="#" class="small-box-footer">Manage Courses <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

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
    <!-- ./col -->
  </div>
  <!-- /.row -->
  <!-- Main row -->
  <div class="row">
    
    <!-- right col -->
  </div>
  <!-- /.row (main row) -->
</div><!-- /.container-fluid -->
</section>
@extends('layouts.app')

@section('title', "Central School system - Manage Schools")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "Manage School")

@section('page_title')
 {{ $school->name }}
@endsection

@section('content')
@include('sidebar')
<section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <!-- Admins -->
          <div class="col-12 col-sm-6 col-md-2">
            <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'admin']) }}" class="text-decoration-none">
 

                  <div class="info-box">
                      <span class="info-box-icon bg-info elevation-1">
                          <div class="inner mt-2">
                              <i class="fas fa-users"></i>
                          </div>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Admins</span>
                          <span class="info-box-number"><h5> {{$school->getConfirmedAdmins()->count()}}</h5></span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Teachers -->
          <div class="col-12 col-sm-6 col-md-2">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'teachers']) }}" class="text-decoration-none">

                  <div class="info-box mb-3">
                      <span class="info-box-icon bg-danger elevation-1">
                          <div class="inner mt-2">
                              <i class="fas fa-chalkboard-teacher"></i>
                          </div>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Teachers</span>
                          <span class="info-box-number"><h5> {{$school->teachers()->count()}}</h5></span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Students -->
          <div class="col-12 col-sm-6 col-md-2">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'students']) }}" class="text-decoration-none">

                  <div class="info-box mb-3">
                      <span class="info-box-icon bg-success elevation-1">
                          <i class="fas fa-user-graduate"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Students</span>
                          <span class="info-box-number"> {{ $school->students->count() }} </span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Classes -->
          <div class="col-12 col-sm-6 col-md-2">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'classes']) }}" class="text-decoration-none">

                  <div class="info-box mb-3">
                      <span class="info-box-icon bg-secondary elevation-1">
                          <i class="fas fa-school"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Classes</span>
                          <span class="info-box-number">{{ $school->classes->count() }}</span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Events -->
          <div class="col-12 col-sm-6 col-md-2">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'events']) }}" class="text-decoration-none">
  
                  <div class="info-box mb-3">
                      <span class="info-box-icon bg-warning elevation-1">
                          <i class="fas fa-calendar-alt"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Events</span>
                          <span class="info-box-number"> <!-- Link to Events Page --></span>
                      </div>
                  </div>
              </a>
          </div>

          <!-- Curriculum -->
          <div class="col-12 col-sm-6 col-md-2">
              <a href="{{ route('school.show', ['schoolId' => $school->id, 'view' => 'curriculum']) }}" class="text-decoration-none">
 
                  <div class="info-box mb-3">
                      <span class="info-box-icon bg-black elevation-1">
                          <i class="fas fa-book"></i>
                      </span>
                      <div class="info-box-content">
                          <span class="info-box-text">Curriculum</span>
                          <span class="info-box-number"> <!-- Link to Curriculum Page --></span>
                      </div>
                  </div>
              </a>
          </div>
      </div>



        <!-- /.row -->

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Student And Teachers Stats</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                      <i class="fas fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                      <a href="#" class="dropdown-item graph-option" data-option="students">Students</a>
                      <a href="#" class="dropdown-item graph-option" data-option="teachers">Teachers</a>
                      <!-- Add other options if needed -->
                  </div>
                  </div>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Sales Chart Canvas -->
                        <canvas id="studentTeacherChart" height="180" style="height: 180px;"></canvas>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <!-- ... Other progress groups ... -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                      <h5 class="description-header">$35,210.43</h5>
                      <span class="description-text">TOTAL REVENUE</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                      <h5 class="description-header">$10,390.90</h5>
                      <span class="description-text">TOTAL COST</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">$24,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">1200</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </div><!--/. container-fluid -->
    </section>


@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var studentData = {{ $totalStudents }};
        var teacherData = {{ $totalTeachers }};
        var ctx = document.getElementById('studentTeacherChart').getContext('2d');

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Students', 'Teachers'],
                datasets: [{
                    label: 'Number of Students and Teachers',
                    data: [studentData, teacherData],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Add event listener to the dropdown options
        var dropdownOptions = document.querySelectorAll('.graph-option');
        dropdownOptions.forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                var selectedOption = event.target.getAttribute('data-option');

                // Fetch data for the selected option from the backend (you may need an AJAX request)

                // Example: Update the chart based on the selected option
                if (selectedOption === 'students') {
                    chart.data.datasets[0].data = [{{ $totalStudents }}, 0];
                } else if (selectedOption === 'teachers') {
                    chart.data.datasets[0].data = [0, {{ $totalTeachers }}];
                }

                // Update the chart
                chart.update();
            });
        });
    });
</script>

@endsection






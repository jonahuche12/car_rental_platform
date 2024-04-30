

<section class="content">

      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
              <h3>{{ auth()->user()->ownedSchools->count() }}</h3>

                <b>All Schools</b>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href=" {{route('manage_schools')}} " class="small-box-footer">Manage Schools <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>


          <!-- <div class="col-lg-4 col-6">
            
            <div class="small-box bg-info">
              <div class="inner">
                <h3>150</h3>

                <b>All Students</b>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">Manage Students <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
         
          <!-- <div class="col-lg-4 col-6">
           
            <div class="small-box bg-success">
              <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <b>Teachers</b>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">Manage Teachers <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
          <!-- <div class="col-lg-4 col-6">
            
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>{{44}}</h3>

                <b>Staff</b>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">Manage Admin <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
          <!-- <div class="col-lg-4 col-6">
            
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
          </div> -->

          <!-- <div class="col-lg-4 col-6">
            
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
          </div> -->

          
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
@extends('layouts.app')

@section('title', "Central School System - Guardian Profile")

@section('style')
<style>
  .complete_profile{
    display: none;
  }
  .profile_pic_style{
    cursor: pointer; 
    position: relative; 
    bottom: -10px; 
    right: 100px;
  }
  .qualification-container {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
  }

</style>
@endsection
@section('page_title', "Profile")
@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb3', "Profile")

@section('sidebar')
    @include('sidebar')
@endsection

@section('style')
<style>
    /* Background overlay for expanded details */
    .collapsed-details {
        height: 100%; /* Adjust the maximum height for scrollable area */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #fff; /* Text color for details */
    }

    /* Toggle button style */
    .toggle-details-btn {
        margin-top: 10px;
        padding: 8px 12px;
        background-color: #17a2b8; /* Your desired button color */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .toggle-details-btn i {
        margin-left: 5px;
    }

    /* Styling for detail labels and values */
    .detail-item {
        margin-bottom: 8px;
    }

    .detail-label {
        font-weight: bold;
    }

    .detail-value {
        color: #fff; /* Text color for detail values */
    }
</style>


@endsection

@section('content')


    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
         
          <!-- /.col -->
          <div class="col-md-8">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#wards" data-toggle="tab">Wards</a></li>
                  <!-- <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li> -->
                  <!-- <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li> -->
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                <div class="active tab-pane" id="wards">
                  @include('partials.wards')
                </div>

                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                   
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->

    </section>

@endsection

@section('scripts')



<script>
 
    
</script>

@endsection
@extends('layouts.app')
@section('title', "Central School system - Create School")
@section('content')
@include('sidebar')

<h3 class="mt-4 mb-4">Choose a Package</h3>
        <div class="row">
          
          <!-- /.col -->
          @forelse($school_packages as $school_package)
            <div class="col-md-4">
              <!-- Widget: user widget style 1 -->
              <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user- p-4 @if($school_package->name == 'Basic Package') bg-info @elseif($school_package->name == 'Standard Package') bg-primary @elseif($school_package->name == 'Premium Package') bg-warning @endif">
                  <h3 class="widget-user-username"><b>{{$school_package->name}}</b></h3>
                  <!-- <h5 class="widget-user-desc">Founder & CEO</h5> -->
                </div>
                <div class="widget-user-" style="height:150px;">
                  <img class="img-fluid elevation- h-100 w-100" width="100%"  src="{{ asset('storage/' . $school_package->picture) }}" alt="User Avatar">
                </div>
                <div class="card-body">
                        <p><strong>Description:</strong> <br>{{ $school_package->description }}</p>
                        <p><strong>Duration (in days):</strong> {{ $school_package->duration_in_days }}</p>
                        <p><strong>Maximum Students:</strong> {{ $school_package->max_students }}</p>
                        <p><strong>Maximum Teachers:</strong> {{ $school_package->max_teachers }}</p>
                        <p><strong>Maximum Admins:</strong> {{ $school_package->max_admins }}</p>
                        <p><strong>Maximum Classes:</strong> {{ $school_package->max_classes }}</p>
                        <p><strong>Price:</strong> ${{ $school_package->price }}</p>
                    </div>
                    <div class="card-footer">
                    <a href="{{ route('create-school', ['packageId' => $school_package->id]) }}" class="btn btn-primary">Get Package</a>

                    </div>
              </div>
              <!-- /.widget-user -->
            </div>
          @empty
            <tr>
              <td colspan="11" class="text-center">
                No school available.
              </td>
            </tr>
          @endforelse

         
        </div>

@endsection
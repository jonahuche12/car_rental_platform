@extends('layouts.app')

@section('title', "Scholarship Category - $category->name")

@section('breadcrumb2')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3')
<a href="#">Scholarships</a>
@endsection

@section('sidebar')
@include('sidebar')
@endsection

@section('page_title')
<h5><b>{{ $category->scholarship->title }}</b> - <em>{{ $category->name }}</em></h5>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Scholarship Category Information</div>
                <div class="card-body bg-white">
                    @if(now() < $category->start_date)
                    <!-- Countdown to Start Date -->
                    <div id="countdown" class="text-center">
                        <h2 class="mb-4" style="font-weight: bold;">Countdown to Start Date:</h2>
                        <div id="countdown-timer" style="font-size: 81px;"></div>
                    </div>
                    @elseif(now() >= $category->start_date && now() < $category->end_date)
                    <!-- Start Button -->
                    <div class="text-center">
                        <button id="start-button" class="btn btn-primary btn-lg" style="font-size: 50px;">Start Test</button>
                    </div>
                    @else
                    <!-- Event has ended -->
                    <div class="text-center">
                        <h2 class="mb-4" style="font-weight: bold;">The Scholarship has ended.</h2>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="startTestModal" tabindex="-1" role="dialog" aria-labelledby="startTestModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="startTestModalLabel">Confirm Start Test</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>You are about to start the scholarship test. Once you start, you cannot close or change the page/tab. If you do, your score will be automatically calculated based on your current progress.</p>
        <p>Are you sure you want to start the test?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="{{ route('start_test', ['category' => $category->id]) }}" class="btn btn-primary">Start Test</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    // Function to calculate countdown
    function countdown(targetDate) {
        var countDownDate = new Date(targetDate).getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown-timer").innerHTML = 
                "<span style='color: #FFA500;'>" + days + "d </span>" +
                "<span style='color: #4682B4;'>" + hours + "h </span>" +
                "<span style='color: #6B8E23;'>" + minutes + "m </span>" +
                "<span style='color: #8A2BE2;'>" + seconds + "s </span>";

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown-timer").innerHTML = "The event has started!";
                location.reload();
            }
        }, 1000);
    }

    countdown('{{ $category->start_date }}');

    document.getElementById('start-button').addEventListener('click', function() {
        $('#startTestModal').modal('show');
    });
</script>
@endsection

@extends('layouts.app')

@section('title', "Central School System $category->name")

@section('sidebar')
    @include('sidebar')
@endsection

@section('breadcrumb2')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3')
    {{ $category->name }}
@endsection

@section('page_title')
    {{ $category->scholarship->title . ' - ' . $category->name}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Scholarship Category Information</div>
                <div class="card-body bg-white">
                    @if(now() >= $category->start_date && now() < $category->end_date)
                    <!-- Display Test Details -->
                    <div class="row">
                        @foreach($category->tests as $test)
                        <div class="col-md-6">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>{{ $test->title }}</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Type:</strong> {{ $test->type }}</p>
                                    <p><strong>Description:</strong> {{ $test->description }}</p>
                                    <p><strong>Max No of Questions:</strong> {{ $test->max_no_of_questions }}</p>
                                    <p><strong>Complete Score:</strong> {{ $test->complete_score }}</p>
                                   
                                    <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#startTestModal" data-test-id="{{ $test->id }}" style="font-size: 20px;">Start Test</a>
                                 
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @elseif(now() >= $category->end_date)
                    <!-- Event has ended -->
                    <div class="text-center">
                        <h2 class="mb-4" style="font-weight: bold;">The test has ended.</h2>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start Test Modal -->
<div class="modal fade" id="startTestModal" tabindex="-1" role="dialog" aria-labelledby="startTestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="startTestModalLabel">Start Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to start this test?<br>
                Click the Start Test Button when you are ready.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a id="confirm-start-button" class="btn btn-primary" href="#">Start Test</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#startTestModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var testId = button.data('test-id');
        var modal = $(this);
        var startButton = modal.find('#confirm-start-button');

        startButton.attr('href', '/test_page/' + testId);
    });
</script>
@endsection

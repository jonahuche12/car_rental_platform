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
    {{ $category->scholarship->title . ' - ' . $category->name }}
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
                        @php
                            $cognitiveTestStatus = null;
                            $classLevelTestEnabled = false;
                        @endphp
                        @foreach($category->tests as $test)
                            @php
                                // Check if the test is cognitive
                                if ($test->type == 'cognitive') {
                                    $cognitiveTestGrade = $test->testGrades->where('user_id', auth()->id())->first();
                                    if ($cognitiveTestGrade) {
                                        $cognitiveTestStatus = $cognitiveTestGrade->passed ? 'Passed' : 'Failed';
                                    }
                                }

                                // Enable class level test if cognitive test is passed
                                if ($test->type == 'class_level' && $cognitiveTestStatus == 'Passed') {
                                    $classLevelTestEnabled = true;
                                }

                                // Get the user's grade for this test
                                $userTestGrade = $test->testGrades->where('user_id', auth()->id())->first();
                            @endphp
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
                                        @if($userTestGrade)
                                            <p><strong>Your Score:</strong> {{ $userTestGrade->score }}</p>
                                        @endif

                                        @if($test->type == 'cognitive')
                                            @if($cognitiveTestGrade)
                                                <button class="btn {{ $cognitiveTestGrade->passed ? 'btn-success' : 'btn-danger' }}" disabled>{{ $cognitiveTestGrade->passed ? 'Passed' : 'Failed' }}</button>
                                            @else
                                                <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#startTestModal" data-test-id="{{ $test->id }}" style="font-size: 20px;">Start Test</a>
                                            @endif
                                        @elseif($test->type == 'class_level')
                                            @if($userTestGrade)
                                                @if($userTestGrade->passed)
                                                    <button class="btn btn-success" disabled>Passed</button>
                                                @else
                                                    <button class="btn btn-danger" disabled>Failed</button>
                                                @endif
                                            @elseif($classLevelTestEnabled)
                                                <a class="btn btn-warning" href="#" data-toggle="modal" data-target="#startTestModal" data-test-id="{{ $test->id }}" style="font-size: 20px;">Start Test</a>
                                            @else
                                                <button class="btn btn-secondary" disabled>Complete Cognitive Test First</button>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @elseif(now() >= $category->end_date)
                    <!-- Event has ended -->
                    <div class="text-center">
                        <h2 class="mb-4" style="font-weight: bold;">The test has ended.</h2>
                        <div class="row">
                            @foreach($category->tests as $test)
                                @php
                                    // Get the user's grade for this test
                                    $userTestGrade = $test->testGrades->where('user_id', auth()->id())->first();
                                @endphp
                                <div class="col-md-6">
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h5>{{ $test->title }}</h5>
                                            <a href="#" data-toggle="modal" data-target="#retakeTestModal" data-test-id="{{ $test->id }}" style="font-size: 20px;">
                                                <i class="fa fa-redo"></i> Retake Test
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Type:</strong> {{ $test->type }}</p>
                                            <p><strong>Description:</strong> {{ $test->description }}</p>
                                            <p><strong>Max No of Questions:</strong> {{ $test->max_no_of_questions }}</p>
                                            <p><strong>Complete Score:</strong> {{ $test->complete_score }}</p>
                                            @if($userTestGrade)
                                                <p><strong>Your Score:</strong> {{ $userTestGrade->score }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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

<!-- Retake Test Modal -->
<div class="modal fade" id="retakeTestModal" tabindex="-1" role="dialog" aria-labelledby="retakeTestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="retakeTestModalLabel">Retake Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to retake this test?<br>
                Click the Retake Test Button when you are ready.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a id="confirm-retake-button" class="btn btn-primary" href="#">Retake Test</a>
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

    $('#retakeTestModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var testId = button.data('test-id');
        var modal = $(this);
        var retakeButton = modal.find('#confirm-retake-button');

        retakeButton.attr('href', '/test_page/' + testId);
    });
</script>
@endsection

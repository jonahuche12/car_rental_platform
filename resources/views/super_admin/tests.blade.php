@extends('layouts.app')

@section('title', "CSS - Tests")

@section('page_title', "All Tests")

@section('breadcrumb2')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3', "Tests")

@section('content')
@include('sidebar')

<div class="container mt-4">
    @foreach ($uniqueClassLevels as $classLevel)
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Class Level: {{ $classLevel }}</h3>
                <button class="btn btn-light" data-toggle="modal" data-target="#createTestModal{{ $classLevel }}">Create Test</button>
            </div>
            <div class="card-body" id="classLevel{{ $classLevel }}">
                @foreach ($tests->where('class_level', $classLevel)->groupBy('academic_session_id') as $sessionId => $sessionTests)
                    @php
                        $session = $sessionTests->first()->academicSession;
                    @endphp
                    <div class="mb-4">
                        <h4>Academic Session: {{ $session->name }}</h4>

                        @foreach ($sessionTests->groupBy('term_id') as $termId => $termTests)
                            @php
                                $term = $termTests->first()->term;
                            @endphp
                            <h5>Term: {{ $term->name }}</h5>

                            @foreach ($termTests->groupBy('type') as $type => $typeTests)
                                <h6>Type: {{ ucfirst($type) }}</h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Academic Session</th>
                                                <th>Term</th>
                                                <th>Class Level</th>
                                                <th>Questions Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($typeTests as $test)
                                                <tr>
                                                    <td>{{ $test->title }}</td>
                                                    <td>{{ ucfirst($test->type) }}</td>
                                                    <td>{{ $test->academicSession->name }}</td>
                                                    <td>{{ $test->term->name }}</td>
                                                    <td>{{ $test->class_level }}</td>
                                                    <td>{{ $test->questions->count() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Create Test Modal -->
        <div class="modal fade" id="createTestModal{{ $classLevel }}" tabindex="-1" role="dialog" aria-labelledby="createTestModalLabel{{ $classLevel }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="createTestModalLabel{{ $classLevel }}">Create Test for Class Level {{ $classLevel }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p class="alert alert-success" id="test-success-{{ $classLevel }}" style="display:none"></p>
                    <p class="alert alert-danger" id="test-error-{{ $classLevel }}" style="display:none"></p>
                    <form action="#" id="testForm{{ $classLevel }}" data-class_level="{{ $classLevel }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="class_level" value="{{ $classLevel }}">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="cognitive">Cognitive</option>
                                    <option value="class_level">Class Level</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_no_of_questions">Maximum Number of Questions</label>
                                <input type="number" class="form-control" name="max_no_of_questions" required>
                            </div>
                            <div class="form-group">
                                <label for="complete_score">Complete Score</label>
                                <input type="number" class="form-control" name="complete_score" required>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Test</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('[id^="testForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var classLevel = form.data('class_level');
            var url = '{{ route("tests.store") }}';

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function(response) {
                    $('#test-success-' + classLevel).text(response.message).fadeIn().delay(3000).fadeOut();
                    form.trigger('reset');
                    location.reload();
                },
                error: function(xhr) {
                    var error = JSON.parse(xhr.responseText);
                    $('#test-error-' + classLevel).text(error.message).fadeIn().delay(3000).fadeOut();
                }
            });
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('title', "CSS - Tests")

@section('page_title', "All Tests")

@section('breadcrumb2')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3', "Tests")

@section('style')
<style>
    .card-header {
        font-size: 1.1rem;
        font-weight: bold;
    }

    .btn-link {
        font-size: 1rem;
        text-align: left;
        width: 100%;
        color: #333;
    }

    .btn-link:hover {
        text-decoration: none;
        color: #0056b3;
    }

    .modal-content {
        border-radius: 0.5rem;
    }

    .btn-primary, .btn-secondary {
        border-radius: 0.25rem;
    }

    .btn-light {
        border-radius: 0.25rem;
    }

    .card {
        border-radius: 0.5rem;
    }

    .shadow {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-primary {
        border-color: #0056b3;
        color: #0056b3;
    }

    .btn-outline-primary:hover {
        background-color: #0056b3;
        color: #fff;
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .icon-buttons {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
    }

    .icon-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.9rem;
    }
</style>
@endsection
@section('content')
@include('sidebar')

<div class="container mt-4">
    @foreach ($uniqueClassLevels as $classLevel)
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Class Level: {{ $classLevel }}</h6>
                <button class="btn btn-light" data-toggle="modal" data-target="#createTestModal{{ $classLevel }}">
                    <i class="fas fa-plus-circle"></i> Create Test
                </button>
            </div>
            <div class="card-body">
                <button class="btn btn-outline-primary btn-block" data-toggle="collapse" data-target="#collapseClassLevel{{ $classLevel }}">
                    <i class="fas fa-chevron-down"></i> Show Tests
                </button>
                <div id="collapseClassLevel{{ $classLevel }}" class="collapse mt-3">
                    @foreach ($tests->where('class_level', $classLevel)->groupBy('academic_session_id') as $sessionId => $sessionTests)
                        @php
                            $session = $sessionTests->first()->academicSession;
                        @endphp
                        <div class="mb-4">
                            <button class="btn btn-link text-secondary" data-toggle="collapse" data-target="#collapseSession{{ $sessionId }}{{ $classLevel }}">
                                Academic Session: {{ $session->name }}
                            </button>
                            <div id="collapseSession{{ $sessionId }}{{ $classLevel }}" class="collapse">
                                @foreach ($sessionTests->groupBy('term_id') as $termId => $termTests)
                                    @php
                                        $term = $termTests->first()->term;
                                    @endphp
                                    <button class="btn btn-link text-info ml-3" data-toggle="collapse" data-target="#collapseTerm{{ $term->id }}{{ $classLevel }}">
                                        Term: {{ $term->name }}
                                    </button>
                                    <div id="collapseTerm{{ $term->id }}{{ $classLevel }}" class="collapse">
                                        <div class="row">
                                            @foreach ($termTests->groupBy('type') as $type => $typeTests)
                                                <div class="col-md-4">
                                                    <div class="d-flex flex-wrap">
                                                        @foreach ($typeTests as $test)
                                                        <div class="card bg-dark text-light m-2 shadow position-relative" style="width: 18rem;">
                                                            <div class="card-body">
                                                                <div class="icon-buttons">
                                                                    <button class="btn btn-warning btn-sm" title="Edit" data-toggle="modal" data-target="#editTestModal{{ $test->id }}" data-test_id="{{ $test->id }}">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button class="btn btn-danger btn-sm" title="Delete" data-toggle="modal" data-target="#deleteTestModal" data-test_id="{{ $test->id }}" data-test-title="{{ $test->title }}">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                                                    </button>
                                                                </div>
                                                                <a href="{{ route('tests.show', ['test' => $test->id]) }}">
                                                                    <h5 class="card-title">{{ $test->title }}</h5>
                                                                </a>

                                                                <p class="card-text"><strong>Type:</strong> {{ ucfirst($test->type) }}</p>
                                                                <p class="card-text"><strong>Academic Session:</strong> {{ $test->academicSession->name }}</p>
                                                                <p class="card-text"><strong>Term:</strong> {{ $test->term->name }}</p>
                                                                <p class="card-text"><strong>Class Level:</strong> {{ $test->class_level }}</p>
                                                                <p class="card-text"><strong>Questions Count:</strong> {{ $test->questions->count() }}</p>
                                                            </div>
                                                        </div>

                                                        <!-- Edit Test Modal -->
                        <div class="modal fade" id="editTestModal{{ $test->id }}" tabindex="-1" role="dialog" aria-labelledby="editTestModalLabel{{ $test->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="editTestModalLabel{{ $test->id }}">Edit Test - {{ $test->title }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <p class="alert alert-success" id="edit-success-{{ $test->id }}" style="display:none"></p>
                                    <p class="alert alert-danger" id="edit-error-{{ $test->id }}" style="display:none"></p>
                                    <form action="{{ route('tests.update', ['test' => $test->id]) }}" id="editTestForm{{ $test->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control" name="title" value="{{ $test->title }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="type">Type</label>
                                                <select class="form-control" name="type" required>
                                                    <option value="cognitive" {{ $test->type == 'cognitive' ? 'selected' : '' }}>Cognitive</option>
                                                    <option value="class_level" {{ $test->type == 'class_level' ? 'selected' : '' }}>Class Level</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="max_no_of_questions">Maximum Number of Questions</label>
                                                <input type="number" class="form-control" name="max_no_of_questions" value="{{ $test->max_no_of_questions }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="complete_score">Complete Score</label>
                                                <input type="number" class="form-control" name="complete_score" value="{{ $test->complete_score }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="duration">Duration(mins)</label>
                                                <input type="number" class="form-control" name="duration" value="{{ $test->duration }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                                Update Test
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Create Test Modal -->
        <div class="modal fade" id="createTestModal{{ $classLevel }}" tabindex="-1" role="dialog" aria-labelledby="createTestModalLabel{{ $classLevel }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createTestModalLabel{{ $classLevel }}">Create Test for Class Level {{ $classLevel }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p class="alert alert-success" id="test-success" style="display:none"></p>
                    <p class="alert alert-danger" id="test-error" style="display:none"></p>
                    <form action="{{ route('tests.store') }}" id="testForm{{ $classLevel }}" method="POST">
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
                            <div class="form-group">
                                <label for="complete_score">Duration (mins)</label>
                                <input type="duration" class="form-control" name="duration" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                Create Test
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteTestModal" tabindex="-1" role="dialog" aria-labelledby="deleteTestModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteTestModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p class="alert alert-success" id="test-delete-success" style="display:none"></p>
                    <p class="alert alert-danger" id="test-delete-error" style="display:none"></p>
                    <div class="modal-body">
                        Are you sure you want to delete this test <b><span id="test-title"></span></b>? This will also delete all related questions and answers.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

 <!-- Edit Test Modal -->
             
@section('scripts')
<script>
    $(document).ready(function() {
        // Handle the test creation form submission
        $('[id^="testForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var classLevel = form.data('class_level');
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function() {
                    form.find('.spinner-border').show();
                },
                success: function(response) {
                    form.find('.spinner-border').hide();
                    $('#test-success').text(response.message).fadeIn().delay(3000).fadeOut();
                    location.reload()

                },
                error: function(xhr) {
                    form.find('.spinner-border').hide();
                    var errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $('#test-error').text(errorMessage).fadeIn().delay(3000).fadeOut();
                }
            });
        });

        // Handle the test edit form submission
        $('[id^="editTestForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var testId = form.attr('id').replace('editTestForm', '');
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function() {
                    form.find('.spinner-border').show();
                },
                success: function(response) {
                    form.find('.spinner-border').hide();
                    $('#edit-success-' + testId).text(response.message).show();
                    setTimeout(function() {
                        $('#edit-success-' + testId).hide();
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    form.find('.spinner-border').hide();
                    $('#edit-error-' + testId).text(xhr.responseText).show();
                }
            });
        });

        // Handle collapse of terms within a session
        $('[data-target^="#collapseTerm"]').on('click', function() {
            var termId = $(this).data('target');
            $(termId).collapse('toggle');
        });

        // Handle collapse of sessions within a class level
        $('[data-target^="#collapseSession"]').on('click', function() {
            var sessionId = $(this).data('target');
            $(sessionId).collapse('toggle');
        });

        // Handle collapse of class levels
        $('[data-target^="#collapseClassLevel"]').on('click', function() {
            var classLevelId = $(this).data('target');
            // Collapse all other elements except the one being expanded
            $('.collapse').not(classLevelId).collapse('hide');
        });

        // Ensure only one term is open at a time within a session
        $('[data-target^="#collapseTerm"]').on('show.bs.collapse', function() {
            var currentTerm = $(this).data('target');
            $(currentTerm).parent().find('.collapse').not(currentTerm).collapse('hide');
        });

        // Ensure only one session is open at a time within a class level
        $('[data-target^="#collapseSession"]').on('show.bs.collapse', function() {
            var currentSession = $(this).data('target');
            $(currentSession).parent().find('.collapse').not(currentSession).collapse('hide');
        });


        $('[id^="editTestForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var testId = form.attr('id').replace('editTestForm', '');
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function() {
                    form.find('.spinner-border').show();
                },
                success: function(response) {
                    form.find('.spinner-border').hide();
                    $('#edit-success-' + testId).text(response.message).show();
                    setTimeout(function() {
                        $('#edit-success-' + testId).hide();
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    form.find('.spinner-border').hide();
                    $('#edit-error-' + testId).text(xhr.responseText).show();
                }
            });
        });

        var testIdToDelete = null;

        // Handle the delete button click
        $('.btn-danger[data-toggle="modal"]').on('click', function() {
            var button = $(this);
            testIdToDelete = button.data('test_id');
            title = button.data('test-title')
            console.log(title)
            $('#test-title').text(title)
        });

        var testIdToDelete = null;

    // Handle the delete button click
    $('.btn-danger[data-toggle="modal"]').on('click', function() {
        var button = $(this);
        testIdToDelete = button.data('test_id');
    });

    // Handle the confirm delete button click
    $('#confirmDeleteBtn').on('click', function() {
        if (testIdToDelete) {
            var button = $('.btn-danger[data-test_id="' + testIdToDelete + '"]');
            $.ajax({
                type: 'DELETE',
                url: '/tests/' + testIdToDelete,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    button.find('.spinner-border').show();
                },
                success: function(response) {
                    button.find('.spinner-border').hide();
                    $('#test-delete-success').text(response.message).fadeIn().delay(3000).fadeOut();
                    button.closest('.card').fadeOut(300, function() {
                    $('#deleteTestModal').modal('hide');
                        $(this).remove();
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    button.find('.spinner-border').hide();
                    $('#deleteTestModal').modal('hide');
                    $('#test-delete-error').text(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
                }
            });
        }
    });
    });


</script>
@endsection

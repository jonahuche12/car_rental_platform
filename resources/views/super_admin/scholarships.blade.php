@extends('layouts.app')

@section('title', "CSS - scholarships")

@section('page_title', "All scholarships")

@section('breadcrumb2')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3', "scholarships")

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
        background-color: #f8f9fa; /* Light background for each card */
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

    .scholarship-card {
        width: 100%;
        background-color: #ffffff; /* Ensure individual cards have a white background */
        border: 1px solid #e9ecef;
        margin-bottom: 1rem;
    }

    .scholarship-card .card-body {
        position: relative;
    }

    @media (max-width: 768px) {
        .scholarship-card {
            width: 100% !important;
        }
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
                <button class="btn btn-light create-scholarship-btn" data-toggle="modal" data-target="#createScholarshipModal" data-class-level="{{ $classLevel }}">
                    <i class="fas fa-plus-circle"></i> Create Scholarship
                </button>
            </div>
            <div class="card-body">
                <button class="btn btn-outline-primary btn-block" data-toggle="collapse" data-target="#collapseClassLevel{{ $classLevel }}">
                    <i class="fas fa-chevron-down"></i> Show Scholarships
                </button>
                <div id="collapseClassLevel{{ $classLevel }}" class="collapse mt-3">
                    @foreach ($scholarships->where('class_level', $classLevel)->groupBy('academic_session_id') as $sessionId => $sessionscholarships)
                        @php
                            $session = $sessionscholarships->first()->academicSession;
                        @endphp
                        <div class="mb-4">
                            <button class="btn btn-link text-secondary" data-toggle="collapse" data-target="#collapseSession{{ $sessionId }}{{ $classLevel }}">
                                Academic Session: {{ $session->name }}
                            </button>
                            <div id="collapseSession{{ $sessionId }}{{ $classLevel }}" class="collapse">
                                @foreach ($sessionscholarships->groupBy('term_id') as $termId => $termscholarships)
                                    @php
                                        $term = $termscholarships->first()->term;
                                    @endphp
                                    <button class="btn btn-link text-info ml-3" data-toggle="collapse" data-target="#collapseTerm{{ $term->id }}{{ $classLevel }}">
                                        Term: {{ $term->name }}
                                    </button>
                                    <div id="collapseTerm{{ $term->id }}{{ $classLevel }}" class="collapse">
                                        <div class="row">
                                            @foreach ($termscholarships->groupBy('type') as $type => $typescholarships)
                                                <div class="col-md-12 mb-2">
                                                    <div class="d-flex flex-wrap justify-content-center">
                                                        @foreach ($typescholarships as $scholarship)
                                                        <div class="card scholarship-card bg-light text-dark m-2 shadow position-relative">
                                                            <div class="card-body">
                                                                <div class="icon-buttons d-flex justify-content-between">
                                                                    <div>
                                                                        <button class="btn btn-warning btn-sm" title="Edit" data-toggle="modal" data-target="#editScholarshipModal{{ $scholarship->id }}" data-scholarship_id="{{ $scholarship->id }}">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger btn-sm" title="Delete" data-toggle="modal" data-target="#deleteScholarshipModal" data-scholarship_id="{{ $scholarship->id }}" data-scholarship-title="{{ $scholarship->title }}">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                    <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#createScholarshipCategoryModal{{ $scholarship->id }}">
                                                                        <i class="fas fa-plus-circle"></i> Add Category
                                                                    </button>
                                                                </div>
                                                                <a href="{{ route('scholarships.show', ['scholarship' => $scholarship->id]) }}">
                                                                    <h5 class="card-title">{{ $scholarship->title }}</h5>
                                                                </a>
                                                                <p class="card-text"><strong>Type:</strong> {{ ucfirst($scholarship->type) }}</p>
                                                                <p class="card-text"><strong>Academic Session:</strong> {{ $scholarship->academicSession->name }}</p>
                                                                <p class="card-text"><strong>Term:</strong> {{ $scholarship->term->name }}</p>
                                                                <p class="card-text"><strong>Class Level:</strong> {{ $scholarship->class_level }}</p>
                                                                <p class="card-text"><strong>Category Count:</strong> <span id="category-count-{{ $scholarship->id }}">{{ $scholarship->categories->count() }}</span></p>
                                                            </div>
                                                        </div>

    <!-- Edit Scholarship Modal -->
    <div class="modal fade" id="editScholarshipModal{{ $scholarship->id }}" tabindex="-1" role="dialog" aria-labelledby="editScholarshipModalLabel{{ $scholarship->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editScholarshipModalLabel{{ $scholarship->id }}">Edit Scholarship - {{ $scholarship->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <p class="alert alert-success" id="edit-success-{{ $scholarship->id }}" style="display:none"></p>
                <p class="alert alert-danger" id="edit-error-{{ $scholarship->id }}" style="display:none"></p>
                <form action="{{ route('scholarships.update', ['scholarship' => $scholarship->id]) }}" id="editScholarshipForm{{ $scholarship->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" value="{{ $scholarship->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" rows="3" required>{{ $scholarship->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            Update Scholarship
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Scholarship Category Modal -->
    <div class="modal fade" id="createScholarshipCategoryModal{{ $scholarship->id }}" tabindex="-1" role="dialog" aria-labelledby="createScholarshipCategoryModalLabel{{ $scholarship->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createScholarshipCategoryModalLabel{{ $scholarship->id }}">Create Scholarship Category - {{ $scholarship->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <p class="alert alert-success" id="category-success-{{ $scholarship->id }}" style="display:none"></p>
                <p class="alert alert-danger" id="category-error-{{ $scholarship->id }}" style="display:none"></p>
                <form action="{{ route('scholarship_categories.store') }}" id="createScholarshipCategoryForm" method="POST">
                    @csrf
                    <input type="hidden" name="scholarship_id" value="{{ $scholarship->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="required_viewed_lessons">Required Viewed Lessons</label>
                            <input type="number" class="form-control" name="required_viewed_lessons" required>
                        </div>
                        <div class="form-group">
                            <label for="reward_amount">Reward Amount</label>
                            <input type="number" class="form-control" name="reward_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="required_connects">Required Connects</label>
                            <input type="number" class="form-control" name="required_connects" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            Create Category
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
    @endforeach
</div>

<!-- Create Scholarship Modal -->
<div class="modal fade" id="createScholarshipModal" tabindex="-1" role="dialog" aria-labelledby="createScholarshipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createScholarshipModalLabel">Create Scholarship</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="scholarship-success" style="display:none"></p>
            <p class="alert alert-danger" id="scholarship-error" style="display:none"></p>
            <form action="{{ route('scholarships.store') }}" id="ScholarshipForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="class_level" id="scholarshipClassLevel">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Create Scholarship
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteScholarshipModal" tabindex="-1" role="dialog" aria-labelledby="deleteScholarshipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteScholarshipModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="scholarship-delete-success" style="display:none"></p>
            <p class="alert alert-danger" id="scholarship-delete-error" style="display:none"></p>
            <div class="modal-body">
                Are you sure you want to delete this Scholarship <b><span id="scholarship-title"></span></b>? This will also delete all related questions and answers.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

 <!-- Edit Scholarship Modal -->
             
@section('scripts')
<script>
    $(document).ready(function() {
        document.querySelectorAll('.create-scholarship-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var classLevel = this.getAttribute('data-class-level');
            document.getElementById('scholarshipClassLevel').value = classLevel;
            document.getElementById('createScholarshipModalLabel').innerText = 'Create Scholarship for Class Level ' + classLevel;
        });
    });
        // Handle the Scholarship creation form submission
        $('[id^="ScholarshipForm"]').on('submit', function(e) {
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
                    $('#scholarship-success').text(response.message).fadeIn().delay(3000).fadeOut();
                    location.reload()

                },
                error: function(xhr) {
                    console.log(xhr.responseText)
                    form.find('.spinner-border').hide();
                    var errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $('#scholarship-error').text(errorMessage).fadeIn().delay(3000).fadeOut();
                }
            });
        });

        // Handle the Scholarship edit form submission
        $('[id^="editScholarshipForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var ScholarshipId = form.attr('id').replace('editScholarshipForm', '');
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function() {
                    form.find('.spinner-border').show();
                },
                success: function(response) {
                    form.find('.spinner-border').hide();
                    $('#edit-success-' + ScholarshipId).text(response.message).show();
                    setTimeout(function() {
                        $('#edit-success-' + ScholarshipId).hide();
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    form.find('.spinner-border').hide();
                    $('#edit-error-' + ScholarshipId).text(xhr.responseText).show();
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


        $('[id^="editScholarshipForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var ScholarshipId = form.attr('id').replace('editScholarshipForm', '');
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function() {
                    form.find('.spinner-border').show();
                },
                success: function(response) {
                    form.find('.spinner-border').hide();
                    $('#edit-success-' + ScholarshipId).text(response.message).show();
                    setTimeout(function() {
                        $('#edit-success-' + ScholarshipId).hide();
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    form.find('.spinner-border').hide();
                    $('#edit-error-' + ScholarshipId).text(xhr.responseText).show();
                }
            });
        });

        var scholarshipIdToDelete = null;

        // Handle the delete button click
        $('.btn-danger[data-toggle="modal"]').on('click', function() {
            var button = $(this);
            scholarshipIdToDelete = button.data('scholarship_id');
            title = button.data('scholarship-title')
            console.log(title)
            $('#scholarship-title').text(title)
        });

        var scholarshipIdToDelete = null;

    // Handle the delete button click
    $('.btn-danger[data-toggle="modal"]').on('click', function() {
        var button = $(this);
        scholarshipIdToDelete = button.data('scholarship_id');
    });

    // Handle the confirm delete button click
        $('#confirmDeleteBtn').on('click', function() {
            if (scholarshipIdToDelete) {
                var button = $('.btn-danger[data-scholarship_id="' + scholarshipIdToDelete + '"]');
                console.log(scholarshipIdToDelete)
                console.log(button)
                $.ajax({
                    type: 'DELETE',
                    url: '/scholarships/' + scholarshipIdToDelete,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        button.find('.spinner-border').show();
                    },
                    success: function(response) {
                        button.find('.spinner-border').hide();
                        $('#scholarship-delete-success').text(response.message).fadeIn().delay(3000).fadeOut();
                        button.closest('.card').fadeOut(300, function() {
                        // $('#deleteScholarshipModal').modal('hide');
                            $(this).remove();
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        button.find('.spinner-border').hide();
                        // $('#deleteScholarshipModal').modal('hide');
                        $('#scholarship-delete-error').text(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
                    }
                });
            }
        });
        // Handle the Scholarship category creation form submission
        $('[id^="createScholarshipCategoryForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var scholarshipId = form.find('input[name="scholarship_id"]').val();
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function() {
                    form.find('.spinner-border').show();
                },
                success: function(response) {
                    form.find('.spinner-border').hide();
                    $('#category-success-' + scholarshipId).text(response.message).fadeIn();

                    var categoryCountElement = $('#category-count-' + scholarshipId);
                    var currentCount = parseInt(categoryCountElement.text());
                    categoryCountElement.text(currentCount + 1);

                    // Display success message for 3 seconds
                    setTimeout(function() {
                        $('#category-success-' + scholarshipId).fadeOut();

                        // Wait for 2 seconds before hiding the modal
                        setTimeout(function() {
                            $('#createScholarshipCategoryModal' + scholarshipId).modal('hide');
                        }, 2000);
                    }, 3000);
                },
                error: function(xhr) {
                    form.find('.spinner-border').hide();
                    var errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $('#category-error-' + scholarshipId).text(errorMessage).fadeIn().delay(3000).fadeOut();
                }
            });
        });
    });


</script>
@endsection

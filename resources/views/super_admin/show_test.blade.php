@extends('layouts.app')

@section('title', "Test Details")

@section('style')

<style>
    .list-group-item {
        margin-bottom: 10px;
    }

    .list-group-item .btn-group .btn {
        margin-right: 5px;
    }

    .position-relative {
        position: relative;
    }

    .img-thumbnail {
        width: 100px;
        height: 100px;
    }

    @media (max-width: 767.98px) {
        .btn-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-group .btn {
            margin-bottom: 5px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    }
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
    }
    .info-box {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        background-color: #fff;
    }

    .card-body p {
        margin-bottom: 10px;
    }

    .btn-primary {
        margin-top: 15px;
    }
</style>

@endsection

@section('breadcrumb3', "Tests")
@section('breadcrumb2')
<a href="{{ route('tests.show', ['test' => $test->id]) }}">{{ $test->title }}</a>
@endsection

@section('content')
@include('sidebar')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $test->title }}</h5>
        </div>
        <div class="card-body">
        <div class="card mt-4">
            <div class="card-body">
                <div class="info-box">
                    <p><strong>Type:</strong> {{ ucfirst($test->type) }}</p>
                </div>
                <div class="info-box">
                    <p><strong>Academic Session:</strong> {{ $test->academicSession->name }}</p>
                </div>
                <div class="info-box">
                    <p><strong>Term:</strong> {{ $test->term->name }}</p>
                </div>
                <div class="info-box">
                    <p><strong>Class Level:</strong> {{ $test->class_level }}</p>
                </div>
                <div class="info-box">
                    <p><strong>Questions Count:</strong> {{ $test->questions->count() }}</p>
                </div>
                
                <!-- Add questions button -->
                <div class="text-center mt-3">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addQuestionModal">
                        <i class="fas fa-plus-circle"></i> Add Questions
                    </button>
                </div>
            </div>
        </div>




            <!-- Display questions -->
            <div class="mt-4">
                <h5>Questions</h5>
                <ul class="list-group">
                    @foreach($test->questions as $question)
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <span class="btn-link" style="cursor:pointer" data-toggle="collapse" data-target="#answersCollapse{{ $question->id }}">{{ $question->question }}</span>
                            <div class="btn-group mt-2 mt-md-0">
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addAnswerModal" data-question-id="{{ $question->id }}">
                                    <i class="fas fa-plus-circle"></i> Add Answers({{ $question->answers->count() }})
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editQuestionModal" data-question-id="{{ $question->id }}" data-question="{{ $question->question }}" data-answer-type="{{ $question->answer_type }}">
                                    <i class="fas fa-edit"></i> Edit Question
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-question-btn" data-question-id="{{ $question->id }}">
                                    <i class="fas fa-trash-alt"></i> Delete Question
                                </button>
                            </div>
                        </li>
                        <div id="answersCollapse{{ $question->id }}" class="collapse">
                            <ul class="list-group">
                                @foreach($question->answers as $answer)
                                    <li class="list-group-item ml-4 d-flex justify-content-between align-items-center flex-wrap">
                                        <span>{{ $answer->answer }} </span>
                                        @if(count($answer->images) > 0)
                                            <div class="d-flex flex-wrap">
                                                @foreach($answer->images as $image)
                                                    <div class="position-relative mr-2 mb-2">
                                                        <img src="{{ asset('storage/'.$image) }}" alt="Answer Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                                                        <button class="btn btn-sm btn-danger delete-answer-image-btn" style="position: absolute; top: 5px; right: 5px;" data-answer-id="{{ $answer->id }}" data-image="{{ $image }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="btn-group mt-2 mt-md-0">
                                            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editAnswerModal" data-answer-id="{{ $answer->id }}" data-answer="{{ $answer->answer }}"data-score_point="{{ $answer->score_point
                                             }}" data-is_correct="{{ $answer->is_correct }}">
                                                <i class="fas fa-edit"></i> Edit Answer
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-answer-btn" data-answer-id="{{ $answer->id }}">
                                                <i class="fas fa-trash-alt"></i> Delete Answer
                                            </button>
                                            @if($answer->is_correct)
                                            <button class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-check"></i> Correct
                                            </button>
                                            @else
                                            <button class="btn btn-sm btn-outline-danger " >
                                                <i class="fas fa-times"></i>Incorrect
                                            </button>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </ul>
            </div>



        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addQuestionModalLabel">Add Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="question-message" style="display:none"></p>
            <p class="alert alert-danger" id="question-error" style="display:none"></p>
            <form id="addQuestionForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="test_id" value="{{ $test->id }}">

                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" class="form-control" id="question" name="question" required>
                    </div>

                    <div class="form-group">
                        <label for="answer_type">Answer Type</label>
                        <select class="form-control" id="answer_type" name="answer_type" required>
                            <option value="radio">Radio</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="images">Images</label>
                        <input type="file" class="form-control-file" id="images" name="images[]" multiple>
                        <div id="images-preview" class="mt-2"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Add Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Question Modal -->
<div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="edit-question-message" style="display:none"></p>
            <p class="alert alert-danger" id="edit-question-error" style="display:none"></p>
            <form id="editQuestionForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="question_id" id="edit_question_id">

                    <div class="form-group">
                        <label for="edit_question">Question</label>
                        <input type="text" class="form-control" id="edit_question" name="question" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_answer_type">Answer Type</label>
                        <select class="form-control" id="edit_answer_type" name="answer_type" required>
                            <option value="radio">Radio</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Existing Images</label>
                        <div id="existing-images" class="mb-2"></div>
                    </div>

                    <div class="form-group">
                        <label for="new_question_images">Add More Images</label>
                        <input type="file" class="form-control-file" id="new_question_images" name="new_images[]" multiple>
                        <div id="new-images-preview" class="mt-2"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Update Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Answer Modal -->
<div class="modal fade" id="addAnswerModal" tabindex="-1" role="dialog" aria-labelledby="addAnswerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addAnswerModalLabel">Add Answer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="answer-message" style="display:none"></p>
            <p class="alert alert-danger" id="answer-error" style="display:none"></p>
            <form id="addAnswerForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="text" name="question_id" id="answer_question_id">

                    <div class="form-group">
                        <label for="answer">Answer</label>
                        <input type="text" class="form-control" id="answer" name="answer" required>
                    </div>

                    <div class="form-group">
                        <label for="score_point">Score Point</label>
                        <input type="number" class="form-control" id="score_point" name="score_point" >
                    </div>

                    <div class="form-group">
                        <label for="is_correct">Is Correct</label>
                        <select class="form-control" id="is_correct" name="is_correct" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="answer_images">Attach Images</label>
                        <input type="file" class="form-control-file" id="answer_images" name="answer_images[]" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Add Answer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Answer Modal -->
<div class="modal fade" id="editAnswerModal" tabindex="-1" role="dialog" aria-labelledby="editAnswerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editAnswerModalLabel">Edit Answer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="edit-answer-message" style="display:none"></p>
            <p class="alert alert-danger" id="edit-answer-error" style="display:none"></p>
            <form id="editAnswerForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="answer_id" id="edit_answer_id">

                    <div class="form-group">
                        <label for="edit_answer">Answer</label>
                        <input type="text" class="form-control" id="edit_answer" name="answer" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_score_point">Score Point</label>
                        <input type="number" class="form-control" id="edit_score_point" name="edit_score_point" >
                    </div>
                    <div class="form-group">
                        <label for="edit_is_correct">Is Correct</label>
                        <select class="form-control" id="edit_is_correct" name="edit_is_correct" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>



                    <div class="form-group">
                        <label>Existing Images</label>
                        <div id="existing-images" class="mb-2"></div>
                    </div>

                    <div class="form-group">
                        <label for="new_answer_images">Add More Images</label>
                        <input type="file" class="form-control-file" id="new_answer_images" name="new_answer_images[]" multiple>
                        <div id="new-answer-images-preview" class="mt-2"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Update Answer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle add question form submission
    $('#addQuestionForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        var submitButton = form.find('button[type="submit"]');
        var spinner = submitButton.find('.spinner-border');

        submitButton.prop('disabled', true);
        spinner.show();

        $.ajax({
            type: 'POST',
            url: '{{ route("questions.store") }}',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#question-message').text(response.message).fadeIn().delay(3000).fadeOut();
                $('#addQuestionModal').modal('hide');
                form.trigger('reset');
                submitButton.prop('disabled', false);
                spinner.hide();
                setTimeout(function() {
                    location.reload();
                }, 3000);
            },
            error: function(xhr) {
                $('#question-error').text(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
                submitButton.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Handle delete question button click
    $('.delete-question-btn').on('click', function() {
        if (!confirm('Are you sure you want to delete this question?')) {
            return;
        }
        
        var button = $(this);
        var questionId = button.data('question-id');
        
        $.ajax({
            type: 'DELETE',
            url: `/questions/${questionId}`,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                button.closest('li').remove();
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

    // Function to preview selected images
    function previewImages(input, previewContainer) {
        $(previewContainer).empty();
        if (input.files) {
            $.each(input.files, function(index, file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(previewContainer).append(`
                        <div class="image-preview" style="display: inline-block; margin: 5px;">
                            <img src="${e.target.result}" alt="Image" style="width: 100px; height: 100px;">
                        </div>
                    `);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // Attach change event to image input fields for preview
    $('#images').on('change', function() {
        previewImages(this, '#images-preview');
    });

    $('#new_question_images').on('change', function() {
        previewImages(this, '#new-images-preview');
    });

    $('#new_answer_images').on('change', function() {
        previewImages(this, '#new-answer-images-preview');
    });

    // Set data in the edit question modal
    $('#editQuestionModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var questionId = button.data('question-id');
        var questionText = button.data('question');
        var answerType = button.data('answer-type');
        var modal = $(this);

        modal.find('#edit_question_id').val(questionId);
        modal.find('#edit_question').val(questionText);
        modal.find('#edit_answer_type').val(answerType);

        // Load existing images
        $.ajax({
            type: 'GET',
            url: `/questions/${questionId}/images`,
            success: function(response) {
                var imagesContainer = modal.find('#existing-images');
                imagesContainer.empty();
                response.images.forEach(function(image) {
                    imagesContainer.append(`
                        <div class="image-preview" style="display: inline-block; position: relative; margin: 5px;">
                            <img src="/storage/${image}" alt="Image" style="width: 100px; height: 100px;">
                            <button type="button" class="btn btn-danger btn-sm remove-image" style="position: absolute; top: 5px; right: 5px;" data-image="${image}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                });
            }
        });
    });

    // Handle remove image button click
    $(document).on('click', '.remove-image', function() {
        var imageElement = $(this).parent();
        var image = $(this).data('image');
        var questionId = $('#edit_question_id').val();

        $.ajax({
            type: 'DELETE',
            url: `/questions/${questionId}/images`,
            data: {
                _token: '{{ csrf_token() }}',
                image: image
            },
            success: function(response) {
                imageElement.remove();
                alert(response.message);
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

    // Handle edit question form submission
    $('#editQuestionForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        var submitButton = form.find('button[type="submit"]');
        var spinner = submitButton.find('.spinner-border');
        var questionId = form.find('#edit_question_id').val();

        submitButton.prop('disabled', true);
        spinner.show();

        $.ajax({
            type: 'POST',
            url: '{{ route("questions.update", ":id") }}'.replace(':id', questionId),
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                submitButton.prop('disabled', false);
                spinner.hide();
                $('#edit-question-message').text(response.message).fadeIn().delay(3000).fadeOut();
                setTimeout(function() {
                    location.reload();
                }, 3000);
            },
            error: function(xhr) {
                $('#edit-question-error').text(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
                submitButton.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Handle Add Answer modal show event
    $('#addAnswerModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let questionId = button.data('question-id');
        let modal = $(this);
        modal.find('#answer_question_id').val(questionId);
    });

    // Handle Add Answer form submission
    $('#addAnswerForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let formData = new FormData(form[0]);
        let spinner = form.find('.spinner-border');
        spinner.show();

        $.ajax({
            url: '{{ route('answers.store') }}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#answer-message').text(response.message).show();
                form.trigger("reset");
                $('#answer-images-preview').empty();
                $('#addAnswerModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                $('#answer-error').text(errors[Object.keys(errors)[0]][0]).show();
            },
            complete: function() {
                spinner.hide();
            }
        });
    });

    // Handle image previews
    $('#images, #new_images, #answer_images').on('change', function() {
        let input = $(this);
        let previewContainer = input.siblings('.mt-2');

        previewContainer.empty();
        Array.from(input[0].files).forEach(file => {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail mr-2').css({ width: '100px', height: '100px' });
                previewContainer.append(img);
            };
            reader.readAsDataURL(file);
        });
    });

    $('.delete-answer-image-btn').click(function() {
        var imageElement = $(this).parent();
        var image = $(this).data('image');
        var answerId = $(this).data('answer-id');

        $.ajax({
            type: 'DELETE',
            url: `/answers/${answerId}/images`,
            data: {
                _token: '{{ csrf_token() }}',
                image: image
            },
            success: function(response) {
                imageElement.remove();
                alert(response.message);
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

    // Handle remove answer image button click
    $(document).on('click', '.remove-answer-image', function() {
        var imageElement = $(this).parent();
        var image = $(this).data('image');
        var answerId = $('#edit_answer_id').val();

        $.ajax({
            type: 'DELETE',
            url: `/answers/${answerId}/images`,
            data: {
                _token: '{{ csrf_token() }}',
                image: image
            },
            success: function(response) {
                imageElement.remove();
                alert(response.message);
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

    // Handle edit answer modal show event
    $('#editAnswerModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var answerId = button.data('answer-id');
        var answerText = button.data('answer');
        var scorePoint = button.data('score_point');
        var isCorrect = button.data('is_correct');
        var modal = $(this);
        modal.find('#edit_answer_id').val(answerId);
        modal.find('#edit_answer').val(answerText);
        modal.find('#edit_score_point').val(scorePoint);
        modal.find('#edit_is_correct').val(isCorrect);

        // Load existing images
        $.ajax({
            type: 'GET',
            url: `/answers/${answerId}/images`,
            success: function(response) {
                var imagesContainer = modal.find('#existing-images');
                imagesContainer.empty();
                response.images.forEach(function(image) {
                    imagesContainer.append(`
                        <div class="image-preview" style="display: inline-block; position: relative; margin: 5px;">
                            <img src="/storage/${image}" alt="Image" style="width: 100px; height: 100px;">
                            <button type="button" class="btn btn-danger btn-sm remove-answer-image" style="position: absolute; top: 5px; right: 5px;" data-image="${image}"  data-answer-id="${answerId}" >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                });
            }
        });
    });

    // Handle edit Answer form submission
    $('#editAnswerForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        var submitButton = form.find('button[type="submit"]');
        var spinner = submitButton.find('.spinner-border');
        var answerId = form.find('#edit_answer_id').val();

        submitButton.prop('disabled', true);
        spinner.show();

        $.ajax({
            type: 'POST',
            url: '{{ route("answers.update", ":id") }}'.replace(':id', answerId),
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                submitButton.prop('disabled', false);
                spinner.hide();
                $('#edit-answer-message').text(response.message).fadeIn().delay(3000).fadeOut();
            },
            error: function(xhr) {
                $('#edit-answer-error').text(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
                submitButton.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Handle delete question button click
    $('.delete-answer-btn').on('click', function() {
        if (!confirm('Are you sure you want to delete this answer?')) {
            return;
        }
        
        var button = $(this);
        var answerId = button.data('answer-id');
        
        $.ajax({
            type: 'DELETE',
            url: `/answers/${answerId}`,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                button.closest('li').remove();
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

});

</script>
@endsection

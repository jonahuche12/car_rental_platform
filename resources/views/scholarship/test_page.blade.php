@extends('layouts.app')

@section('title', "Central School System $test->title")
@section('page_title', "$test->title")

@section('sidebar')
    @include('sidebar')
@endsection

@section('breadcrumb2')
    <a href="#">Home</a>
@endsection

@section('breadcrumb3')
    <a href="#">{{ $test->title }}</a>
@endsection

@section('style')
<style>
    .question-image, .answer-image {
        max-width: 30%;
        height: auto;
        margin: 10px 0;
    }
    .img-thumbnail {
        border: 1px solid #ddd;
        padding: 4px;
        border-radius: 4px;
    }
    .question-card {
        display: none; /* Hide all question cards by default */
    }
    .question-card.active {
        display: block; /* Display only the active question card */
    }
    .question-form {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #ccc;
        color:#000;
    }
    .answers-container .answer {
        margin-bottom: 10px;
    }
    #question-container {
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    #loading-spinner .spinner-border {
        display: inline-block;
        margin-left: 10px;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <h2>{{ $test->title }}</h2>
    @if (isset($message))
        <div class="alert alert-info">{{ $message }}</div>
    @endif
    <div id="question-container" class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Question <span id="question-number">1</span> of {{ $test->questions->count() }}</h5>
            <h5 id="timer"></h5> <!-- Countdown timer -->
        </div>
        <div class="card-body">
            <p class="alert alert-danger" id="question-error" style="display:none;"></p>

            @foreach($test->questions as $key => $question)
            @php
                $answers = $question->answers->toArray();
                shuffle($answers);
            @endphp
            <div class="question-card {{ $key === 0 ? 'active' : '' }}" data-question-index="{{ $key }}">
                <div class="question-content">
                    <p>{{ $question->question }}</p>
                    @if($question->images)
                        @foreach($question->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Question Image" class="img-fluid img-thumbnail question-image">
                        @endforeach
                    @endif
                </div>
                <form class="question-form">
                    @csrf
                    <input type="hidden" name="question" value="{{ $question->id }}">

                    <div class="answers-container">
                        @foreach($answers as $answer)
                            <div class="answer-container mb-3">
                                <div class="form-check answer ml-3">
                                    @if($question->answer_type === 'radio')
                                        <input class="form-check-input" type="radio" name="answer" id="answer{{ $answer['id'] }}" value="{{ $answer['id'] }}" data-score="{{ $answer['score_point'] }}">
                                    @elseif($question->answer_type === 'checkbox')
                                        <input class="form-check-input" type="checkbox" name="answer[]" id="answer{{ $answer['id'] }}" value="{{ $answer['id'] }}" data-score="{{ $answer['score_point'] }}">
                                    @endif
                                    <label class="form-check-label" for="answer{{ $answer['id'] }}">
                                        {{ $answer['answer'] }}
                                    </label>
                                </div>
                                @if($answer['images'])
                                    <div class="answer-images mt-0 ml-3">
                                        @foreach($answer['images'] as $image)
                                            <img src="{{ asset('storage/' . $image) }}" alt="Answer Image" class="img-fluid img-thumbnail answer-image" width="150px">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        @if($key > 0)
                            <button type="button" class="btn btn-secondary prev-question">Previous</button>
                        @endif
                        @if($key < $test->questions->count() - 1)
                            <button type="button" class="btn btn-primary next-question">Next</button>
                        @else
                            <div id="loading-spinner" class="d-none">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-finish">Finish</button>
                        @endif
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    const questionCards = $('.question-card');
    let currentQuestionIndex = 0;
    let answers = {};
    let interval;
    let testSubmitted = false;

    // Shuffle answers
    $('.answers-container').each(function() {
        const answers = $(this).children('.answer').toArray();
        for (let i = answers.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [answers[i], answers[j]] = [answers[j], answers[i]];
        }
        $(this).append(answers);
    });

    // Test duration in minutes
    const duration = {{ $test->duration }};
    let timer;

    function startTimer(duration, display) {
        let timer = duration, minutes, seconds;
        interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.text(minutes + ":" + seconds);

            if (--timer < 0) {
                clearInterval(interval);
                submitTest(); // Automatically submit the test when time is up
            }
        }, 1000);
    }

    function stopTimer() {
        clearInterval(interval);
    }

    function updateQuestionNumber() {
        $('#question-number').text(currentQuestionIndex + 1);
    }

    function showQuestion(index) {
        questionCards.removeClass('active');
        $(questionCards[index]).addClass('active');
        updateQuestionNumber();
    }

    function collectAnswers() {
        const activeCard = $('.question-card.active');
        const form = activeCard.find('.question-form');
        const questionIndex = activeCard.data('question-index');
        const questionId = form.find('input[name="question"]').val();
        const selectedAnswers = form.find('.form-check-input:checked').map(function() {
            return {
                id: $(this).val(),
                score: parseInt($(this).data('score'), 10)
            };
        }).get();

        if (form.find('.form-check-input:checked').first().attr('type') === 'checkbox') {
            answers[questionId] = selectedAnswers.map(answer => answer.score);
        } else {
            answers[questionId] = selectedAnswers[0] ? selectedAnswers[0].score : 0;
        }
    }

    function displayErrorMessage(message) {
        $('#question-error').text(message).show();
    }

    function submitTest() {
        if (testSubmitted) return; // Prevent multiple submissions
        testSubmitted = true;
        stopTimer(); // Stop the timer
        collectAnswers();

        const finishButton = $('.btn-finish');
        const spinner = $('#loading-spinner');

        // Show spinner and hide button
        finishButton.hide();
        spinner.removeClass('d-none');

        $.ajax({
            url: '{{ route('test.submit', ['test' => $test->id]) }}',
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({ answers: answers }),
            success: function(data) {
                if (data.success) {
                    // Redirect to results page with the grade
                    window.location.href = '{{ route('test_results', ['test' => $test->id]) }}?score=' + data.grade + '&passed=' + data.passed + '&isLastTest=' + data.isLastTest + '&isCategoryPassed=' + data.isCategoryPassed;
                } else {
                    displayErrorMessage(data.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                displayErrorMessage('There was an error submitting your test.');
            },
            complete: function() {
                // Hide spinner and show button
                spinner.addClass('d-none');
                finishButton.show();
            }
        });
    }

    showQuestion(currentQuestionIndex);

    $('.prev-question').click(function() {
        collectAnswers();
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
        }
    });

    $('.next-question').click(function() {
        collectAnswers();
        if (currentQuestionIndex < questionCards.length - 1) {
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
        }
    });

    $('.btn-finish').click(function(event) {
        event.preventDefault();
        submitTest();
    });

    // Handle visibility change and beforeunload event
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            submitTest(); // Automatically submit the test if the tab is hidden
        }
    });

    window.addEventListener('beforeunload', function(event) {
        if (!testSubmitted) {
            submitTest(); // Automatically submit the test if the user navigates away or closes the browser
        }
    });

    // Start the countdown timer
    const display = $('#timer');
    startTimer(duration * 60, display); // Convert minutes to seconds
});

</script>
@endsection

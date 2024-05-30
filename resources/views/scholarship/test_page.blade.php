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
</style>
@endsection

@section('content')
<div class="container mt-5">
    <h2>{{ $test->title }}</h2>
    @if (isset($message))
        <div class="alert alert-info">{{ $message }}</div>
    @endif
    <div id="question-container" class="card mt-4">
        <div class="card-header">
            <h5>Question <span id="question-number">{{ $currentQuestionIndex + 1 }}</span> of {{ $test->questions->count() }}</h5>
        </div>
        <div class="card-body">
            <p class="alert alert-danger" id="question-error" style="display:none;"></p>

            @foreach($test->questions as $key => $question)
            <div class="question-card {{ $key === $currentQuestionIndex ? 'active' : '' }}" data-question-index="{{ $key }}">
                <p>{{ $question->question }}</p>
                @if($question->images)
                    @foreach($question->images as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="Question Image" class="img-fluid img-thumbnail question-image">
                    @endforeach
                @endif
                <form class="question-form">
                    @csrf
                    <input type="hidden" name="question" value="{{ $key }}">

                    @foreach($question->answers as $answer)
                        <div class="form-check">
                            @if($question->answer_type === 'radio')
                                <input class="form-check-input" type="radio" name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}" data-score="{{ $answer->score_point }}">
                            @elseif($question->answer_type === 'checkbox')
                                <input class="form-check-input" type="checkbox" name="answer[]" id="answer{{ $answer->id }}" value="{{ $answer->id }}" data-score="{{ $answer->score_point }}">
                            @endif
                            <label class="form-check-label" for="answer{{ $answer->id }}">
                                {{ $answer->answer }}
                            </label>
                        </div>
                        @if($answer->images)
                            @foreach($answer->images as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Answer Image" class="img-fluid img-thumbnail answer-image mt-2">
                            @endforeach
                        @endif
                    @endforeach

                    <div class="mt-4">
                        @if($key > 0)
                            <button type="button" class="btn btn-secondary prev-question">Previous</button>
                        @endif
                        @if($key < $test->questions->count() - 1)
                            <button type="button" class="btn btn-primary next-question">Next</button>
                        @else
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
    document.addEventListener('DOMContentLoaded', function() {
        const questionCards = document.querySelectorAll('.question-card');
        let currentQuestionIndex = {{ $currentQuestionIndex }};
        let answers = {};

        function updateQuestionNumber() {
            document.getElementById('question-number').textContent = currentQuestionIndex + 1;
        }

        function showQuestion(index) {
            questionCards.forEach((card, idx) => {
                card.classList.toggle('active', idx === index);
            });
            updateQuestionNumber();
        }

        function collectAnswers() {
            const activeCard = document.querySelector('.question-card.active');
            const form = activeCard.querySelector('.question-form');
            const questionIndex = activeCard.dataset.questionIndex;
            const selectedAnswers = Array.from(form.querySelectorAll('.form-check-input:checked')).map(input => ({
                id: input.value,
                score: parseInt(input.dataset.score, 10)
            }));
            answers[questionIndex] = selectedAnswers;
        // console.log(answers)
        }

        showQuestion(currentQuestionIndex);

        document.querySelectorAll('.prev-question').forEach(button => {
            button.addEventListener('click', function() {
                collectAnswers();
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    showQuestion(currentQuestionIndex);
                }
            });
        });

        document.querySelectorAll('.next-question').forEach(button => {
            button.addEventListener('click', function() {
                collectAnswers();
                if (currentQuestionIndex < questionCards.length - 1) {
                    currentQuestionIndex++;
                    showQuestion(currentQuestionIndex);
                }
            });
        });

        document.querySelector('.btn-finish').addEventListener('click', function(event) {
            collectAnswers();
            event.preventDefault();

            // Submit the answers via AJAX
            fetch('{{ route('test.submit', ['test' => $test->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ answers })
            }).then(response => response.json())
              .then(data => {
                  // Handle response
                  if (data.success) {
                      window.location.href = '{{ route("home") }}';
                  } else {
                      // Handle error
                      alert('There was an error submitting your test.');
                  }
              });
        });
    });
</script>
@endsection

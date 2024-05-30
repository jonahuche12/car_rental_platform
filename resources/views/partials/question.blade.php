<div class="card-header">
    <h5>Question {{ $currentQuestionIndex + 1 }} of {{ $test->questions->count() }}</h5>
</div>
<div class="card-body">
    <p class="alert alert-danger" id="question-error" style="display:none;"></p>
    <p>{{ $currentQuestion->question }}</p>
    @if($currentQuestion->images)
        @foreach($currentQuestion->images as $image)
            <img src="{{ asset('storage/' . $image) }}" alt="Question Image" class="img-fluid img-thumbnail question-image">
        @endforeach
    @endif
    <form>
        <input type="hidden" name="question" value="{{ $currentQuestionIndex }}">

        @foreach($answers as $answer)
            <div class="form-check">
                @if($currentQuestion->answer_type === 'radio')
                    <input class="form-check-input" type="radio" name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}" data-score="{{ $answer->score_point }}">
                @elseif($currentQuestion->answer_type === 'checkbox')
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
            @if($currentQuestionIndex > 0)
                <button type="submit" name="question" value="{{ $currentQuestionIndex - 1 }}" class="btn btn-secondary">Previous</button>
            @endif
            @if($currentQuestionIndex < $test->questions->count() - 1)
                <button type="submit" name="question" value="{{ $currentQuestionIndex + 1 }}" class="btn btn-primary">Next</button>
            @else
                <button type="submit" name="question" value="{{ $currentQuestionIndex + 1 }}" class="btn btn-success btn-finish">Finish</button>
            @endif
        </div>
    </form>
</div>

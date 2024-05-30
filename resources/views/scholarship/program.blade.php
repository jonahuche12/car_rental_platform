@extends('layouts.app')
@section('title', "Central School System - Scholarship Program for $class_level")

@section('sidebar')
    @include('sidebar')
@endsection

@section('breadcrumb2')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('style')
<style>
  .alert-danger {
    position: relative;
}

.alert-danger .close-error {
    position: absolute;
    top: 0;
    right: 10px;
    font-size: 1.2em;
    line-height: 1;
    cursor: pointer;
    border: none;
    background: none;
}

.alert-danger .close-error:hover {
    color: red;
}

</style>
@endsection

@section('breadcrumb3', 'Scholarship Program')

@section('page_title')
    <h4><b>Scholarship Program for {{ ucfirst($class_level) }}</b></h4>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm p-4 mb-4 bg-white rounded">
            <h4 class="text-primary font-weight-bold">Aim</h4>
            <p>
                The aim of this scholarship is to identify and support exceptional students by facilitating their education through financial assistance and academic support. This scholarship program is dedicated to nurturing talent and providing opportunities for students to excel in their studies and future careers. Additionally, the program is intended to prepare students and ensure they are ready for their final external exams, providing the necessary resources and guidance to achieve academic success.
            </p>

            <h4 class="text-primary font-weight-bold mt-4">Selection Process</h4>
            <p>
                To ensure we select the most deserving candidates, the scholarship program includes a rigorous three-tier selection process:
            </p>
            <ul>
                <li>
                    <strong>Cognitive Test:</strong> This initial test assesses the student's cognitive abilities, including critical thinking, problem-solving skills, and intellectual potential. This helps us identify students with outstanding mental acuity and learning capabilities.
                </li>
                <li>
                    <strong>Class Level Test:</strong> The second phase involves a comprehensive examination based on the student's current class level. This test evaluates the student's academic knowledge and understanding of their current curriculum, ensuring they possess the necessary academic proficiency.
                </li>
                <li>
                    <strong>Face-to-Face Interview:</strong> The final phase is a personal interview conducted by our agents. This interview provides an opportunity for students to showcase their personality, motivation, and aspirations. It allows us to understand their individual needs and how the scholarship can best support their educational journey.
                </li>
            </ul>

            <h4 class="text-primary font-weight-bold mt-4">Support Provided</h4>
            <p>
                Successful candidates who pass through these stages will demonstrate their exceptional abilities and readiness to benefit from the scholarship. The program will not only prepare students for their final external exams but also fund and facilitate tuition for students who have performed exceptionally well. This comprehensive support aims to remove financial barriers and provide a conducive learning environment, enabling students to focus on their academic and personal growth.
            </p>

            <h4 class="text-primary font-weight-bold mt-4">Available Scholarships</h4>
            @if ($scholarships->isEmpty())
                <p>No scholarships available for this class level.</p>
            @else
                <div class="list-group">
                    @foreach ($scholarships as $scholarship)
                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-2">
                            <h5 class="mb-1 text-white font-weight-bold">{{ $scholarship->title }}</h5>
                            <p class="mb-1 text-white ml-3">{{ $scholarship->description }}</p>

                            <div class="ml-3">
                                <h5 class="text-primary font-weight-bold mt-4">Categories</h5>
                                @if ($scholarship->categories->isEmpty())
                                    <p>No categories found for this scholarship.</p>
                                @else
                                    <div class="accordion" id="scholarshipCategories{{ $scholarship->id }}">
                                        @foreach($scholarship->categories as $category)
                                            <div class="card mb-2">
                                                <div class="card-header" id="heading{{ $category->id }}">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapse{{ $category->id }}" class="collapse" aria-labelledby="heading{{ $category->id }}" data-parent="#scholarshipCategories{{ $scholarship->id }}">
                                                    <div class="card-body">
                                                        <p><strong>Description:</strong> {{ $category->description }}</p>
                                                        <p>
                                                            <strong>Required Viewed Lessons:</strong> {{ $category->required_viewed_lessons }}
                                                            <br>
                                                            <span class="small-text text-info">This Category requires the student to have taken at least {{ $category->required_viewed_lessons }} Lessons in {{ $class_level }}</span>
                                                        </p>
                                                        <p>
                                                            <strong>Reward Amount:</strong> &#8358;{{ number_format($category->reward_amount, 2) }}
                                                        </p>
                                                        <p><strong>Required Connects:</strong> {{ $category->required_connects }}</p>
                                                        <div class="alert alert-success" id="enroll-success-{{ $category->id }}" style="display:none"></div>
                                                        <div class="alert alert-danger small-text" id="enroll-error-{{ $category->id }}" style="display:none; cursor:pointer;">
                                                            <button type="button" class="btn btn-danger close-error mt-2" style="float:right">
                                                                <span aria-hidden="true" class="text-white">&times;</span>
                                                            </button>
                                                            <span id="enroll-error-message-{{ $category->id }}"></span>
                                                        </div>

                                                        @if (auth()->user()->isEnrolledInCategory($category->id))
                                                            <a href="{{ route('scholarship_categories.show_page', ['category' => $category->id]) }}" class="btn btn-primary btn-sm">Go to Category</a>
                                                        @else
                                                            <button class="btn btn-primary btn-sm enroll-btn" data-category-id="{{ $category->id }}">Enroll in Scholarship</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
   $(document).ready(function() {
    $('.collapse').collapse('hide');

    $('.enroll-btn').on('click', function(e) {
        e.preventDefault();
        let $button = $(this);
        let categoryId = $button.data('category-id');
        let $spinner = $('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        // Disable the button and show the spinner
        $button.prop('disabled', true).html($spinner);

        $.ajax({
            url: "{{ route('enroll.scholarship', '') }}/" + categoryId,
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                // Re-enable the button and restore its original text
                $button.prop('disabled', false).text('Enroll in Scholarship');

                if (response.success) {
                    $('#enroll-success-' + categoryId).text(response.success).fadeIn().delay(3000).fadeOut();
                    $('#enroll-error-' + categoryId).hide(); // Hide any previous error messages
                }
            },
            error: function(xhr) {
                // Re-enable the button and restore its original text
                $button.prop('disabled', false).text('Enroll in Scholarship');

                let error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred. Please try again.';
                $('#enroll-error-message-' + categoryId).text(error);
                $('#enroll-error-' + categoryId).show(); // Use show() instead of fadeIn() to ensure visibility
            }
        });
    });

    // Allow the user to manually dismiss the error message
    $('.close-error').on('click', function() {
        $(this).closest('.alert-danger').hide();
    });
});
</script>
@endsection

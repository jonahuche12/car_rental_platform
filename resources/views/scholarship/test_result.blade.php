@extends('layouts.app')

@section('title', "CSS - Test Results - $test->title")
@section('page_title', "Test Results - $test->title")

@section('sidebar')
@include('sidebar')
@endsection

@section('breadcrumb2')
    <a href="{{ route('home') }}"> Home</a>

@endsection

@section('breadcrumb3')
   <a href="#" class="btn-clear link-clear">Test Results</a>

@endsection

@section('content')
<div class="container mt-5">
    <h2>Test Results</h2>
    <div class="alert alert-success">
        Your total score is: {{ $score }}
    </div>

    @if ($passed)
        @if ($isLastTest)
            <div class="alert alert-success">
                <!-- <p>{{$isLastTest}}</p> -->
                Congratulations! You have completed all tests in this category.
                @if ($isCategoryPassed)
                    You have passed the scholarship category. Please wait for Central School System to send your scholarship status and reward via email.
                @else
                    Unfortunately, you did not pass the scholarship category. Keep trying!
                @endif
            </div>
        @else
            <a href="{{ route('start_test', $test->scholarshipCategories->first()->id) }}" class="btn btn-primary">Continue to Next Test</a>
        @endif
    @else
        <div class="alert alert-danger">
            You did not pass this test. Please try again.
        </div>
    @endif
    <a href="{{ route('home') }}" class="btn btn-primary">Continue to Home</a>
</div>
@endsection

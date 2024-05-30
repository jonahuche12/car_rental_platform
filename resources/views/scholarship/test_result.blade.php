@extends('layouts.app')

@section('title', "Test Results - $test->title")
@section('page_title', "Test Results - $test->title")

@section('content')
<div class="container mt-5">
    <h2>Test Results</h2>
    <div class="alert alert-success">
        Your total score is: {{ $score }}
    </div>
</div>
@endsection

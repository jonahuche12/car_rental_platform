@extends('layouts.app')

@section('title')
@if(isset($school))
CSS - {{$school->name}} - User Packages
@else
Central School System - User Packages
@endif
@endsection

@section('breadcrumb3')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb2')
@if(auth()->user()->profile)
<span>{{auth()->user()->profile->role}}</span>
@endif
@endsection

@section('breadcrumb1')
@if(auth()->user()->profile)
<p>{{auth()->user()->profile->full_name}}</p>
@endif
@endsection

@section('sidebar')
@include('sidebar')
@endsection

@section('page_title')
User Packages
@endsection

@section('style')
<style>
.package-card {
    border: 1px solid #000;
    border-radius: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
    background-color: #333; /* Dark background */
    color: #fff; /* White text color */
}

.package-card:hover {
    transform: scale(1.05);
}

.package-card img {
    max-height: 300px; /* Adjusted image height */
    object-fit: cover;
}

.package-card .card-body {
    padding: 15px;
}

.package-card .card-title {
    font-size: 1.25rem;
    margin-bottom: 10px;
    color: #fff; /* White text color */
    font-weight: bold;
}

.package-card .card-text {
    font-size: 0.9rem;
    color: #fff; /* White text color */
}

.package-card .btn {
    margin-top: 10px;
    width: 100%;
    font-size: 0.9rem;
}

.show-more, .show-less {
    cursor: pointer;
    color: #007bff;
    text-decoration: underline;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        @foreach ($userPackages as $package)
            <div class="col-md-4">
                <div class="card mb-4 package-card">
                    <img src="{{ asset('storage/' . $package->picture) }}" class="card-img-top" alt="{{ $package->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $package->name }}</h5>
                        <p class="card-text">
                            @if (strlen($package->description) > 15)
                                <span class="short-description">{{ substr($package->description, 0, 15) . '...' }}</span>
                                <span class="full-description" style="display:none;">{{ $package->description }}</span>
                                <span class="show-more" onclick="toggleDescription(this)">Show more</span>
                                <span class="show-less" style="display:none;" onclick="toggleDescription(this)">Show less</span>
                            @else
                                {{ $package->description }}
                            @endif
                        </p>
                        <p><strong>Price:</strong> ₦{{ number_format($package->price, 2) }}</p>
                        <p><strong>Duration:</strong> {{ $package->duration_in_days }} days</p>
                        <p><strong>Max Lessons Per Day:</strong>
                            @if (strpos(strtolower($package->name), 'premium') !== false)
                                Unlimited
                            @else
                                {{ $package->max_lessons_per_day == 'unlimited' ? 'Unlimited' : $package->max_lessons_per_day }}
                            @endif
                        </p>
                        @if (auth()->user()->profile->role !== 'student')
                            <p><strong>Max Uploads:</strong>
                                @if (strpos(strtolower($package->name), 'premium') !== false)
                                    Unlimited
                                @else
                                    {{ $package->max_uploads == 'unlimited' ? 'Unlimited' : $package->max_uploads }}
                                @endif
                            </p>
                        @endif
                        @if (auth()->user()->active_package && auth()->user()->userPackage->id == $package->id)
                            <button class="btn bg-success btn-sm" disabled>Activated</button>
                        @else
                            <a href="{{ route('payment.activate', ['package_id' => $package->id]) }}" class="btn bg-primary btn-sm">Buy</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
@section('scripts')

<script>
function toggleDescription(element) {
    var cardBody = element.closest('.card-body');
    var shortDescription = cardBody.querySelector('.short-description');
    var fullDescription = cardBody.querySelector('.full-description');
    var showMore = cardBody.querySelector('.show-more');
    var showLess = cardBody.querySelector('.show-less');
    
    if (shortDescription.style.display === 'none') {
        shortDescription.style.display = 'inline';
        fullDescription.style.display = 'none';
        showMore.style.display = 'inline';
        showLess.style.display = 'none';
    } else {
        shortDescription.style.display = 'none';
        fullDescription.style.display = 'inline';
        showMore.style.display = 'none';
        showLess.style.display = 'inline';
    }
}
</script>
@endsection

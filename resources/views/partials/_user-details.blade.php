<div class="user-details-section container mt-4">
    <div class="user-header card mb-4">
        <div class="card-body text-center">

        @if ($user->profile->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="{{ $user->profile->full_name }}" class="user-logo rounded-circle mx-auto mb-3">
                    @else
                    <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image"  class="user-logo rounded-circle mx-auto mb-3">
                    @endif
                    
            <h5 class="mb-1">{{ $user->profile->full_name }}</h5>
            <p class="card-text text-muted">{{ $user->email }}</p>
            <p class="card-text text-muted">{{ $user->profile->role }}</p>

            <!-- Display user ranking -->
            @php
                $ranking = $user->rankUser();
                $fullStars = floor($ranking);
                $halfStar = ceil($ranking) - $fullStars;
                $emptyStars = 5 - $fullStars - $halfStar;

                // Determine color class based on ranking
                if ($ranking < 2) {
                    $colorClass = 'text-danger';
                } elseif ($ranking >= 2 && $ranking < 4) {
                    $colorClass = 'text-primary';
                } else {
                    $colorClass = 'text-success';
                }
            @endphp

            <div class="ranking-stars">
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star {{ $colorClass }}"></i>
                @endfor

                @if ($halfStar)
                    <i class="fas fa-star-half-alt {{ $colorClass }}"></i>
                @endif

                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star {{ $colorClass }}"></i>
                @endfor
            </div>
            <p class="ranking-text">Ranking: <b>{{ $ranking }}</b></p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Bio</h5>
                    <p class="card-text">{{ $user->profile->bio ?? 'No bio available' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="text-primary">Contact Information</h5>
                    <div class="user-contact">
                        <div class="contact-item">
                            <h6><i class="fas fa-envelope"></i> Email</h6>
                            <p class="card-text text-info">{{ $user->email }}</p>
                        </div>
                        <!-- Add more contact information as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Display school and class details if applicable -->
    @if ($user->profile->role === 'student')
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">School</h5>
                        <p class="card-text">{{ $user->school->name ?? 'Not assigned to a school' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Class</h5>
                        <p class="card-text">{{ $user->schoolClass()->name ?? 'Not assigned to a class' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($user->profile->role !== 'student' && $user->profile->role !== 'guardian')
        <!-- Display total lessons posted by the user -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="text-primary"><a class="nav-link" id="totalLessonsLink" href="#lessons" data-toggle="tab">Total Lessons</a></h5>
                <p class="card-text">{{ $user->lessons()->count() }}</p>
            </div>
        </div>
    @endif
</div>

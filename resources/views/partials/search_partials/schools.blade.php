@if ($results->isNotEmpty())
    <h5>Schools</h5>
    <div class="row users-list">
        @foreach ($results as $school)
            <div class="col-md-4 col-12 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        @if ($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="user-logo rounded-circle mx-auto mb-3" style="width: 100px; height: 100px;">
                        @else
                            <i class="fas fa-school user-logo text-muted rounded-circle mx-auto mb-3" style="font-size: 63px;"></i>
                        @endif
                        <h5 class=""><a class="users-list-name" href="{{ route('schools.show', $school) }}">{{ $school->name }}</a></h5>
                        <p>Ranking : <b>{{$school->getRanking()}}</b></p>
                        <p class="card-text text-muted">{{ $school->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p>No schools found for the search term <b>{{ $term }}</b>.</p>
@endif

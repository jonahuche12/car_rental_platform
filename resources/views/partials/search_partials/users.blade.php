@if ($results->isNotEmpty())
    <h5>People</h5>
    <ul class="users-list people-list clearfix">
        @foreach ($results as $user)
        @if($user->profile->role != 'super_admin')
            <li class="col-md-4 col-">
                <!-- Display user card -->
                <div class="card people-card" data-people-id="{{ $user->id }}" data-admin-name="{{ $user->profile->full_name }}">
                    <!-- User card details -->
                    <div class="card-body">
                        <div class="user-profile">
                            <p class=""><b><a class="users-list-name" href="{{route('user_page',['userId'=> $user->id, 'fullname'=> $user->profile->full_name])}}"> {{$user->profile->full_name}}</a></b></p>
                            @if ($user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="User Image" width="150px">
                            @else
                                <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image" width="150px">
                            @endif
                            <a class="users-list-name" href="#" data-admin-name="{{ $user->profile->full_name }}">
                                <!-- <br> -->
                                <span class="badge p-1 badge-info">{{ $user->profile->role }}</span>
                            </a>
                        </div>
                        <!-- User details -->
                        <div class="user-permissions">
                            <h5 style="cursor:pointer;" class="deti-heading btn btn-info toggle-details-btn" data-target="student-details-{{ $user->id }}">
                                Details <i class="toggle-icon fas fa-chevron-down"></i>
                            </h5>
                            <div class="collapsed-details" id="student-details-{{ $user->id }}">
                            <h5 style="cursor:pointer;" class="deti-heading toggle-details-btn btn btn-info" data-target="student-details-{{ $user->id }}">
                                Details <i class="toggle-icon fas fa-chevron-down"></i>
                            </h5>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Phone:</strong> {{ $user->profile->phone_number ?? 'N/A' }}</p>
                                <p><strong>Gender:</strong> {{ $user->profile->gender }}</p>
                                <p><strong>Date of Birth:</strong> {{ $user->profile->date_of_birth ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @endif
        @endforeach
    </ul>
@else
    <p>No people found for the search term <b>{{ $term }}</b>.</p>
@endif

  <!-- Inline form for searching students/wards -->
  <form id="searchForm" class="form-inline">
    <div class="form-group">
        <input type="text" class="form-control" id="searchQuery" placeholder="Search Students/Wards">
    </div>
</form>

<!-- Display the list of wards or message -->
<div class="active tab-pane" id="wards">
    <div id="wardsList">
        <!-- Content will be loaded dynamically via AJAX -->
    </div>
</div>

<!-- Confirm Ward Addition Modal -->
<div class="modal" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Ward Addition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="alert alert-danger" id="add-ward-error" style="display:none;"></div>
            <div class="alert alert-info" id="add-ward-message" style="display:none;"></div>
            <div class="modal-body">
                <p id="wardInfo" data-student-id=""></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAddWard" data-ward-id="">
                    <span id="addWardBtnText">Add as Ward</span> <!-- Text of the button -->
                    <span id="addWardBtnSpinner" style="display: none;"> <!-- Loading spinner -->
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirmRemoveModal" tabindex="-1" role="dialog" aria-labelledby="confirmRemoveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmRemoveModalLabel">Confirm Removal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger" id="ward-error" style="display:none;"></div>
            <div class="alert alert-info" id="ward-message" style="display:none;"></div>
            <div class="modal-body">
                <p id="wardDetails"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemoveButton">
                <span id="removeWardBtnText"> Remove Ward</spin>
                <span id="removeWardBtnSpinner" style="display: none;"> <!-- Loading spinner -->
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>


                    <!-- Check if wards array is not empty -->
                    @if(auth()->user()->wards->isNotEmpty())
    <!-- Display the list of wards -->
    <ul class="users-list clearfix">
        <p class="p-1 mt-2"><b>Your Wards</b></p>
        @foreach (auth()->user()->wards as $ward)
            <li class="col-lg-4 col-">
                <!-- Display ward card -->
                <div class="card people-card" data-people-id="{{ $ward->id }}" data-admin-name="{{ $ward->profile->full_name }}">
                    <!-- Ward card details -->
                    <div class="card-body">
                        <div class="user-profile">
                            <p class="badge bg-purple"> {{ $ward->profile->full_name }}</p><br>
                            @if ($ward->profile->profile_picture)
                                <img src="{{ asset('storage/' . $ward->profile->profile_picture) }}" alt="User Image" width="150px">
                            @else
                                <img src="{{ asset('dist/img/avatar5.png') }}" alt="User Image" width="150px">
                            @endif
                            <a class="users-list-name" href="#" data-admin-name="{{ $ward->profile->full_name }}">
                                <!-- <br> -->
                                <span class="badge p-1 badge-info">{{ $ward->profile->role }}</span>
                            </a>
                        </div>
                        <!-- Ward details -->
                        <div class="user-permissions">
                            <h5 style="cursor:pointer;" class="deti-heading btn btn-info toggle-details-btn" data-target="ward-details-{{ $ward->id }}">
                                Details <i class="toggle-icon fas fa-chevron-down"></i>
                            </h5>
                            <div class="collapsed-details text-light" id="ward-details-{{ $ward->id }}" style="background-color: rgba(0, 0, 0, 0.7);">
                                <h5 style="cursor:pointer;" class="deti-heading toggle-details-btn btn btn-info" data-target="ward-details-{{ $ward->id }}">
                                    Details <i class="toggle-icon fas fa-chevron-down"></i>
                                </h5>
                                <p class="small-text"><br><strong>Email:</strong> {{ $ward->email }}</p>
                                <p  class="small-text"><strong>Phone:</strong> {{ $ward->profile->phone_number ?? 'N/A' }}</p>
                                <p  class="small-text"><strong>Gender:</strong> {{ $ward->profile->gender }}</p>
                                <p class="small-text"><strong>Date of Birth:</strong> {{ $ward->profile->date_of_birth ?? 'N/A' }}</p>
                                <!-- Add remove ward button if the ward is not confirmed -->
                                @if (!$ward->pivot->confirmed)
                                    <button class="btn btn-danger" onclick="confirmRemoveWard('{{ $ward->id }}', '{{ $ward->profile->full_name }}', '{{ $ward->schoolClass()->name }}')">Remove Ward</button>
                                @endif

                                @if ($ward->pivot->confirmed)
                                    <a href="{{ route('student.progress', ['student_id' => $ward->id]) }}" class="btn btn-info small-text">Academic Progress</a>
                                @endif




                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <!-- If no wards exist, display a message -->
    <p class="p-1 mt-2"><b>No wards yet.</b></p>
@endif
<!-- resources/views/home.blade.php -->

@extends('layouts.app')

@section('sidebar')
    @include('sidebar')
@endsection

@section('content')
<?php
$homepage = "users_homepage/homepage";
$userRole = auth()->user()->profile->role ?? null;

if ($userRole == "school_owner") {
    $homepage = "users_homepage/school_owner_homepage";
}elseif ($userRole == "student") {
    $homepage = "users_homepage/student_homepage";
}elseif ($userRole == "teacher") {
    $homepage = "users_homepage/teacher_homepage";
}elseif ($userRole == "staff") {
    $homepage = "users_homepage/staff_homepage";
}elseif ($userRole == "guardian") {
    $homepage = "users_homepage/guardian_homepage";
}elseif ($userRole == "super_admin") {
    $homepage = "users_homepage/super_admin_homepage";
}elseif ($userRole == "admin") {
    $homepage = "users_homepage/admin_homepage";
}

?>

@include($homepage)

<div class="modal fade" id="roleSelectionModal" tabindex="-1" aria-labelledby="roleSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Select Your Role</h5>
                <button type="button" onclick="hideModal()" class="btn-close btn-danger btn btn-sm" aria-label="Close"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.create') }}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">What Best Describes Your Position?</label>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="student" value="student">
                            <label class="form-check-label" for="student">Student</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="teacher" value="teacher">
                            <label class="form-check-label" for="teacher">Teacher</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="staff" value="staff">
                            <label class="form-check-label" for="staff">Staff</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="admin" value="admin">
                            <label class="form-check-label" for="admin">Admin</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="guardian" value="guardian">
                            <label class="form-check-label" for="guardian">Guardian</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="role" id="school_owner" value="school_owner">
                            <label class="form-check-label" for="school_owner">School Owner</label><br>
                            <span class="text-danger"><small><em>select this if you own a school</em></small></span>
                        </div>

                        <!-- Add other role options -->
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary btn-block">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // AJAX request to check if the user has a profile
        axios.get('/check-profile')
            .then(response => {
                if (!response.data.hasProfile) {
                    console.log(response.data.hasProfile);
                    // If the user has no profile, show the modal
                    $('#roleSelectionModal').modal('show');
                }
            })
            .catch(error => {
                console.error('Error checking profile:', error);
            });

        // Function to hide the modal
        window.hideModal = function () {
            $('#roleSelectionModal').modal('hide');
        };
    });
</script>
@endsection

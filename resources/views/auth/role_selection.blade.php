<!-- resources/views/auth/role_selection.blade.php -->

<div id="roleSelectionModal" class="modal">
    <div class="modal-content">
        <h4>Select Your Role</h4>
        <form action="{{ route('profile.create') }}" method="post">
            @csrf
            <label>
                <input type="radio" name="role" class="form-control" value="student"> Student
            </label>
            <label>
                <input type="radio" name="role"  class="form-control" value="teacher"> Teacher
            </label>
            <label>
                <input type="radio" name="role" class="form-control" value="guardian"> Guardian
            </label>
            <label>
                <input type="radio" name="role" class="form-control" value="staff"> Staff
            </label>
            <label>
                <input type="radio" name="role" class="form-control" value="school_owner"> School Owner
            </label>
            <button btn btn-primary btn-block type="submit">Continue</button>
        </form>
    </div>
</div>

@extends('layouts.app')

@section('title', 'CSS - Update Account Details')

@section('breadcrumb2')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3')
    {{ $category->name }}
@endsection

@section('sidebar')
@include('sidebar')
@endsection

@section('content')
<div class="container">
    <h3>Update Account Details for {{ $category->name }}</h3>

    <div class="alert alert-success" id="account-success" style="display:none"></div>
    <div class="alert alert-danger" id="account-error" style="display:none"></div>

    <form id="account-update-form">
        @csrf

        <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
        </div>

        <div class="form-group">
            <label for="account_number">Account Number</label>
            <input type="text" class="form-control" id="account_number" name="account_number" required>
        </div>

        <div class="form-group">
            <label for="account_name">Account Name</label>
            <input type="text" class="form-control" id="account_name" name="account_name" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Account</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#account-update-form').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('account.update.submit', ['category_id' => $category->id]) }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    $('#account-success').text(response.success).fadeIn().delay(3000).fadeOut();
                    $('#account-update-form')[0].reset();
                },
                error: function(xhr) {
                    $('#account-error').text(xhr.responseJSON.message || 'An error occurred while updating the account details.').fadeIn().delay(3000).fadeOut();
                }
            });
        });
    });
</script>
@endsection

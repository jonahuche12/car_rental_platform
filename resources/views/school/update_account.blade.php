@extends('layouts.app')

@section('style')
<style>
    .update-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .content {
        
        border: 1px solid #000;
        border-radius: 5px;
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        /* border: 1px solid #ccc; */
        border-radius: 5px;
        /* transition: border-color 0.3s ease; */
    }

    input[type="text"]:focus {
        outline: none;
        border-color: #007bff;
    }

</style>
@endsection

@section('sidebar')
@include('sidebar')
@endsection

@section('title')
Central School System - Update Account Details
@endsection

@section('page_title')
Update Account Number
@endsection

@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection

@section('breadcrumb3')
Update Account Details
@endsection

@section('content')
<div class="update-container">
    <div class="content">
        <p>Please update your account information:</p>
                <p class="alert alert-success" id="update-success" style="display:none"></p>
                <p class="alert alert-danger" id="update-error" style="display:none"></p>
        <form id="updateAccountForm" action="{{ route('withdrawal.saveAccount', ['token' => $withdrawalRequest->token]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="account_name">Account Name</label>
                <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Enter account name" required>
            </div>
            <div class="form-group">
                <label for="account_number">Account Number</label>
                <input type="text" class="form-control" id="account_number" name="account_number" placeholder="Enter account number" required>
            </div>
            <div class="form-group">
                <label for="bank_name">Bank Name</label>
                <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter bank name" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#updateAccountForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Serialize form data

            // AJAX request
            $.ajax({
                url: $(this).attr('action'), // Form action URL
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Handle success response
                    $('#update-success').text(response.message).fadeIn().delay(3000).fadeOut(function() {
                        // Redirect after 3 seconds
                        setTimeout(function() {
                            window.location.href = "{{ route('schools.show', ['id' => $school->id]) }}";
                        }, 3000);
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    var errorMessage = xhr.responseJSON.message;
                    $('#update-error').text(errorMessage).fadeIn().delay(3000).fadeOut();
                    // Additional actions, if any
                }
            });
        });
    });
</script>

@endsection

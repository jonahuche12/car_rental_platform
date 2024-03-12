<!-- resources/views/merchant/verify_otp.blade.php -->
@extends('layouts.app')
@section('title', 'Central School System ')

@section('content')
@include('sidebar')
<div class="row mx-auto">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin_confirm_transfer') }}">
            @csrf

            <div class="form-group">
                <label for="otp">Enter Amount Recieved</label>
                <input type="number" id="amount" name="amount" class="form-control" required>
                <input type="hidden" id="payment_session" name="payment_session_id" value="{{$transfer->payment_session_id}}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Verify Transfer</button>
        </form>
    </div>
</div>
@endsection

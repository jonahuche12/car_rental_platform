@extends('layouts.app')

@section('title', 'CSS - Buy School Connects')

@section('style')
<style>
    .complete_profile {
        display: none;
    }

    .profile_pic_style {
        cursor: pointer;
        position: absolute;
        bottom: -10px;
        right: 100px;
    }

    .qualification-container {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('page_title', 'Buy Connects')

@section('breadcrumb2')
<a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3', 'Buy Connects')

@section('content')
@include('sidebar')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-purple">
                    <h5>Buy School Connects</h5>
                </div>
                <div class="card-body">
                    @php
                    $user = auth()->user();
                    $school = $user->school;
                    @endphp

                    <p class="text-sm">Click the button below to proceed with the payment:</p>
                    <!-- <form id="paymentForm" method="POST" action="#">
                        @csrf
                        <div class="form-group">
                            <label for="connects_count">Select Number of Connects:</label>
                            <select class="form-control" id="connects_count" name="connects_count" required>
                                <option value="500" selected>90 Connects - ₦500</option>
                                <option value="1000">210 Connects - ₦1000</option>
                                <option value="2000">450 Connects - ₦2000</option>
                                <option value="3000">1000 Connects - ₦3000</option>
                                <option value="5000">2000 Connects - ₦5000</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <small>Pay with Paystack</small>
                        </button>
                    </form>
                    <br> -->

                    <!-- Button to Toggle Bank Transfer Section -->
                    <button id="toggleBankTransfer" class="btn bg-purple btn-sm mb-3">
                        {{ __('Pay Via Transfer') }}
                    </button>

                    <!-- Bank Transfer Information (Initially Hidden) -->
                    <div id="bankTransferSection" style="display: none;" class="alert alert-success">
                    
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6 class="text-info">Bank Transfer Information</h6>
                            <p class="text-sm"><b>Bank Name:</b> MoniePoint microfinance bank</p>
                            <p class="text-sm"><b>Account Name:</b> Central School System</p>
                            <p class="text-sm"><b>Account Number:</b> 6350716210</p>
                            <p class="text-sm"><b>Amount:</b> <span id="amountDisplay">₦ {{ $amount }}</span></p>

                        </div>

                        <!-- Button to Confirm Bank Transfer -->
                        <form id="confirmBankTransferForm" method="POST" action="{{ route('confirm_transfer') }}">
                            @csrf
                            <input type="hidden" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                            <input type="text" name="id_paid_for" id="id_paid_for" class="form-control" value="{{ $id }}" required>
                            <input type="hidden" name="paid_for" id="paid_for" class="form-control" value="school_connects" required>
                            <input type="hidden" name="amount_input" id="amount" class="form-control" value="{{$amount}}" required>
                       
                        
                        </form>

                            <button type="submit" id="confirmTransfer" class="btn btn-primary">
                                {{ __('I Have Made the Transfer') }} <i class="fas fa-check-circle"></i>
                            </button>
                        </form>

                        <div id="countdownTimer" style="display:none">
                            <p><b>Time Remaining: <span id="timer"></span></b></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection


@section('scripts')

<script>
    // Toggle the display of the bank transfer section
    document.getElementById('toggleBankTransfer').addEventListener('click', function () {
        const bankTransferSection = document.getElementById('bankTransferSection');
        bankTransferSection.style.display = bankTransferSection.style.display === 'none' ? 'block' : 'none';
    });
</script>

<!-- JavaScript to Handle AJAX and Countdown Timer -->
<script>
     $(document).ready(function() {
        // Update amount display based on selected connects count
        $('#connects_count').on('change', function() {
            var selectedAmount = $(this).val();
            var displayText = '₦' + selectedAmount;
            $('#amountDisplay').text(displayText);
            $('#amount').val(selectedAmount); // Update hidden input field value
        });

        // Toggle bank transfer section visibility
        $('#toggleBankTransfer').click(function() {
            $('#bankTransferSection').toggle();
        });
    });
  
// When "Pay via Transfer" button is clicked
$('#toggleBankTransfer').click(function() {
    // Serialize the form data
    const formData = $('#confirmBankTransferForm').serialize();

    // Get the CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Make an AJAX request to set the session
    $.ajax({
        type: 'POST',
        url: '{{ route("set_transfer_session") }}',
        data: formData, // Send the form data
        headers: {
            'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
        },
        success: function(response) {
            if (response.success) {
                // const paymentSessionId = response.payment_session.payment_session_id;
                console.log(response)
                location.reload()

               
            } else {
                // Handle the case where the session could not be set
                alert('Failed to set session.');
            }
        },
        error: function(status, xhr, error) {
            // Handle AJAX error
            console.log(xhr.responseText)
            // alert('An error occurred during the AJAX request.');
        }
    });
});

var paymentSession = {!! json_encode(session('payment_session')) !!};
if(paymentSession ){
// var paymentSessionId = paymentSession.payment_session_id
     // Session set successfully, show countdown timer

     $('#toggleBankTransfer').prop('disabled', true); // Disable the button
     const bankTransferSection = document.getElementById('bankTransferSection');
        bankTransferSection.style.display = 'block';
                $('#countdownTimer').show();
                startCountdown(1800); // 60 seconds = 1 minute
}

// Countdown Timer Function
function startCountdown(seconds) {
    let timer = seconds;
    const timerElement = document.getElementById('timer');

    const countdown = setInterval(function() {
        const minutes = Math.floor(timer / 60);
        const secondsLeft = timer % 60;

        timerElement.textContent = `${minutes}:${secondsLeft < 10 ? '0' : ''}${secondsLeft}`;

        if (--timer < 0) {
            clearInterval(countdown);
            $('#payViaTransfer').prop('disabled', false); // Enable the button
            $('#countdownTimer').hide(); // Hide the timer

            // Reload the page
            // location.reload();
        }
    }, 1000); // Update every second
}

setInterval(function () {
    // Get the current time
    var currentTime = new Date();
    console.log("Current: " + currentTime);

    // Get the payment session data from the session
    console.log(paymentSession);
    var paymentMarked = paymentSession.payment_marked;
    console.log(paymentMarked);

    // Check if payment session exists and contains 'payment_session_expires_at'
    if (paymentSession && paymentSession.payment_session_expires_at) {
        // Get the expiration time from the payment session
        var expirationTime = new Date(paymentSession.payment_session_expires_at);
        console.log("expiration: " + expirationTime);

        // Compare the current time to the expiration time
        if (currentTime >= expirationTime && paymentMarked !== true) {
            var diff = currentTime - expirationTime;
            console.log(diff);
            // Session has expired, but it's not marked as paid, so remove it
            $.ajax({
                type: 'POST',
                url: '/remove-transfer-session',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        console.log(currentTime);
                        console.log(expirationTime);
                        // Session removed successfully
                        console.log('Session removed');
                        // location.reload()
                    } else {
                        // Handle removal failure
                        console.log('Session removal failed');
                    }
                },
                error: function () {
                    // Handle AJAX error
                    console.log('Error while removing session');
                }
            });
        } else if (paymentMarked && !paymentSession.payment_confirmed) {
            // Here, you can periodically check if the admin has marked the payment as confirmed
            setInterval(function () {
                $.ajax({
                    type: 'GET',
                    url: `/check-payment-confirmed/${paymentSession.payment_session_id}`,
                    success: function (response) {
                        if (response.success) {
                            if (response.payment_confirmed) {
                                // Payment is confirmed by admin, handle accordingly (create order, end session, display message)
                                console.log('Payment confirmed by admin.');
                                // You can trigger the necessary actions here
                                // For example, redirect to a route to clear payment session and create an order
                                $('#confirmBankTransferForm button[type="submit"]').text('Payment Confirmed').removeClass('btn-warning').addClass('btn-success');
                                window.location.href = '/remove-transfer-session?success=1';
                            } else {
                                $('#confirmBankTransferForm button[type="button"]').text('Payment Not Confirmed yet').removeClass('btn-success').addClass('btn-warning');
                                // Payment is not confirmed yet
                                console.log('Payment not confirmed by admin.');
                            }
                        }
                    },
                    error: function () {
                        // Handle AJAX error
                        console.log('Error while checking payment confirmation.');
                    }
                });
            }, 10000); // Check every 30 seconds (adjust as needed)
        }
    }
}, 60000); // Check every minute (adjust as needed)
if (paymentSession.payment_marked) {
    // window.location.href = '/remove-transfer-session';
    $('#confirmBankTransferForm button[type="submit"]').text('Waiting for Confirmation').removeClass('btn-primary').addClass('btn-warning');
    $('#confirmTransfer').prop('disabled', true); // Disable the button

   
}


 // When "I Have Made the Transfer" button is clicked
 $('#confirmTransfer').click(function(e) {
        e.preventDefault(); // Prevent the default form submission
        // Display "Please Wait" button with rotating icon
        var pleaseWaitButton = $('<button>')
            .text('Please Wait')
            .addClass('btn btn-warning btn-sm')
            .prop('disabled', true)
            .append($('<span>').addClass('spinner-border spinner-border-sm').attr('role', 'status').attr('aria-hidden', 'true'));

        // Replace the original button with "Please Wait" button
        $('#confirmTransfer').replaceWith(pleaseWaitButton);

        // Get the payment session ID
        // var paymentSession = {!! json_encode(session('payment_session')) !!}; // Ensure it's properly formatted as JSON
        console.log(paymentSession.payment_marked)
        // Here, you can choose to disable the button to prevent multiple submissions
        // $('#confirmBankTransferForm button[type="submit"]').prop('disabled', true);

        // You can also perform any other necessary actions, such as sending an email to the admin

        // After performing the necessary actions, you can submit the form using AJAX if needed
        $.ajax({
            type: 'POST',
            url: '/confirm-user-transfer/${paymentSession.payment_session_id}',
            data: $('#confirmBankTransferForm').serialize(),
            success: function(response) {
            if (response.success) {
                console.log(response.transfer);
                location.reload(); // Reload the page
            } else {
                // Display the error message from the server
                console.log('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error and display the error message from the server
                console.log('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    </script>


@endsection



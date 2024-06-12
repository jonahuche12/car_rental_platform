@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Wallet Balance -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{{ route('user.wallet', ['userId' => $teacher->id]) }}" class="text-decoration-none">
                <div class="info-box bg-info">
                    <span class="info-box-icon bg-dark elevation-1">
                        <i class="fas fa-wallet"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Wallet Balance</span>
                        <span class="info-box-number">₦{{ number_format($walletBalance, 2) }}</span>
                    </div>
                </div>
            </a>
        </div>

        @if ($walletBalance > 50)
            <!-- Apply for Withdrawal Button -->
            <div class="col-12 mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#withdrawalModal">
                    Apply for Withdrawal
                </button>
            </div>
        @endif

        <!-- Transactions Table -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title">Recent Transactions</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lesson</th>
                                    <th>Teacher</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaction->lesson->title }}</td>
                                        <td>{{ $transaction->lesson->teacher->profile->full_name }}</td>
                                        <td>{{ $transaction->type }}</td>
                                        <td>₦{{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-center">
                    {{ $transactions->links('partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Modal -->
<div class="modal fade" id="withdrawalModal" tabindex="-1" role="dialog" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="withdrawalModalLabel">Apply for Withdrawal</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="alert alert-success" id="withdraw-success" style="display:none"></p>
                <p class="alert alert-danger" id="withdraw-error" style="display:none"></p>
                <form id="withdrawalForm" method="POST" action="">
                    @csrf
                    <div class="form-group">
                        <label for="withdrawalAmount">Amount (₦)</label>
                        <input type="number" class="form-control" id="withdrawalAmount" name="amount" min="1" max="{{ $walletBalance }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit_btn" class="btn btn-primary">
                        <span id="submit_text">Submit Request</span>
                        <i id="submit_spinner" class="fas fa-spinner fa-spin" style="display: none;"></i>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@section('style')
<style>
    .info-box {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }
    .info-box:hover {
        transform: scale(1.05);
    }
    .info-box-icon {
        font-size: 2rem;
        padding: 15px;
        border-radius: 5px;
    }
    .info-box-content {
        margin-left: 15px;
    }
    .info-box-text {
        font-size: 1.2rem;
        font-weight: bold;
    }
    .info-box-number {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .bg-dark {
        background-color: #343a40 !important;
        color: #fff;
    }
    .text-decoration-none:hover {
        text-decoration: none;
    }
    .card {
        border-radius: 5px;
    }
    .card-header {
        border-bottom: 1px solid #343a40;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }
    .card-footer {
        background-color: #f8f9fa;
    }
    .pagination {
        margin: 0;
    }
    .pagination .page-item .page-link {
        color: #007bff;
    }
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
    }
    .pagination .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
</style>
@endsection

@section('sidebar')
@include('sidebar')
@endsection

@section('title')
Central School System - Wallet for {{$teacher->name}}
@endsection

@section('page_title')
{{$teacher->profile->full_name}}
@endsection

@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection

@section('breadcrumb3')
<a href="#">Wallet</a>
@endsection

@section('scripts')
<script>
    var withdrawalUrl = '{{ route("user.withdrawal_application", ["userId" => $teacher->id]) }}';

    $(document).ready(function() {
        $('#withdrawalModal').on('shown.bs.modal', function () {
            $('#withdrawalAmount').trigger('focus');
        });

        $('#withdrawalForm').submit(function(e) {
            e.preventDefault();
            var amount = $('#withdrawalAmount').val();
            var token = $('input[name="_token"]').val();
            var $submitButton = $('#submit_btn');
            var $submitText = $('#submit_text');
            var $submitSpinner = $('#submit_spinner');
            $submitText.hide();
            $submitSpinner.show();

            $.ajax({
                url: withdrawalUrl,
                method: 'POST',
                data: {
                    amount: amount,
                    _token: token
                },
                success: function(response) {
                    $('#withdraw-success').text(response.message).fadeIn().delay(3000).fadeOut(function() {
                        $('#withdrawalModal').modal('hide');
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText)
                    var errorMessage = xhr.responseJSON.message;
                    $('#withdraw-error').text(errorMessage).fadeIn().delay(3000).fadeOut();
                },
                complete: function() {
                    $submitText.show();
                    $submitSpinner.hide();
                }
            });
        });
    });
</script>


@endsection

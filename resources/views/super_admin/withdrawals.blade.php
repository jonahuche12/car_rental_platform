@extends('layouts.app')

@section('title', 'Manage Withdrawals')

@section('sidebar')
@include('sidebar')
@endsection

@section('page_title')
Manage Withdrawals
@endsection
@section('style')
<style>
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: #fff;
    }
    .card-header {
        background-color: #000;
        color: #fff;
    }
    .table thead th {
        background-color: #000;
        color: #fff;
    }
    .badge-success {
        background-color: #28a745;
    }
    .btn-custom {
        margin: 0 5px;
    }
    .pagination {
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    

    <!-- Hidden input to track active tab -->
    <input type="hidden" id="activeTab" value="{{ session('active_tab', 'completed') }}">
    <p class="alert alert-success" id="withdraw-success" style="display:none"></p>
    <p class="alert alert-danger" id="withdraw-error" style="display:none"></p>

    <ul class="nav nav-tabs" id="withdrawalTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if(session('active_tab', 'completed') == 'completed') active @endif" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="true">Completed Withdrawals</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if(session('active_tab', 'completed') == 'not-completed') active @endif" id="not-completed-tab" data-toggle="tab" href="#not-completed" role="tab" aria-controls="not-completed" aria-selected="false">Not Completed Withdrawals</a>
        </li>
    </ul>

    <div class="tab-content" id="withdrawalTabsContent">
        <div class="tab-pane fade @if(session('active_tab', 'completed') == 'completed') show active @endif" id="completed" role="tabpanel" aria-labelledby="completed-tab">
            <div class="card mt-4">
                <div class="card-header">
                    <h2>Completed Withdrawals</h2>
                </div>
                <div class="card-body">
                    @if($completedWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Account Details</th>
                                        <th>Amount</th>
                                        <th>Processed At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedWithdrawals as $withdrawal)
                                        <tr>
                                            <td>{{ $withdrawal->account_name }}<br>{{ $withdrawal->account_number }}<br>{{ $withdrawal->bank_name }}</td>
                                            <td>{{ $withdrawal->amount }}</td>
                                            <td>{{ $withdrawal->processed_at }}</td>
                                            <td><span class="badge badge-success"><i class="fas fa-check"></i> Completed</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $completedWithdrawals->appends(['not_completed_page' => $notCompletedWithdrawals->currentPage()])->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <p>No completed withdrawals found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane fade @if(session('active_tab', 'completed') == 'not-completed') show active @endif" id="not-completed" role="tabpanel" aria-labelledby="not-completed-tab">
            <div class="card mt-4">
                <div class="card-header">
                    <h2>Not Completed Withdrawals</h2>
                </div>
                <div class="card-body">
                    @if($notCompletedWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Account Details</th>
                                        <th>Amount</th>
                                        <th>Requested At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notCompletedWithdrawals as $withdrawal)
                                        <tr data-id="{{ $withdrawal->id }}">
                                            <td><input type="checkbox" class="select-withdrawal" value="{{ $withdrawal->id }}"></td>
                                            <td>{{ $withdrawal->account_name }}<br>{{ $withdrawal->account_number }}<br>{{ $withdrawal->bank_name }}</td>
                                            <td>{{ $withdrawal->amount }}</td>
                                            <td>{{ $withdrawal->created_at }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-success btn-custom completed_btn" title="Mark as Completed"><i class="fas fa-check"></i></a>
                                                <a href="#" class="btn btn-sm btn-danger btn-custom failed_btn" title="Mark as Failed"><i class="fas fa-times"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <button class="btn btn-success" id="markSelectedCompleted">Mark Selected as Completed</button>
                            <button class="btn btn-danger ml-2" id="markSelectedFailed">Mark Selected as Failed</button>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $notCompletedWithdrawals->appends(['completed_page' => $completedWithdrawals->currentPage()])->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <p>No not completed withdrawals found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get the active tab from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'completed';

    if (activeTab === 'not-completed') {
        $('#not-completed-tab').tab('show');
    } else {
        $('#completed-tab').tab('show');
    }

    // Update the active tab in URL parameters when a tab is clicked
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href").substring(1);
        const newUrlParams = new URLSearchParams(window.location.search);
        newUrlParams.set('tab', target);
        history.replaceState(null, '', '?' + newUrlParams.toString());

        // Send an AJAX request to update the session value
        fetch(`{{ route('update-active-tab') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ active_tab: target })
        });
    });

    // Handle the select all checkbox
    $('#selectAll').on('click', function() {
        $('.select-withdrawal').prop('checked', this.checked);
    });

    // Handle the click event on the completed_btn button
    $('.completed_btn').on('click', function (e) {
        e.preventDefault();
        const withdrawalId = $(this).closest('tr').data('id');
        updateWithdrawalStatus([withdrawalId], 'complete');
    });

    // Handle the click event on the markSelectedCompleted button
    $('#markSelectedCompleted').on('click', function() {
        const selectedIds = getSelectedWithdrawalIds();
        if (selectedIds.length > 0) {
            updateWithdrawalStatus(selectedIds, 'complete');
        } else {
            alert('No withdrawals selected.');
        }
    });

    // Handle the click event on the markSelectedFailed button
    $('#markSelectedFailed').on('click', function() {
        const selectedIds = getSelectedWithdrawalIds();
        if (selectedIds.length > 0) {
            updateWithdrawalStatus(selectedIds, 'fail');
        } else {
            alert('No withdrawals selected.');
        }
    });

    function getSelectedWithdrawalIds() {
        return $('.select-withdrawal:checked').map(function() {
            return $(this).val();
        }).get();
    }
    function updateWithdrawalStatus(ids, action) {
        $.ajax({
            url: `{{ route('withdrawal.complete') }}`,
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                ids: ids,
                action: action
            }),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.status === 'success') {
                    $('#withdraw-success').text(data.message).fadeIn().delay(3000).fadeOut();
                    console.log(data.withdrawals_id);
                    // location.reload(); 
                } else {
                    $('#withdraw-error').text(data.message).fadeIn().delay(3000).fadeOut();
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText)
                console.error('AJAX Error:', status, error);
                $('#withdraw-error').text('An error occurred while updating the withdrawal status.').fadeIn().delay(3000).fadeOut();
            }
        });
    }
});

</script>
@endsection


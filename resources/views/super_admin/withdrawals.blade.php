@extends('layouts.app')

@section('title', 'Manage Withdrawals')

@section('sidebar')
@include('sidebar')
@endsection

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Manage Withdrawals</h1>

    <!-- Hidden input to track active tab -->
    <input type="hidden" id="activeTab" value="{{ session('active_tab', 'completed') }}">

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
            <h2 class="mt-4">Completed Withdrawals</h2>
            <div class="completed-withdrawals mb-5">
                @if($completedWithdrawals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
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

        <div class="tab-pane fade @if(session('active_tab', 'completed') == 'not-completed') show active @endif" id="not-completed" role="tabpanel" aria-labelledby="not-completed-tab">
            <h2 class="mt-4">Not Completed Withdrawals</h2>
            <div class="not-completed-withdrawals mb-5">
                @if($notCompletedWithdrawals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Account Details</th>
                                    <th>Amount</th>
                                    <th>Requested At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notCompletedWithdrawals as $withdrawal)
                                    <tr>
                                        <td>{{ $withdrawal->account_name }}<br>{{ $withdrawal->account_number }}<br>{{ $withdrawal->bank_name }}</td>
                                        <td>{{ $withdrawal->amount }}</td>
                                        <td>{{ $withdrawal->created_at }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success completed_btn" title="Mark as Completed"><i class="fas fa-check"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger failed_btn" title="Mark as Failed"><i class="fas fa-times"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeTab = document.getElementById('activeTab').value;

    if (activeTab === 'not-completed') {
        $('#not-completed-tab').tab('show');
    } else {
        $('#completed-tab').tab('show');
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href").substring(1);
        document.getElementById('activeTab').value = target;

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
});
</script>
@endsection

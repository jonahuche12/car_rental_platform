@extends('layouts.app')

@section('sidebar')
    @include('sidebar')
@endsection

@section('title', "Central School System - Scholarship - $category->name")

@section('breadcrumb2')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb3')
    <a href="#">Scholarship</a>
@endsection

@section('page_title')
    <h5><b>{{ $category->scholarship->title }}</b> - <em>{{ $category->name }}</em></h5>
@endsection

@section('style')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .modal-header {
        background-color: #007bff;
        color: white;
    }
    .modal-header .close {
        color: white;
    }
    .alert {
        display: none;
    }
    .bank-details {
        font-size: 0.9rem;
        color: #333;
    }
    .bank-details strong {
        font-weight: 600;
    }
    .payment-status .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.75em;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h6>Students Enrolled in <b>{{ $category->name }}</b> Scholarship Category</h6>

    @if($students->isEmpty())
        <p>No students enrolled in this category.</p>
    @else
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>AVG SCORE</th>
                    <th>PASSED</th>
                    <th>DETAILS</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->profile->full_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->pivot->avg_score > 0 ? $student->pivot->avg_score : 'N/A' }}</td>
                        <td>{{ $student->pivot->passed ? 'Yes' : 'No' }}</td>
                        <td>
                            <div class="bank-details">
                                <strong>Bank Name:</strong> <span>{{ $student->profile->bank_name }}</span><br>
                                <strong>Account Name:</strong> <span>{{ $student->profile->account_name }}</span><br>
                                <strong>Account Number:</strong> <span>{{ $student->profile->account_number }}</span>
                            </div>
                            <div class="payment-status mt-2">
                                @if($student->pivot->reward_completed)
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Paid</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Unpaid</span>
                                    <button class="btn btn-primary btn-sm mt-2 mark-paid-btn" data-student-id="{{ $student->id }}" data-student-name="{{ $student->profile->full_name }}">Mark as Paid</button>
                                @endif
                            </div>
                        </td>
                        <td>{{ $student->pivot->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        {{ $students->links() }}

        <!-- Button to mark all students as paid or all paid -->
        @if($students->every(function ($student) { return $student->pivot->reward_completed; }))
            <button type="button" class="btn bg-success mt-4" disabled><i class="fas fa-check-circle"></i> All Paid</button>
        @else
            <button type="button" class="btn btn-success mt-4" id="markAllPaidBtn">Mark All Paid</button>
        @endif
    @endif

    <!-- Publish Button -->
    <button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#publishModal">
     Publish
    </button>
    @if($category->end_date && $category->end_date < now())
        <!-- Process Category Button -->
        @if($category->processed)
            <button type="button" class="btn bg-success mt-4" disabled><i class="fas fa-check-circle"></i> Processed</button>
        @else
            <button type="button" class="btn btn-success mt-4" data-toggle="modal" data-target="#processCategoryModal">
                <i class="fas fa-cog"></i> Process Category
            </button>
        @endif

        <!-- Process Category Modal -->
        <div class="modal fade" id="processCategoryModal" tabindex="-1" role="dialog" aria-labelledby="processCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="processCategoryModalLabel">Process Scholarship Category - {{ $category->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="process-success" class="alert alert-success"></div>
                        <div id="process-error" class="alert alert-danger"></div>
                        <form id="processCategoryForm">
                            @csrf
                            <button type="submit" class="btn btn-success">Confirm and Process</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Publish Modal -->
    <div class="modal fade" id="publishModal" tabindex="-1" role="dialog" aria-labelledby="publishModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="publishModalLabel">Edit Scholarship Category - {{ $category->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="category-success" class="alert alert-success"></div>
                    <div id="category-error" class="alert alert-danger"></div>
                    <form id="updateCategoryForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="datetime-local" class="form-control" name="start_date" id="start_date" value="{{ $category->start_date ? $category->start_date->format('Y-m-d\TH:i') : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="datetime-local" class="form-control" name="end_date" id="end_date" value="{{ $category->end_date ? $category->end_date->format('Y-m-d\TH:i') : '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark as Paid Modal -->
    <div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog" aria-labelledby="markPaidModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markPaidModalLabel">Confirm Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="payment-success" class="alert alert-success"></div>
                    <div id="payment-error" class="alert alert-danger"></div>
                    <form id="markPaidForm">
                        @csrf
                        <input type="hidden" id="studentId" name="student_id" value="">
                        <input type="hidden" id="categoryId" name="category_id"     value="{{$category->id}}">
    <input type="hidden" id="isBulk" name="is_bulk" value="false">
    <p id="confirmText">Are you sure you want to mark <span id="studentName"></span> as paid?</p>
    <button type="submit" class="btn btn-success">Confirm Payment</button>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Individual mark as paid button click
    $('.mark-paid-btn').on('click', function() {
        var studentId = $(this).data('student-id');
        var studentName = $(this).data('student-name');
        $('#studentId').val(studentId);
        $('#studentName').text(studentName);
        $('#isBulk').val('false');
        $('#markPaidModal').modal('show');
    });

    // Mark all as paid button click
    $('#markAllPaidBtn').on('click', function() {
        $('#studentId').val('');
        $('#studentName').text('all students that passed');
        $('#isBulk').val('true');
        $('#markPaidModal').modal('show');
    });

   // Handle mark as paid form submission
    $('#markPaidForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('scholarship_students.mark_as_paid') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                $('#payment-success').text(response.success).fadeIn().delay(3000).fadeOut();
                setTimeout(function() {
                    $('#markPaidModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                }, 3000);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                $('#payment-error').text('An error occurred while updating the payment status.').fadeIn().delay(3000).fadeOut();
            }
        });
    });

    // Handle category update form submission
    $('#updateCategoryForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('scholarship_categories.update', $category->id) }}",
            type: "PUT",
            data: formData,
            success: function(response) {
                $('#category-success').text(response.success).fadeIn().delay(3000).fadeOut();
                setTimeout(function() {
                    $('#publishModal').modal('hide');
                }, 3000);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                $('#category-error').text('An error occurred while updating the category.').fadeIn().delay(3000).fadeOut();
            }
        });
    });

    // Handle process category form submission
    $('#processCategoryForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('scholarship_categories.process', $category->id) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#process-success').text(response.success).fadeIn().delay(3000).fadeOut();
                setTimeout(function() {
                    $('#processCategoryModal').modal('hide');
                }, 3000);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                $('#process-error').text('An error occurred while processing the category.').fadeIn().delay(3000).fadeOut();
            }
        });
    });
});
</script>
@endsection

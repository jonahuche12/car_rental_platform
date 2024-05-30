@extends('layouts.app')
@section('sidebar')
@include('sidebar')
@endsection

@section('title', "Central School System - Scholarship - $category->name")

@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection

@section('breadcrumb3')
<a href="#">scholarship</a>
@endsection

@section('page_title')
<h5><b>{{$category->scholarship->title}}</b> - <em>{{$category->name}}</em></h5>
@endsection

@section('content')
<div class="container">
    <h6>Students Enrolled in <b>{{ $category->name }}</b> Scholarship Category</h6>

    @if($students->isEmpty())
        <p>No students enrolled in this category.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->profile->full_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->pivot->created_at->format('Y-m-d') }}</td> <!-- Assuming 'created_at' is available in the pivot table -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        {{ $students->links() }}
    @endif

    <!-- Publish Button -->
    <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#publishModal">
        Publish
    </button>

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
                <p class="alert alert-success" id="category-success" style="display:none"></p>
                <p class="alert alert-danger" id="category-error" style="display:none"></p>
                <div class="modal-body">
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
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#updateCategoryForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('scholarship_categories.update', $category->id) }}",
            type: "PUT",
            data: formData,
            success: function(response) {
                $('#category-success').text(response.success).fadeIn().delay(3000).fadeOut(function() {
                    setTimeout(function() {
                        $('#publishModal').modal('hide');
                    }, 3000);
                });
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                $('#category-error').text('An error occurred while updating the category.').fadeIn().delay(3000).fadeOut();
            }
        });
    });
});

</script>
@endsection

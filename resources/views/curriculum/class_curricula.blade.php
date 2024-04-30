@extends('layouts.app')

@section('title', "Central School System - Curriculum")

@section('breadcrumb1')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb2', "Curricula")

@section('content')
    @include('sidebar')

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Curricula</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Subject</th>
                                <th class="text-center">Theme</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($curricula as $curriculum)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $curriculum->subject }}</td>
                                    <td class="text-center">{{ $curriculum->theme }}</td>
                                    <td class="text-center">{{ $curriculum->description }}</td>
                                    <td class="project-actions text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapse{{ $curriculum->id }}" aria-expanded="false" aria-controls="collapse{{ $curriculum->id }}">
                                                <i class="fa fa-chevron-down"></i> Topics ({{ $curriculum->topics()->count() }})
                                            </button>
                                            <a class="btn btn-success btn-sm view-curriculum-btn" href="#" data-curriculum-id="{{ $curriculum->id }}" data-toggle="modal">
                                                <i class="fas fa-eye"></i> View Lessons
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse" id="collapse{{ $curriculum->id }}">
                                    <td colspan="5">
                                        <table class="table table-striped mt-2">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Topic</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($curriculum->topics as $topic)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $topic->pivot->topic }}</td>
                                                        <td>{{ $topic->pivot->description }}</td>
                                                        <td>
                                                            <a class="btn btn-success btn-sm view-topic-btn" href="#" data-topic-id="{{ $topic->pivot->id }}" data-curriculum-id="{{ $curriculum->id }}" data-toggle="modal">
                                                                <i class="fas fa-eye"></i> View Lessons 
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Topics available for this Curriculum.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Curriculum Available Yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
<script>
    // Handle click event on "View Lessons" button
    $('.view-curriculum-btn').on('click', function(e) {
        e.preventDefault();

        var curriculumId = $(this).data('curriculum-id');

        // Make AJAX request to fetch curriculum details
        $.ajax({
            url: '/get-curriculum-details/' + curriculumId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirect to route with the retrieved curriculum ID
                    window.location.href = '/get-related-lessons/' + response.curriculum_id;
                } else {
                    // Handle error case if needed
                    console.log('Failed to retrieve curriculum details.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                console.log(xhr.responseText)
            }
        });
    });

    // JavaScript code to handle the AJAX request
$(document).ready(function() {
    $('.view-topic-btn').click(function(e) {
        e.preventDefault();

        // Retrieve topic ID and curriculum ID from the button's data attributes
        var topicId = $(this).data('topic-id');
        var curriculumId = $(this).data('curriculum-id');

        // Send AJAX request to retrieve topic details
        $.ajax({
            url: '/get-topic-details/' + topicId + '/' + curriculumId,
            type: 'GET',
            success: function(response) {
                console.log(response)
                // Upon successful response, redirect to a new route passing topic details
                window.location.href = '/curriculum/' + curriculumId + '/topic/' + topicId;
            },
            error: function(xhr, status, error) {
                // Handle error if AJAX request fails
                console.error(xhr.responseText);
            }
        });
    });
});

</script>

@endsection

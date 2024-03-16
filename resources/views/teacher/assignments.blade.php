@extends('layouts.app')

@section('title', "Central School system - Show Classes")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "School Package")

@section('style')
<style>
    .table {
        margin-bottom: 0; /* Remove default bottom margin */
    }

    .nested-table {
        margin-bottom: 0; /* Remove bottom margin for the nested table */
    }

    /* Additional styling as needed */
</style>

@endsection

@section('content')
@include('sidebar')
<div class="">
    <button class="btn btn-primary" data-toggle="modal" data-target="#createAssignmentModal">Create New Assignment</button>
</div>
    <section class="content">

        <!-- Default box -->
        <div class="card">
            
            <div class="card-header">
            <h3 class="card-title">
                <img alt="Avatar" class="table-avatar rounded-circle" src="{{ asset('storage/' . $school->logo) }}" style="width: 50px; height: 50px;">
                <b>{{ $school->name }}</b>
            </h3>

            </div>
            
            <div class="card-body p-0 table-responsive table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl" >
                <div class="message" style="display:none"></div>
                <table class="table projects">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th >
                                Assignment Name
                            </th>
                            
                            
                            <th class="text-center">
                                Description
                            </th>
                            <!-- <th style="width: 10%">
                                Progress
                            </th> -->
                            
                            <th style="width: 25%"  class="text-center">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                        <tr class="bg-info">
                            <td>
                                {{ $loop->iteration }}
                                <i class="toggle-icon fa fa-chevron-up"></i>
                            </td>
                            <td style="width: 21%">
                                <a>
                                    {{ $assignment->name }}
                                </a>
                                <br />
                                <small>
                                    Created {{ $assignment->academic_session }}
                                </small>
                                
                            </td>
                            


                            
                            <td style="width: 25%" class="text-center">
                                {{ $assignment->description }}
                            </td>
                            <!-- <td class="project_progress text-center" style="width: 10%" >
                                
                                <small>
                                    {{ 0 }}% Complete
                                </small>
                            </td> -->

                            <td class="project-actions text-right">
                                <div class="btn-group">
                                    
                                    <a class="btn btn-secondary btn-sm edit-class-btn" href="#" data-assignment-id="{{ $assignment->id }}">
                                        <i class="fas fa-pencil-alt"></i> <span class="action d-none d-sm-inline">Edit</span>
                                    </a>
                                   
                                    <button class="btn btn-warning toggle-nested-table" data-assignment-id="{{ $assignment->id }}">
                                    <i class="toggle-icon fa fa-chevron-up"></i><span class="action d-none d-sm-inline"></span>
                                    </button>
                                    <!-- <a class="btn btn-danger btn-sm delete-package-btn" href="#" data-assignment-id="{{ $assignment->id }}" data-picture="{{ $assignment->picture }}">
                                        <i class="fas fa-trash"></i><span class="action d-none d-sm-inline"> Delete</span>
                                    </a> -->
                                </div>
                            </td>


                        </tr>
                        <tr>

                        
                        <td colspan="8"  style="margin-top:-5px" > 
                        
                           
                        <table class="table table-striped nested-table mt-2" data-assignment-id="{{ $assignment->id }}" >
      
                            <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th>Student Full Name</th>
                                    <th>Score</th>
                                    <th>Actions</th> <!-- Add a column for actions -->
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assignment->classSection->students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        
                                        <td>{{ $student->profile->full_name }}</td>
                                        <td>{{ $student->bio }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-info btn-sm view-section-details-btn" href="{{ route('view_section', ['sectionId' => $student->id]) }}">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a class="btn btn-secondary btn-sm edit-section-z" href="#" data-section-id="{{ $student->id }}" data-toggle="modal" data-target="#editSectionModal">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm delete-section-btn" href="#" data-section-id="{{ $student->id }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No Class Student available for this Class.
                                            <!-- You can add a button to create new class sections if needed -->
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        </td>
                    </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                No Assignments available for this School.
                                <div class="-left">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#createAssignmentModal">Create New Class</button>
                                </div>
                            
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->


        <!-- Modal for Creating a New Class Section -->
        <div class="modal fade" id="createAssignmentModal" tabindex="-1" role="dialog" aria-labelledby="createAssignmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createAssignmentModalLabel">Create New Assignment for <b><span id="class-name">{{$class_section->name}}</span></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for Creating a New Class Section -->
                        <form action="#" id="createAssignmentForm" method="POST">
                            @csrf
                            <!-- Class Section Name -->
                            <div class="form-group">
                                <label for="section_name">Assignment Title:</label>
                                <input type="text" class="form-control" id="section_name" name="name" placeholder="Reproductive System 1" required>
                            </div>


                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>



                            <!-- Add more form fields as needed -->

                            <!-- Button to Submit the Form -->
                            <button type="submit" class="btn btn-primary">Create Assignment</button>
                        </form>
                    </div>
                    <!-- Additional Modal Footer or Content as needed -->
                </div>
            </div>
        </div>

        


    </section>



@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#createAssignmentForm').submit(function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Serialize the form data
            var formData = $(this).serialize();

            // Send an AJAX request
            $.ajax({
                url: '/create_assignmment', // Replace with your actual route
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    // Optionally, display a success message or redirect the user
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    // Optionally, display an error message to the user
                }
            });
        });
    });
</script>



@endsection




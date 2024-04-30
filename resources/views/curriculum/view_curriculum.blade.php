@extends('layouts.app')

@section('title', "Central School System - Curriculum")

@section('breadcrumb1')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb2', "Curricula")

@section('content')
@include('sidebar')

<section class="content">

    <!-- Default box -->
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
            <div class="message" style="display:none"></div>
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">#</th>
                        <th style="width: 20%" class="text-center">Subject</th>
                        <th style="width: 24%" class="text-center">Theme</th>
                        <th style="width: 30%" class="text-center">Description</th>
                        <th style="width: 25%" class="text-center">Actions</th>
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
                                    <i class="fa fa-chevron-down"></i> <span class="d-none d-sm-inline">Topics ({{ $curriculum->topics()->count() }})</span>
                                </button>
                                <a class="btn btn-success btn-sm edit-curriculum-btn" href="#" data-curriculum-id="{{ $curriculum->id }}" data-toggle="modal">
                                    <i class="fas fa-eye"></i> <span class="d-sm-inline">View Lessons</span>
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
                                        <td>{{ $topic->pivot->topic }} </td>
                                        <td>{{ $topic->pivot->description }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <!-- <a class="btn btn-info btn-sm view-section-details-btn" href="#">
                                                    <i class="fas fa-eye"></i> View
                                                </a> -->
                                                
                                                <a class="btn btn-success btn-sm edit-curriculum-btn" href="#" data-curriculum-id="{{ $curriculum->id }}" data-toggle="modal">
                                                    <i class="fas fa-eye"></i> <span class="d-sm-inline">View Lessons</span>
                                                </a>
                                            </div>
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
                        <td colspan="5" class="text-center">
                            No Curriculum Available Yet.
                           
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- /.card-body -->
    </div>
    <!-- /.card -->



            <div class="modal fade" id="creatCurriculumModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create New Curriculum</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Add a form for creating a new package -->
                            <form id="createCurriculumForm" method="POST" action="#">
                                @csrf

                            <div class="form-group mb-3">
                                <label for="country">Choose Country:</label>
                                <select class="form-control" name="country" id="country_input">
                                    <option value="">Choose Country</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Kenya">Kenya</option>
                                </select>
                            </div>
                                <div class="form-group">
                                    <label for="theme">Theme:</label>
                                    <input type="text" name="theme" class="form-control" required>
                                </div>


                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="class_level">Class Level:</label>
                                    <select name="class_level" class="form-control" id="class_level">
                                        <option value="">Select Level</option>
                                        <option value="primary_one">Primary One</option>
                                        <option value="primary_two">Primary Two</option>
                                        <option value="primary_three">Primary Three</option>
                                        <option value="primary_four">Primary Four</option>
                                        <option value="primary_five">Primary Five</option>
                                        <option value="primary_six">Primary Six</option>
                                        <option value="jss_one">JSS One</option>
                                        <option value="jss_two">JSS Two</option>
                                        <option value="jss_three">Jss Three</option>
                                    </select>
                                </div>


                                <div id="topics-container">
                                    <div class="form-group topic-group">
                                        <fieldset>
                                            <legend>Topic 1</legend>
                                            <div>
                                                <label for="topic">Topic:</label>
                                                <input type="text" name="topic[]" class="form-control" required>
                                            </div>
                                            <div>
                                                <label for="topic_descriptions">Description:</label>
                                                <textarea name="topic_description[]" class="form-control"></textarea>
                                            </div>
                                        </fieldset>
                                        <button type="button" class="btn btn-danger remove-topic">Remove</button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success" id="add-topic">Add Topic</button>


                                <button type="submit" id='submit-form' class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Add this code within your Blade template, inside the edit modal -->
        <div class="modal fade" id="editCurriculumModal" tabindex="-1" role="dialog" aria-labelledby="editCurriculumModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCurriculumModalLabel">Edit Curriculum  <span id="curriculum-name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add a form for editing a package -->
                        <form id="editCurriculumForm" data-curriculum-id="">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_name">Theme</label>
                                <input type="text" class="form-control" id="edit_theme" name="theme" required>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Update Curriculum</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Add this code within your Blade template, inside the edit modal -->
        <div class="modal fade" id="editCurriculumTopicModal" tabindex="-1" role="dialog" aria-labelledby="editCurriculumTopicModalLabel" aria-hidden="true" data-topic-id="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCurriculumTopicModalLabel">Edit Topic <span id="topic"></span> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" style="display:none" id="editSuccessMessage"></div>
                        <div class="alert alert-danger" style="display:none" id="editErrorMessage"></div>
                        <!-- Add a form for editing a package -->
                        <form id="editCurriculumTopicForm" data-topic-id="">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="edit_topic">Topic</label>
                                <input type="text" class="form-control" id="edit_topic" name="topic" required>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="edit_section_description">Description</label>
                                <textarea class="form-control" id="edit_topic_description" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Update Topic</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="deleteConfirmationModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Topic? 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCurriculumTopicModal" tabindex="-1" role="dialog" aria-labelledby="addCurriculumTopicModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCurriculumTopicModalLabel">
                            Add Topic for <b><span id="curriculum_theme"></span></b>
                        </h5>
                        <span id="curriculum_topic_id" style="display:none" data-curriculum_topic-id=""></span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add a form for adding a new topic -->
                        <form id="addCurriculumTopicForm">
                            <!-- Name field -->
                            <div class="form-group">
                                <label for="add_topic">Topic</label>
                                <input type="text" class="form-control" id="add_topic" name="topic" required>
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label for="add_topic_description">Description</label>
                                <textarea class="form-control" id="add_topic_description" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Add Topic</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>




</section>



@endsection

@section('scripts')


<script>


</script>





@endsection




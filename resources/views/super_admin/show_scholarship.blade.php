@extends('layouts.app')

@section('title', "CSS - Test Details")

@section('style')
<style>
    .list-group-item {
        margin-bottom: 10px;
    }

    .list-group-item .btn-group .btn {
        margin-right: 5px;
    }

    .position-relative {
        position: relative;
    }

    .img-thumbnail {
        width: 100px;
        height: 100px;
    }

    @media (max-width: 767.98px) {
        .btn-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-group .btn {
            margin-bottom: 5px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .info-box {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        background-color: #fff;
    }

    .card-body p {
        margin-bottom: 10px;
    }

    .btn-primary {
        margin-top: 15px;
    }
    .test-item {
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .test-item .card-body {
        padding: 15px;
    }

    .test-item .form-check-label {
        font-weight: bold;
    }

    .test-item .form-check-input {
        margin-top: 5px;
    }

</style>
@endsection

@section('breadcrumb3', "Scholarship")
@section('breadcrumb2')
<a href="#">{{ $scholarship->name }}</a>
@endsection

@section('content')
@include('sidebar')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $scholarship->title }}</h5>
        </div>
        <div class="card-body">
            <div class="card mt-4">
                <div class="card-body">
                    <div class="info-box">
                        <p><strong>Type:</strong> {{ ucfirst($scholarship->type) }}</p>
                    </div>
                    <div class="info-box">
                        <p><strong>Academic Session:</strong> {{ $scholarship->academicSession->name }}</p>
                    </div>
                    <div class="info-box">
                        <p><strong>Term:</strong> {{ $scholarship->term->name }}</p>
                    </div>
                    <div class="info-box">
                        <p><strong>Class Level:</strong> {{ $scholarship->class_level }}</p>
                    </div>
                    <div class="info-box">
                        <p><strong>Category Count:</strong> <span id="category-count-{{ $scholarship->id }}">{{ $scholarship->categories->count() }}</span></p>
                    </div>
                    <!-- Add categories button -->
                    <div class="text-center mt-3">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                            <i class="fas fa-plus-circle"></i> Add Category
                        </button>
                    </div>
                </div>
            </div>

           <!-- Display categories -->
            <div class="mt-4">
                <h5>Categories</h5>
                <ul class="list-group">
                    @foreach($scholarship->categories as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <span class="btn-link" style="cursor:pointer" data-toggle="collapse" data-target="#testsCollapse{{ $category->id }}">{{ $category->name }}</span>
                            <div class="btn-group mt-2 mt-md-0">
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addTestModal" data-category-id="{{ $category->id }}" data-class-level="{{ $category->scholarship->class_level }}">
                                    <i class="fas fa-plus-circle"></i> Add Test({{ $category->tests->count() }})
                                </button>

                                <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editScholarshipCategoryModal"
                                        data-category-id="{{ $category->id }}"
                                        data-category-name="{{ $category->name }}"
                                        data-required-viewed-lessons="{{ $category->required_viewed_lessons }}"
                                        data-reward-amount="{{ $category->reward_amount }}"
                                        data-description="{{ $category->description }}"
                                        data-required-connects="{{ $category->required_connects }}">
                                    <i class="fas fa-edit"></i> Edit Category
                                </button>

                                <button class="btn btn-sm btn-outline-danger delete-category-btn" data-category-id="{{ $category->id }}" data-toggle="modal" data-target="#deleteCategoryModal">
                                    <i class="fas fa-trash-alt"></i> Delete Category
                                </button>

                                <a href="{{ route('scholarshipCategory.students', $category->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-user-graduate"></i> View Students ({{ $category->students->count() }})
                                </a>
                            </div>
                        </li>
                        <div id="testsCollapse{{ $category->id }}" class="collapse">
                            <ul class="list-group">
                                @foreach($category->tests as $test)
                                    <li class="list-group-item ml-4 d-flex justify-content-between align-items-center flex-wrap">
                                        <span>{{ $test->title }}</span>
                                        <div class="btn-group mt-2 mt-md-0">
                                            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editTestModal" data-test-id="{{ $test->id }}" data-test-title="{{ $test->title }}">
                                                <i class="fas fa-edit"></i> Edit Test
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-test-btn" data-test-id="{{ $test->id }}">
                                                <i class="fas fa-trash-alt"></i> Remove Test
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="category-success-{{ $scholarship->id }}" style="display:none"></p>
                <p class="alert alert-danger" id="category-error-{{ $scholarship->id }}" style="display:none"></p>
            <form action="{{ route('scholarship_categories.store') }}" id="createScholarshipCategoryForm" method="POST">
                    @csrf
                    <input type="hidden" name="scholarship_id" value="{{ $scholarship->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="required_viewed_lessons">Required Viewed Lessons</label>
                            <input type="number" class="form-control" name="required_viewed_lessons" required>
                        </div>
                        <div class="form-group">
                            <label for="reward_amount">Reward Amount</label>
                            <input type="number" class="form-control" name="reward_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="required_connects">Required Connects</label>
                            <input type="number" class="form-control" name="required_connects" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            Create Category
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- Edit Scholarship Category Modal -->
<div class="modal fade" id="editScholarshipCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editScholarshipCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editScholarshipCategoryModalLabel">Edit Scholarship Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="edit-success" style="display:none"></p>
            <p class="alert alert-danger" id="edit-error" style="display:none"></p>
            <form id="editScholarshipCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_category_id" name="category_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="required_viewed_lessons">Required Viewed Lessons</label>
                        <input type="number" class="form-control" id="required_viewed_lessons" name="required_viewed_lessons" required>
                    </div>
                    <div class="form-group">
                        <label for="reward_amount">Reward Amount</label>
                        <input type="number" step="0.01" class="form-control" id="reward_amount" name="reward_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="required_connects">Required Connects</label>
                        <input type="number" class="form-control" id="required_connects" name="required_connects" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="delete-success" style="display:none"></p>
                <p class="alert alert-danger" id="delete-error" style="display:none"></p>
            <div class="modal-body">
                Are you sure you want to delete this category?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCategory">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Test Modal -->
<div class="modal fade" id="addTestModal" tabindex="-1" role="dialog" aria-labelledby="addTestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addTestModalLabel">Add Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="alert alert-success" id="test-success" style="display:none"></p>
            <p class="alert alert-danger" id="test-error" style="display:none"></p>
            <form id="addTestForm" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="category_id" id="test_category_id">
                    <div class="form-group">
                        <label for="test_title">Select Tests</label>
                        <div id="testList">
                            <!-- Tests will be populated here via JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        Add Tests
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle add category form submission
    $('[id^="createScholarshipCategoryForm"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var scholarshipId = form.find('input[name="scholarship_id"]').val();
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function() {
                    form.find('.spinner-border').show();
                },
                success: function(response) {
                    form.find('.spinner-border').hide();
                    $('#category-success-' + scholarshipId).text(response.message).fadeIn();

                    var categoryCountElement = $('#category-count-' + scholarshipId);
                    var currentCount = parseInt(categoryCountElement.text());
                    categoryCountElement.text(currentCount + 1);

                    // Display success message for 3 seconds
                    setTimeout(function() {
                        $('#category-success-' + scholarshipId).fadeOut();

                        // Wait for 2 seconds before hiding the modal
                        setTimeout(function() {
                            $('#addCategoryModal').modal('hide');
                        }, 2000);
                        location.reload()
                    }, 3000);
                },
                error: function(xhr) {
                    form.find('.spinner-border').hide();
                    var errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $('#category-error-' + scholarshipId).text(errorMessage).fadeIn().delay(3000).fadeOut();
                }
            });
        });

        var categoryIdToDelete;

    // Show delete confirmation modal
    $('.delete-category-btn').on('click', function() {
        categoryIdToDelete = $(this).data('category-id');
    });

    // Handle delete confirmation
    $('#confirmDeleteCategory').on('click', function() {
        if (!categoryIdToDelete) {
            $('#delete-error').text("Category ID is not set.").fadeIn();
            return;
        }

        $.ajax({
            type: 'DELETE',
            url: `/categories/${categoryIdToDelete}`,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#delete-success').text(response.message).fadeIn();

                setTimeout(function() {
                    $('#delete-success').fadeOut();

                    // Hide the modal
                    $('#deleteCategoryModal').modal('hide');

                    // Remove the category parent element from the DOM
                    $('button[data-category-id="' + categoryIdToDelete + '"]').closest('li').remove();
                    var categoryCountElement = $('#category-count-{{ $scholarship->id }}');
                    var currentCount = parseInt(categoryCountElement.text());
                    categoryCountElement.text(currentCount - 1);
                }, 3000);
            },
            error: function(xhr) {
                $('#delete-error').text("There was an error deleting this Category. Please try again later.").fadeIn();

                setTimeout(function() {
                    $('#delete-error').fadeOut();
                }, 3000);

                console.log(xhr.responseText);
            }
        });
    });

    $('#editScholarshipCategoryModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var categoryId = button.data('category-id');
        var categoryName = button.data('category-name');
        var requiredViewedLessons = button.data('required-viewed-lessons');
        var rewardAmount = button.data('reward-amount');
        var description = button.data('description');
        var requiredConnects = button.data('required-connects');
        var modal = $(this);

        modal.find('#edit_category_id').val(categoryId);
        modal.find('#category_name').val(categoryName);
        modal.find('#required_viewed_lessons').val(requiredViewedLessons);
        modal.find('#reward_amount').val(rewardAmount);
        modal.find('#description').val(description);
        modal.find('#required_connects').val(requiredConnects);
    });

    // Handle edit category form submission
    $('#editScholarshipCategoryForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();
        var submitButton = form.find('button[type="submit"]');
        var spinner = submitButton.find('.spinner-border');
        var categoryId = form.find('#edit_category_id').val();

        submitButton.prop('disabled', true);
        spinner.show();

        $.ajax({
            type: 'POST',
            url: `/categories/${categoryId}`,
            data: formData,
            success: function(response) {
                submitButton.prop('disabled', false);
                spinner.hide();
                $('#edit-success').text(response.message).fadeIn().delay(3000).fadeOut();
                setTimeout(function() {
                    $('#editScholarshipCategoryModal').modal('hide');
                    location.reload();  // Reload the page to reflect the changes
                }, 3000);
            },
            error: function(xhr) {
                $('#edit-error').text(xhr.responseJSON.message).fadeIn().delay(3000).fadeOut();
                submitButton.prop('disabled', false);
                spinner.hide();
            }
        });
    });

   // Function to load tests and set checkboxes
function loadTests(classLevel, categoryId) {
    $.ajax({
        type: 'GET',
        url: '/api/tests/latest',
        data: { class_level: classLevel, category_id: categoryId },  // Send class_level and category_id with the request
        success: function(response) {
            console.log(response);
            var testList = $('#testList');
            testList.empty();
            response.tests.forEach(function(test) {
                var isChecked = response.currentTestIds.includes(test.id) ? 'checked' : '';
                var testItem = `
                    <div class="test-item card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tests[]" value="${test.id}" id="test_${test.id}" ${isChecked}>
                                <label class="form-check-label" for="test_${test.id}">
                                    ${test.title} (${test.class_level}, ${test.term_name}, ${test.academic_session_name})
                                </label>
                            </div>
                        </div>
                    </div>`;
                testList.append(testItem);
            });

            // Handle checkbox change
            $('input[name="tests[]"]').change(function() {
                var testId = $(this).val();
                var isChecked = $(this).is(':checked');
                toggleTest(categoryId, testId, isChecked);
            });
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

// Function to toggle tests in the category
function toggleTest(categoryId, testId, add) {
    $.ajax({
        type: 'POST',
        url: '/categories/toggle-test',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            category_id: categoryId,
            test_id: testId,
            add: add
        },
        success: function(response) {
            console.log(response.message);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

// Event listener for showing the modal
$('#addTestModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var classLevel = button.data('class-level');
    var categoryId = button.data('category-id');  // Get category ID from the button data attribute

    // Set the category_id input value
    $('#test_category_id').val(categoryId);

    loadTests(classLevel, categoryId);
});

// Form submission handler
$('#addTestForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    var formData = form.serialize();
    var submitButton = form.find('button[type="submit"]');
    var spinner = submitButton.find('.spinner-border');

    submitButton.prop('disabled', true);
    spinner.show();

    $.ajax({
        type: 'POST',
        url: form.attr('action'),  // Ensure the form action URL is set correctly
        data: formData,
        success: function(response) {
            submitButton.prop('disabled', false);
            spinner.hide();
            $('#test-success').text(response.message).fadeIn().delay(3000).fadeOut();
            $('#addTestModal').modal('hide');
            location.reload();  // Reload the page to reflect the changes
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $('#test-error').text("There is an error Adding this test").fadeIn().delay(3000).fadeOut();
            submitButton.prop('disabled', false);
            spinner.hide();
        }
    });
});


});
</script>
@endsection

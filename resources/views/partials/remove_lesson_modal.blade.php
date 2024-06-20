<!-- resources/views/partials/remove_lesson_modal.blade.php -->
<div class="modal fade" id="removelessonModal{{ $lesson->id }}" tabindex="-1" role="dialog" aria-labelledby="removelessonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removelessonModalLabel">Remove lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success lesson-message" style="display:none;"></div>
            <div class="alert alert-danger" id="lesson-error" style="display:none;"></div>
            <div class="modal-body">
                Are you sure you want to delete <b>{{ $lesson->title }}</b>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="removeLessonBtn" onclick="removeLesson({{ $lesson->id }})">Remove</button>
            </div>
        </div>
    </div>
</div>

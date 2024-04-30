<button id="toggleLessonForm" class="btn btn-primary create_new_lesson">Create New Lesson</button><br>
<button type="button" id="backToVideoButton" class="btn btn-clear btn-lg mb-3"  style=" position: absolute; top: 50px; left: 10px; display:none; z-index: 999;" onclick="backToVideoPreview()">
    <i class="fas fa-arrow-left text-secondary"></i>
</button>
<form id="lessonForm" class="lesson-form" action="{{route('upload')}}" enctype="multipart/form-data">
    @csrf
    <div class="alert alert-success" id="success-message" style="display:none;"></div>
    <div class="alert alert-danger" id="error-message" style="display:none;"></div>
    <div id="videoFields" style="">

    <small><span class="file-info" id="span-info"><b>Upload Video(Max duration: 10 mins)</b></span></small>
    <div class="alert alert-danger" id="video-error" style="display:none;"></div>
    <div class="form-group">
        <!-- <label for="lessonTitle">Title</label> -->
        <input type="text" class="form-control" id="lessonTitle" name="title" placeholder="Enter Title of the Lesson">
    </div>
    <span><small>Add video</small></span>

    <div class="form-group" style="border: 1px solid #ccc; border-radius:5px;">
        <div id="videoPreviewContainer" class="video-preview-container">
            <div id="videoPlaceholder" class="video-placeholder" onclick="triggerFileInput('lessonVideo')">
                <i class="fas fa-plus"></i> <!-- Plus icon -->
                <div id="spinner" class="spinner-border text-primary" role="status" style="display: none;"></div>
            </div>
            <input type="file" class="form-control-file" id="lessonVideo" name="video" accept="video/*" onchange="previewFile('lessonVideo', 'videoPreview', 'videoLoader')" style="display: none;">
            <span class="file-info">(Max duration: 10 minutes)</span>
            <div id="videoLoader" class="loader" style="display: none;"></div>
            <video id="videoPreview" class="video-preview" controls autoplay style="display: none;">Your browser does not support the video tag.</video>
        </div>
    </div>

    <!-- Continue button -->
    <div class="btn-group" role="group" aria-label="Lesson Form Buttons">
        <button type="button" class="btn btn-primary" id="continue-btn" disabled>Continue</button>
    </div>
    </div>
                            <!-- Other form fields -->
        <div id="otherFormFields" style="display: none;">
        <input type="text" name="lesson_id" id="lesson_id" class="form-control">
        
        <div class="form-group">
            <label for="lessonSubject">Subject</label>
            <select name="subject" class="form-control" id="general_name">
                <option value="">Select Subject</option>
                @foreach($uniqueSubjectNames as $subject_name)
                <option value="{{$subject_name}}">{{$subject_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="lessonSubject">Class</label>
            <select name="class_level" class="form-control" id="class_level">
                <option value="">Select Class Level</option>
                <option value="primary_one">Primary One</option>
                <option value="primary_two">Primary Two</option>
                <option value="primary_three">Primary Three</option>
                <option value="primary_four">Primary Four</option>
                <option value="primary_five">Primary Five</option>
                <option value="primary_six">Primary Six</option>
                <option value="jss_one">JSS One</option>
                <option value="jss_two">JSS Two</option>
                <option value="jss_three">Jss Three</option>
                <option value="sss_one">SSS One</option>
                <option value="sss_two">SSS Two</option>
                <option value="sss_three">SSS Three</option>
            </select> 
            <span class="file-info"><small>Select Best class for this lesson</small></span>
        </div>

        <div class="form-group">
            <label for="lessonDescription">Description</label>
            <textarea class="form-control" id="lessonDescription" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="lessonThumbnail" class="file-label">
                <i class="fas fa-image"></i> Thumbnail
            </label>
            <input type="file" class="form-control-file" id="lessonThumbnail" name="thumbnail" onchange="previewThumbnail('lessonThumbnail', 'thumbnailPreview')" accept="image/*">
            <span class="file-info">Upload a thumbnail (Max size: 2MB)</span>
            <div id="thumbnailPreviewContainer" style="overflow: hidden;">
                <img id="thumbnailPreview" src="#" alt="Thumbnail Preview" style="max-width: 100%; max-height: 100%; display: none;">
            </div>
        </div>

        <div class="btn-group" role="group" aria-label="Lesson Form Buttons">
        <button type="button" class="btn btn-primary" id="submitLessonDetailsBtn" disabled >Upload Lesson</button>

    </div>
    </div>

</form>

<script>
    
   
</script>


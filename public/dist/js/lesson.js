document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleLessonForm');
    const lessonForm = document.getElementById('lessonForm');

    toggleButton.addEventListener('click', function () {
        if (lessonForm.style.display === 'none' || lessonForm.style.display === '') {
            // Show form with animation
            lessonForm.style.display = 'block';
            setTimeout(() => {
                lessonForm.style.opacity = '1';
                lessonForm.style.transform = 'translateY(0)';
            }, 10); // Delay added for smoother transition
        } else {
            // Hide form with animation
            lessonForm.style.opacity = '0';
            lessonForm.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                lessonForm.style.display = 'none';
            }, 300); // Wait for animation to finish
        }
    });
});

 // Function to trigger file input
 function triggerFileInput(inputId) {
    const fileInput = document.getElementById(inputId);
    fileInput.click();
}
const titleInput = document.getElementById('lessonTitle');
const videoInput = document.getElementById('lessonVideo');
const buttonContinue = document.getElementById('continue-btn');
let isVideoSelected = videoInput.files && videoInput.files.length > 0;
vid_no = 0;

// Function to check if both title and video are filled
function checkVideoFields() {
    const title = titleInput.value.trim();

    // Update isVideoSelected based on the current state of videoInput.files
    isVideoSelected = videoInput.files && videoInput.files.length > 0;
    if (isVideoSelected) {
        vid_no++
    }
    console.log(vid_no)

    // Enable continue button if both title and video are filled
    buttonContinue.disabled = !(title && vid_no > 0);
}

// Add event listener to title input to check for changes
titleInput.addEventListener('input', checkVideoFields);

// Add change event listener to video input to check for file selection
videoInput.addEventListener('change', checkVideoFields);

// Call checkVideoFields initially to set button state
checkVideoFields();




// Function to preview file and enable continue button if successful
function previewFile(inputId, previewId, loaderId) {
    const fileInput = document.getElementById(inputId);
    const videoPreview = document.getElementById(previewId);
    const videoLoader = document.getElementById(loaderId);
    const videoError = document.getElementById('video-error');
    const continueButton = document.querySelector('#continue-btn');
    const plusIcon = document.querySelector('#videoPlaceholder .fa-plus');
    const spinner = document.getElementById('spinner');
    const replaceVideoBtn = document.getElementById('replaceVideoButton');

    const file = fileInput.files[0];
    const reader = new FileReader();

    // Show loader spinner and clear any previous error
    videoLoader.style.display = 'block';
    videoError.innerText = '';
    plusIcon.style.display = 'none'; // Hide plus icon
    spinner.style.display = 'inline-block'; // Display spinner

    reader.onloadend = function () {
        videoLoader.style.display = 'none';

        const video = document.createElement('video');
        video.src = reader.result;

        video.onloadedmetadata = function() {
            if (video.duration <= 600) { // 10 minutes = 600 seconds
                videoPreview.src = reader.result;
                videoPreview.style.display = 'block';
                videoError.innerText = '';

                // Enable continue button
                // continueButton.disabled = false;

                // Show "Replace Video" button
                replaceVideoBtn.style.display = 'block';
            } else {
                videoError.innerText = 'This video is too long. It should not exceed 10 minutes.';
                plusIcon.style.display = 'inline-block'; // Display plus icon
                spinner.style.display = 'none'; // Hide spinner
                continueButton.disabled = true; // Disable continue button
                // Show error message for 3 seconds
                videoError.style.display = 'block';
                setTimeout(function() {
                    videoError.style.display = 'none';
                }, 6000);
            }
        };
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        // Reset preview
        videoPreview.src = '';
        videoPreview.style.display = 'none';
        videoError.innerText = '';
        plusIcon.style.display = 'inline-block'; // Display plus icon
        spinner.style.display = 'none'; // Hide spinner
        continueButton.disabled = true; // Disable continue button
    }
}

function replaceVideo() {
    // Trigger the file input field
    document.getElementById('lessonVideo').click();
}

// Function to handle replacing the video preview with a new video
function handleReplaceVideo() {
    const fileInput = document.getElementById('lessonVideo');
    const videoPreview = document.getElementById('videoPreview');
    const videoError = document.getElementById('video-error');
    const plusIcon = document.querySelector('#videoPlaceholder .fa-plus');
    const spinner = document.getElementById('spinner');
    const continueButton = document.querySelector('#continue-btn');
    const replaceVideoBtn = document.getElementById('replaceVideoButton');

    const file = fileInput.files[0];
    const reader = new FileReader();

    // Show loader spinner and clear any previous error
    spinner.style.display = 'inline-block'; // Display spinner
    videoPreview.style.display = 'none';
    videoError.innerText = '';
    plusIcon.style.display = 'none'; // Hide plus icon

    reader.onloadend = function () {
        spinner.style.display = 'none'; // Hide spinner

        const video = document.createElement('video');
        video.src = reader.result;

        video.onloadedmetadata = function() {
            if (video.duration <= 600) { // 10 minutes = 600 seconds
                // Replace the old video with the new one
                videoPreview.src = reader.result;
                videoPreview.style.display = 'block';
                videoError.innerText = '';

                // Show "Replace Video" button
                replaceVideoBtn.style.display = 'block';

                // Enable continue button
                continueButton.disabled = false;
            } else {
                videoError.innerText = 'This video is too long. It should not exceed 10 minutes.';
                plusIcon.style.display = 'inline-block'; // Display plus icon
                continueButton.disabled = true; // Disable continue button
                // Show error message for 3 seconds
                videoError.style.display = 'block';
                setTimeout(function() {
                    videoError.style.display = 'none';
                }, 6000);
            }
        };
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}


function continueClicked() {
    // Pause the video
    const videoPreview = document.getElementById('videoPreview');
    videoPreview.pause();

    // Hide the video preview container
    document.getElementById('videoPreviewContainer').style.display = 'none';

    // Display the other form fields 
    document.getElementById('otherFormFields').style.display = 'block';
    document.getElementById('span-info').textContent = 'Lesson Details';
    document.getElementById('continue-btn').style.display = 'none';
    // Corrected method name to get elements by class name
    const createNewLessonButtons = document.getElementsByClassName('create_new_lesson');
    // Loop through the elements with the class name and hide each one
    for (let i = 0; i < createNewLessonButtons.length; i++) {
        createNewLessonButtons[i].style.display = 'none';
    }
    document.getElementById('backToVideoButton').style.display = 'block';
    document.getElementById('replaceVideoButton').style.display = 'none';
}



function backToVideoPreview() {
    // Hide the other form fields
    document.getElementById('otherFormFields').style.display = 'none';
    document.getElementById('continue-btn').style.display = 'block';
    document.getElementById('span-info').textContent = 'Upload Video(Max duration: 10 minutes)';
    document.getElementById('replaceVideoButton').style.display = 'block';

    // Display the video preview container
    document.getElementById('videoPreviewContainer').style.display = 'block';

    // Hide the "Back to Video Preview" button
    document.getElementById('backToVideoButton').style.display = 'none';
}



$(document).ready(function() {
    var r = new Resumable({
        target: '/upload', // Upload endpoint URL
        chunkSize: 2 * 1024 * 1024, // 2 MB chunk size
        simultaneousUploads: 1, // Number of simultaneous uploads
        method: 'POST', // Use POST method for file upload
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    // Add file input to Resumable.js
    r.assignBrowse(document.getElementById('lessonVideo'));

    // Handle file progress events
    r.on('fileProgress', function(file) {
        var progress = Math.floor(file.progress() * 100);
        console.log('File progress:', progress + '%');

        // Update button with upload progress
        updateButtonProgress(progress);
    });

    r.on('fileSuccess', function(file, message) {
        // console.log('File upload successful:', file, message);

        // Parse the message JSON to extract lesson_id
    
            var response = JSON.parse(message);
            // console.log('Parsed Response:', response);
            var lessonId = response.lesson_id;
            document.getElementById('lesson_id').value = lessonId
            console.log(lessonId)
            hideVideoFields()
            displayOtherFormFields()

            // Handle successful upload response...
        
    });

    r.on('fileError', function(file, message) {
        console.error('File upload error:', file, message);

        // Log the exact error message from the server
        console.log('Server error message:', message);

        // Display error message to the user...
    });


    // Function to update spinner button with upload progress
    function updateButtonProgress(progress) {
        var continueBtn = document.getElementById('continue-btn');
        continueBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading... ${progress}%`;
        continueBtn.disabled = true; // Disable the button during upload
    }

    // Function to show/hide spinner during upload
    function showSpinner() {
        var continueBtn = document.getElementById('continue-btn');
        continueBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
        continueBtn.disabled = true; // Disable the button during upload
    }

    function hideSpinner() {
        var continueBtn = document.getElementById('continue-btn');
        continueBtn.innerHTML = 'Continue'; // Reset button text
        continueBtn.disabled = false; // Enable the button after upload completes
    }

    function hideVideoFields() {
        document.getElementById('videoFields').style.display = 'none';
    }

    function displayOtherFormFields() {
        document.getElementById('otherFormFields').style.display = 'block';
    }

    // Function to trigger Resumable.js upload
    function startUpload() {
        var titleInput = document.getElementById('lessonTitle');
        var title = titleInput.value.trim();
        if (!title) {
            document.getElementById('error-message').innerText = 'Enter Title for this Lesson';
            document.getElementById('error-message').style.display = 'block';
            return;
        }

        r.opts.query = { title: title };
        showSpinner(); // Show spinner during upload
        r.upload(); // Start or resume upload
    }

    // Listen for click on Continue button
    document.getElementById('continue-btn').addEventListener('click', function() {
        startUpload(); // Trigger Resumable.js upload when Continue button is clicked
    });


    // Function to handle lesson details update via AJAX
    function updateLessonDetails(lessonId, lessonDetailsData) {
        var formData = new FormData();
        formData.append('lesson_id', lessonId);
        formData.append('subject', lessonDetailsData.subject);
        formData.append('class_level', lessonDetailsData.class_level);
        formData.append('description', lessonDetailsData.description);
        if (lessonDetailsData.thumbnail) {
            formData.append('thumbnail', lessonDetailsData.thumbnail);
        }

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Disable the button and show a spinner
        $('#submitLessonDetailsBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

        $.ajax({
            url: `/lessons/${lessonId}/update-details`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('Lesson details updated successfully:', response);
                $('#success-message').text('Lesson details updated successfully');
                $('#success-message').addClass('alert alert-success').show();

                // Reload the page upon successful update
                setTimeout(function() {
                    location.reload();
                }, 2000); // Reload after 2 seconds (adjust as needed)
            },
            error: function(xhr, status, error) {
                console.error('Error updating lesson details:', error);
                $('#error-message').text('Error updating lesson details: ' + error);
                $('#error-message').addClass('alert alert-danger').show();
                
                // Re-enable the button on error
                $('#submitLessonDetailsBtn').prop('disabled', false).html('Submit Lesson Details');
            }
        });
    }

    // Listen for click on Submit Lesson Details button
    $('#submitLessonDetailsBtn').on('click', function() {
        var lessonId = document.getElementById('lesson_id').value; // Get the lesson ID
        var lessonDetailsData = {
            subject: $('#general_name').val(),
            class_level: $('#class_level').val(),
            description: $('#lessonDescription').val(),
            thumbnail: $('#lessonThumbnail')[0].files[0] || null
        };

        updateLessonDetails(lessonId, lessonDetailsData); // Trigger lesson details update via AJAX
    });

});

// Get references to form input fields and the continue button
// const titleInput = document.getElementById('lessonTitle');
const subjectInput = document.getElementById('general_name');
const classInput = document.getElementById('class_level');
const descriptionInput = document.getElementById('lessonDescription');
const thumbnailInput = document.getElementById('lessonThumbnail');
const continueButton = document.getElementById('submitLessonDetailsBtn');

// Function to check if all fields are filled
function checkFields() {
    // const title = titleInput.value.trim();
    const subject = subjectInput.value.trim();
    const classLevel = classInput.value.trim();
    const description = descriptionInput.value.trim();
    const thumbnail = thumbnailInput.files.length > 0;

    // Enable continue button if all fields are filled
    if (subject !== '' && classLevel !== '' && description !== '' && thumbnail) {
        continueButton.disabled = false;
    } else {
        continueButton.disabled = true;
    }
}

// Add event listeners to input fields to check for changes
// titleInput.addEventListener('input', checkFields);
subjectInput.addEventListener('change', checkFields);
classInput.addEventListener('change', checkFields);
descriptionInput.addEventListener('input', checkFields);
thumbnailInput.addEventListener('change', checkFields);

function previewThumbnail(inputId, previewId) {
    const fileInput = document.getElementById(inputId);
    const thumbnailPreview = document.getElementById(previewId);
    const thumbnailPreviewContainer = document.getElementById(previewId + 'Container');
    const file = fileInput.files[0];

    // Check if file exists and is an image
    if (file && file.type.startsWith('image')) {
        // Check if file size is less than or equal to 2MB
        if (file.size <= 2 * 1024 * 1024) { // 2MB in bytes
            const reader = new FileReader();
            reader.onload = function(event) {
                thumbnailPreview.src = event.target.result;
                thumbnailPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
            thumbnailPreviewContainer.style.display = 'block';
        } else {
            // Show error message if file size exceeds 2MB
            thumbnailPreviewContainer.style.display = 'none';
            alert('The selected image exceeds the maximum allowed size of 2MB.');
            // Clear the file input field
            fileInput.value = '';
        }
    } else {
        // Hide the thumbnail preview container if the file is not an image
        thumbnailPreviewContainer.style.display = 'none';
        // Clear the file input field
        fileInput.value = '';
    }
}

function removelesson(lessonId) {
    var buttonElement = $('#removeLessonBtn'); // Get the button element
    var originalHtml = buttonElement.html(); // Store the original button HTML

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Display spinner and disable button
    buttonElement.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...').prop('disabled', true);

    // Make AJAX request to remove lesson
    $.ajax({
        type: 'POST',
        url: '/remove-lesson/' + lessonId,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            // Display success message and fade out after 3 seconds
            var messageElement = $(".lesson-message");
            messageElement.text(response.message).show();

            // Fade out after 3 seconds
            setTimeout(function () {
                messageElement.fadeOut();
                location.reload(); // Reload the page after hiding the message
            }, 3000);
        },
        error: function (xhr, textStatus, errorThrown) {
            console.error(xhr.responseText);

            // Parse the JSON response to get the error message
            var errorMessage = 'Failed to remove Lesson. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            // Display error message and fade out after 3 seconds
            $(".lesson-error").text(errorMessage).show().fadeIn();
            setTimeout(function () {
                $(".lesson-error").fadeOut();
            }, 3000);
        },
        complete: function () {
            // Revert button to its original state after request completes
            buttonElement.html(originalHtml).prop('disabled', false);
        }
    });
}

   
function previewEditThumbnail(inputId, previewId) {
  const fileInput = document.getElementById(inputId);
  const thumbnailPreview = document.getElementById(previewId);
  const thumbnailPreviewContainer = document.getElementById(previewId + 'Container');
  const file = fileInput.files[0];

  // Check if file exists and is an image
  if (file && file.type.startsWith('image')) {
      // Check if file size is less than or equal to 2MB
      if (file.size <= 2 * 1024 * 1024) { // 2MB in bytes
          const reader = new FileReader();
          reader.onload = function(event) {
              const img = new Image();
              img.onload = function() {
                  // Create a canvas element to draw the resized image
                  const canvas = document.createElement('canvas');
                  const ctx = canvas.getContext('2d');

                  // Set canvas dimensions to desired size (540px width by 360px height)
                  const maxWidth = 540;
                  const maxHeight = 360;

                  let width = img.width;
                  let height = img.height;

                  // Calculate the new dimensions while maintaining aspect ratio
                  if (width > maxWidth || height > maxHeight) {
                      const ratio = Math.min(maxWidth / width, maxHeight / height);
                      width *= ratio;
                      height *= ratio;
                  }

                  canvas.width = width;
                  canvas.height = height;

                  // Draw the resized image onto the canvas
                  ctx.drawImage(img, 0, 0, width, height);

                  // Update the src attribute of the preview image with the canvas data
                  thumbnailPreview.src = canvas.toDataURL('image/jpeg'); // Set to 'image/jpeg' for better quality
                  thumbnailPreview.style.display = 'block';
              };
              img.src = event.target.result;
          };
          reader.readAsDataURL(file);
          thumbnailPreviewContainer.style.display = 'block';
      } else {
          // Show error message if file size exceeds 2MB
          thumbnailPreviewContainer.style.display = 'none';
          alert('The selected image exceeds the maximum allowed size of 2MB.');
          // Clear the file input field
          fileInput.value = '';
      }
  } else {
      // Hide the thumbnail preview container if the file is not an image
      thumbnailPreviewContainer.style.display = 'none';
      // Clear the file input field
      fileInput.value = '';
  }
}


// Function to handle opening the edit lesson modal and populating form fields
function editLessonModal(lessonId) {
$.ajax({
    type: 'GET',
    url: '/lessons-edit/' + lessonId,
    success: function(response) {
        try {
            // Populate modal form fields with lesson details
            $('#editLessonId').val(response.lesson.id);
            $('#editLessonTitle').val(response.lesson.title);
            $('#editLessonDescription').val(response.lesson.description);

            // Populate the subject dropdown with options
            var lessonSubject = response.lesson.subject;
            var $subjectSelect = $('#editLessonSubject');
            $subjectSelect.empty(); // Clear existing options

            // Add options to the subject dropdown from uniqueSubjectNames
            $.each(uniqueSubjectNames, function(index, subject) {
                var $option = $('<option></option>').attr('value', subject).text(subject);
                $subjectSelect.append($option);

                // Select the matching subject
                if (subject === lessonSubject) {
                    $option.prop('selected', true);
                }
            });

            // Populate the class_level dropdown with options
            var lessonClassLevel = response.lesson.class_level;
            var $classLevelSelect = $('#editLessonClassLevel');
            $classLevelSelect.empty(); // Clear existing options

            // Define class level options and text values
            var classLevels = {
                'primary_one': 'Primary One',
                'primary_two': 'Primary Two',
                'primary_three': 'Primary Three',
                'primary_four': 'Primary Four',
                'primary_five': 'Primary Five',
                'primary_six': 'Primary Six',
                'jss_one': 'JSS One',
                'jss_two': 'JSS Two',
                'jss_three': 'JSS Three',
                'sss_one': 'SSS One',
                'sss_two': 'SSS Two',
                'sss_three': 'SSS Three'
            };

            // Add options to the class_level dropdown
            $.each(classLevels, function(value, text) {
                var $option = $('<option></option>').attr('value', value).text(text);
                $classLevelSelect.append($option);

                // Select the matching class level
                if (value === lessonClassLevel) {
                    $option.prop('selected', true);
                }
            });

            // Display the thumbnail preview
            var thumbnailUrl = response.lesson.thumbnail;
            var $thumbnailPreview = $('#thumbnailEditPreview');

            if (thumbnailUrl) {
                $thumbnailPreview.attr('src', thumbnailUrl);
                $thumbnailPreview.show();
            } else {
                $thumbnailPreview.hide();
            }

            // Show the modal
            $('#editLessonModal').modal('show');
        } catch (error) {
            console.error('Error populating edit modal:', error);
        }
    },
    error: function(xhr, textStatus, errorThrown) {
        console.error('Failed to fetch lesson details:', textStatus, errorThrown);
        // Handle error if necessary
    }
});
}

// Event listener for "Edit" button clicks
$(document).on('click', '.edit-lesson-btn', function(e) {
e.preventDefault();
// Retrieve the lesson ID from the clicked button's data attribute
var lessonId = $(this).data('lesson-id');
// Call the editLessonModal function with the lesson ID
editLessonModal(lessonId);
});

// Event listener for saving lesson changes
$('#saveLessonChangesBtn').click(function() {
// Create a new FormData object to gather form data
var formData = new FormData();

// Append lesson_id to FormData
formData.append('lesson_id', $('#editLessonId').val());

// Append other form fields to FormData
formData.append('edit_title', $('#editLessonTitle').val());
formData.append('edit_subject', $('#editLessonSubject').val());
formData.append('edit_class_level', $('#editLessonClassLevel').val());
formData.append('edit_description', $('#editLessonDescription').val());

// Append thumbnail file to FormData if a new file is selected
var thumbnailFile = $('#lessonEditThumbnail')[0].files[0];
if (thumbnailFile) {
    formData.append('edit_thumbnail', thumbnailFile);
}

// Retrieve CSRF token from meta tag
var csrfToken = $('meta[name="csrf-token"]').attr('content');

// AJAX request to update lesson details
$.ajax({
    type: 'POST', // Use POST for file uploads
    url: '/lessons-update/' + $('#editLessonId').val(),
    data: formData,
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
    processData: false, // Prevent jQuery from processing the data
    contentType: false, // Prevent jQuery from setting the content type
    cache: false, // Disable caching
    success: function(response) {
        console.log(response);
        // Display success message
        $('#success-edit-message').text('Lesson updated successfully').fadeIn();
        setTimeout(function() {
            $('#success-edit-message').fadeOut();
            location.reload();
        }, 3000); // Fade out after 3 seconds

        // Close the modal
        // $('#editLessonModal').modal('hide');
        // Reload or update lesson card with new details (optional)
        // location.reload(); 
        // Reload the page for demonstration
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
        // Display error message
        $('#error-edit-message').text('Failed to update lesson').fadeIn();
        setTimeout(function() {
            $('#error-edit-message').fadeOut();
        }, 3000); // Fade out after 3 seconds
    }
});
});

$('.lesson-link').click(function(e) {
    e.preventDefault(); // Prevent default link behavior

    // Extract lesson details from the clicked link
    const lessonId = $(this).data('lesson-id');
    const lessonName = $(this).data('lesson-title');
    const schoolConnectsRequired = $(this).data('school-connects-required');

    // Perform AJAX request to check if user is already enrolled in the lesson
    checkLessonEnrollment(lessonId, lessonName, schoolConnectsRequired);
});

// Function to check lesson enrollment status
function checkLessonEnrollment(lessonId, lessonName, schoolConnectsRequired) {
    $.ajax({
        url: '/check-enrollment',
        method: 'POST',
        data: {
            lesson_id: lessonId
        },
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.is_enrolled) {
                // User is already enrolled in the lesson, route to lesson page
                routeToLessonPage(lessonId);
            } else {
                // User is not enrolled, display modal with required school connects information
                displaySchoolConnectsModal(lessonName, schoolConnectsRequired, lessonId);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error checking enrollment:', error);
            alert('Error checking enrollment. Please try again.');
        }
    });
}
// Function to route to lesson page
function routeToLessonPage(lessonId) {
    // Get the route URL from the data attribute
    var routeUrl = document.getElementById('lessonRoute').dataset.route;
    // Replace ':lessonId' with the actual lessonId
    var url = routeUrl.replace(':lessonId', lessonId);
    // Redirect to the constructed URL
    window.location.href = url;
}

function displayMessageAndFadeOut(messageElementId, message, duration) {
    const messageElement = $(`#${messageElementId}`);
    messageElement.text(message).fadeIn();
    setTimeout(() => {
        messageElement.fadeOut();
        displayConnectsSelectionForm();
    }, duration);
}

// Function to display the connects selection form and hide the modal footer
// function displayConnectsSelectionForm() {
//     $('#connectsForm').show(); // Show the connects form
//     $('#conect-modal-footer').hide(); // Hide the modal footer
//     $('#schoolConnectsModal').modal('show'); // Show the modal
// }

// Function to display school connects modal
function displaySchoolConnectsModal(lessonName, requiredConnects, lessonId) {
    $('#lessonName').text(lessonName);
    $('#requiredConnects').text(requiredConnects);
    $('#schoolConnectsModal').modal('show');

    // Handle click event for confirm play button
    $('#confirmPlayBtn').off('click').on('click', function() {
        const selectedConnectsAmount = $('#connectsAmount').val(); // Get selected connects amount
        console.log(selectedConnectsAmount)
        buySchoolConnects(lessonId, requiredConnects, selectedConnectsAmount);
    });
}

// Function to buy school connects
function buySchoolConnects(lessonId, requiredConnects, selectedConnectsAmount) {
    $.ajax({
        url: '/check-school-connects',
        method: 'POST',
        data: {
            lesson_id: lessonId,
            required_connects: requiredConnects
        },
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.has_enough_connects) {
                // User has enough school connects, route to lesson page
                routeToLessonPage(lessonId);
            } else {
                displayMessageAndFadeOut('connects-error', 'You do not have enough connects.', 3000);
                
            }
        },
        error: function(xhr, status, error) {
            console.error('Error checking school connects:', error);
            $('#connects-error').text('Error checking school connects. Please try again.').fadeIn();
            setTimeout(function() {
                $('#connects-error').fadeOut();
            }, 3000);
        }
    });
}
function displayConnectsSelectionForm() {
    $('#connectsForm').show()
}

// Attach event handler using event delegation
$(document).on('click', '#confirmBuyConnectsBtn', function() {
    const selectedConnectsAmount = $('#connectAmount').val(); // Get selected connects amount
    console.log('Selected Connects Amount:', selectedConnectsAmount);

    if (selectedConnectsAmount) {
        buyConnects(selectedConnectsAmount); // Call buyConnects function with selected amount
    } else {
        console.error('Selected Connects Amount is empty or invalid');
    }
});
$(document).on('click', '#confirmBuySucessConnectsBtn', function() {
    const connectsAmountSuccess = $('#connectsAmountSuccess').val(); // Get selected connects amount
    console.log('Selected Connects Amount:', connectsAmountSuccess);

    if (connectsAmountSuccess) {
        buyConnects(connectsAmountSuccess); // Call buyConnects function with selected amount
    } else {
        console.error('Selected Connects Amount is empty or invalid');
    }
});

// Function to handle buying connects via AJAX
function buyConnects(selectedConnectsAmount) {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Perform AJAX request to buy more connects with selected price value
    $.ajax({
        url: buyConnectsRoute,
        method: 'POST',
        data: {
            amount: selectedConnectsAmount,
            _token: csrfToken
        },
        success: function(response) {
            // Handle success response
            console.log('Buy Connects Response:', response); // Log the response for debugging

            if (response && response.redirect_url) {
                window.location.href = response.redirect_url; // Redirect to the specified URL
            } else {
                console.error('Invalid response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error buying connects:', error);
        }
    });
}




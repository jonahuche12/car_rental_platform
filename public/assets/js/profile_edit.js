$(document).ready(function() {
    $(".ion-edit").on("click", function() {
        // Toggle the display of the input field
        var editField = $(this).closest("li").find(".edit-field");
        editField.toggle();

        // Set display to inline-block if the edit-field is visible
        if (editField.is(":visible")) {
            editField.css("display", "block");
        }
    });
});


function saveData(field) {
    

    // Check if the field is user_package
    if (field === "user_package" ) {
        // Display the confirmation modal
        $('#userPackageModal').modal('show');
        return;
    }

    if (field === "school") {
        // Display the confirmation modal
        $('#schoolEditModal').modal('show');
        return;
    }
    if (field === "class") {
        // Display the confirmation modal
        $('#schoolClassModal').modal('show');
        return;
    }

    // Rest of your existing saveData function code...
    var data = {};

    // Check if it's a location field
    if (field == "address") {
        // Update the data object with the location fields
        data['country'] = $("#country_input").val();
        data['state'] = $("#state_input").val();
        data['city'] = $("#city_input").val();
        data['address'] = $("#address_field_input").val();
    } else if (field == "gender") {
        // Handle gender field
        data['gender'] = $("#gender_input").val();
    } else if (field == "date_of_birth") {
        // Handle date of birth field
        data['date_of_birth'] = $("#date_of_birth_input").val();
    } 
    // else if (field == "school") {
    //     // Handle school field
    //     data['school_id'] = $("#school_input").val();
    // } 
    else {
        // Get the value from the input field based on the field parameter
        data[field] = $("#" + field + "_input").val();
    }
    console.log(data);

    // Include the CSRF token in the headers
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send the data to the server using AJAX
    $.ajax({
        type: "POST",
        url: "/update-profile", // Update this URL to match your route endpoint
        data: data,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            // Handle success, update UI or perform any additional tasks
            console.log(response);

            // Hide the field and button with fade animation
            $("#" + field + "_input").fadeOut();
            $("#school_search").fadeOut();

            if (response.hide_button) {
                $("#" + field + "-button").fadeOut();
            }

            // Display success message with fade animation
            $("." + field + "-message").text(response.message).fadeIn();

            // Update the edit icon with the response data
            $("#" + field + "-icon").replaceWith(response.new_icon);
            $("#" + field + "_data").text('');

            // Update the edit icon with the response data after 3 seconds
            setTimeout(function() {
                $("." + field + "-message").fadeOut();
            }, 3000);
        },

        error: function(xhr, status, error) {
            // Handle error, show a message to the user, etc.
            console.error(error);
            console.error(xhr.responseText);

            // Display specific error message from the server with fade animation
            $("." + field + "-error").text("Failed to update. Please try again.").fadeIn();

            // Hide the error message after 3 seconds with fade animation
            setTimeout(function() {
                $("." + field + "-error").fadeOut();
            }, 3000);

            location.reload()
        }
    });
}

// Function to proceed with the user_package update
function proceedWithUserPackage() {
    // Close the modal
    $('#userPackageModal').modal('hide');

    // Continue with the user_package update
    var field = "user_package";

    // Get the selected user_package value from the modal (assuming you have an input/select with id="userPackageSelect")
    var userPackageValue = $('#user_package_input').val();

    // Validate if the user has selected a package
    if (!userPackageValue) {
        // Display an error message or handle the situation where no package is selected
        console.error("Please select a user package.");
        return;
    }

    // Rest of your existing saveData function code...
    var data = {};
    data[field] = userPackageValue;
    console.log(data);

    // Include the CSRF token in the headers
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send the data to the server using AJAX
    $.ajax({
        type: "POST",
        url: "/update-profile", // Update this URL to match your route endpoint
        data: data,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            // Handle success, update UI or perform any additional tasks
            console.log(response);

            // Hide the field and button with fade animation
            $("#" + field + "_input").fadeOut();
            $("#school_search").fadeOut();

            if (response.hide_button) {
                $("#" + field + "-button").fadeOut();
            }

            // Display success message with fade animation
            $("." + field + "-message").text(response.message).fadeIn();

            // Update the edit icon with the response data
            $("#" + field + "-icon").replaceWith(response.new_icon);
            $("#" + field + "_data").text('');

            // Update the edit icon with the response data after 3 seconds
            setTimeout(function() {
                $("." + field + "-message").fadeOut();
            }, 3000);
        },

        error: function(xhr, status, error) {
            // Handle error, show a message to the user, etc.
            console.error(error);
            console.error(xhr.responseText);

            // Display specific error message from the server with fade animation
            $("." + field + "-error").text("Failed to update. Please try again.").fadeIn();

            // Hide the error message after 3 seconds with fade animation
            setTimeout(function() {
                $("." + field + "-error").fadeOut();
            }, 3000);
        }
    });
}

function proceedWithSchoolUpdate() {
    // Close the modal
    $('#schoolEditModal').modal('hide');

    // Continue with the user_package update
    var field = "school_id";
    console.log(field)

    // Get the selected user_package value from the modal (assuming you have an input/select with id="userPackageSelect")
    var schoolValue = $('#school_input').val();

    // Validate if the user has selected a package
    if (!schoolValue) {
        // Display an error message or handle the situation where no package is selected
        console.error("Please select a School.");
        return;
    }

    // Rest of your existing saveData function code...
    var data = {};
    data[field] = schoolValue;
    console.log(data);

    // Include the CSRF token in the headers
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send the data to the server using AJAX
    $.ajax({
        type: "POST",
        url: "/update-profile", // Update this URL to match your route endpoint
        data: data,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            // Handle success, update UI or perform any additional tasks
            console.log(response);

            // Hide the field and button with fade animation
            $("#" + field + "_input").fadeOut();
            $("#school_search").fadeOut();

            if (response.hide_button) {
                $("#" + field + "-button").fadeOut();
            }

            // Display success message with fade animation
            $("." + field + "-message").text(response.message).fadeIn();

            // Update the edit icon with the response data
            $("#" + field + "-icon").replaceWith(response.new_icon);
            $("#" + field + "_data").text('');

            // Update the edit icon with the response data after 3 seconds
            setTimeout(function() {
                $("." + field + "-message").fadeOut();
            }, 3000);
            location.reload()
        },

        error: function(xhr, status, error) {
            // Handle error, show a message to the user, etc.
            console.error(error);
            console.error(xhr.responseText);

            // Display specific error message from the server with fade animation
            $("." + field + "-error").text("Failed to update. Please try again.").fadeIn();

            // Hide the error message after 3 seconds with fade animation
            setTimeout(function() {
                $("." + field + "-error").fadeOut();
            }, 3000);
        }
    });
}

function proceedWithClassUpdate() {
    // Close the modal
    $('#schoolClassModal').modal('hide');

    // Continue with the user_package update
    var field = "class_id";

    // Get the selected user_package value from the modal (assuming you have an input/select with id="userPackageSelect")
    var classValue = $('#class_input').val();

    // Validate if the user has selected a package
    if (!classValue) {
        // Display an error message or handle the situation where no package is selected
        console.error("Please select a Class.");
        return;
    }

    // Rest of your existing saveData function code...
    var data = {};
    data[field] = classValue;
    console.log(data);

    // Include the CSRF token in the headers
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send the data to the server using AJAX
    $.ajax({
        type: "POST",
        url: "/update-profile", // Update this URL to match your route endpoint
        data: data,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            // Handle success, update UI or perform any additional tasks
            console.log(response);

            // Hide the field and button with fade animation
            $("#" + field + "_input").fadeOut();
            $("#school_search").fadeOut();

            if (response.hide_button) {
                $("#" + field + "-button").fadeOut();
            }

            // Display success message with fade animation
            $("." + field + "-message").text(response.message).fadeIn();

            // Update the edit icon with the response data
            $("#" + field + "-icon").replaceWith(response.new_icon);
            $("#" + field + "_data").text('');

            // Update the edit icon with the response data after 3 seconds
            setTimeout(function() {
                $("." + field + "-message").fadeOut();
            }, 3000);
            location.reload()
        },

        error: function(xhr, status, error) {
            // Handle error, show a message to the user, etc.
            console.error(error);
            console.error(xhr.responseText);

            // Display specific error message from the server with fade animation
            $("." + field + "-error").text("Failed to update. Please try again.").fadeIn();

            // Hide the error message after 3 seconds with fade animation
            setTimeout(function() {
                $("." + field + "-error").fadeOut();
            }, 3000);
        }
    });
}


$(document).ready(function() {
    $('#profile-image-input').change(function() {
        var formData = new FormData();
        formData.append('profile_picture', $(this)[0].files[0]);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: 'POST',
            url: '/update-profile-picture', // Replace with your server endpoint
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // Handle success, update UI or perform any additional tasks
                console.log(response);
                $("#profile-picture").attr('src', response.new_profile_picture);

                // Display success message with fade animation
                $('.validation-error').html('').fadeOut();
                $('.success-message').html('Profile picture updated successfully').fadeIn();

                // Remove success message with fade animation after 3 seconds
                setTimeout(function() {
                    $('.success-message').fadeOut();
                }, 3000);
            },
            error: function(error) {
                // Handle error, show a message to the user, etc.
                console.error(error);

                // Display validation errors if available
                if (error.responseJSON && error.responseJSON.errors) {
                    var errorMessage = '';
                    $.each(error.responseJSON.errors, function(key, value) {
                        errorMessage += value + '<br>';
                    });

                    // Display error message with fade animation
                    $('.validation-error').html(errorMessage).fadeIn();
                } else {
                    $('.validation-error').html('').fadeOut();
                }

                // Hide success message with fade animation
                $('.success-message').html('').fadeOut();
            }
        });
    });
});

$(document).ready(function () {
    // Create a variable to store the timeout for delaying the AJAX request
    var typingTimer;
    var doneTypingInterval = 500; // in milliseconds (adjust as needed)

    // Create a container to hold the dynamically created list of schools
    var schoolListContainer = $("<div>").attr("id", "school_list_container").hide();
    $("#school_search").after(schoolListContainer);

    // Listen for input changes on the school search input
    $("#school_search").on("input", function () {
        // Clear the timeout if it exists
        clearTimeout(typingTimer);

        // Get the entered value
        var query = $(this).val();

        // Show/hide elements based on user input
        if (query.trim() === "") {
            $("#school_input").show();
            $("#school_list_container").hide();
        } else {
            $("#school_input").hide();
            $("#school_list_container").show();
        }

        // Set a timeout to wait for the user to stop typing
        typingTimer = setTimeout(function () {
            // Perform AJAX request to fetch matching schools
            $.ajax({
                type: "GET",
                url: "/search-schools", // Adjust the URL to match your route
                data: { query: query },
                success: function (response) {
                    // Clear the existing list of schools
                    schoolListContainer.empty();

                    // Append each school to the dynamically created list
                    $.each(response.schools, function (index, school) {
                        var schoolItem = $("<div>").text(school.name).addClass("school_item");

                        // Add a click handler to set the selected school to #school_input and hide the list container
                        schoolItem.click(function () {
                            $("#school_search").val(school.name); // Set the name of the selected school
                            $("#school_input").val(school.id); // Set the ID of the selected school (if needed)
                            // $("#school_input").show();
                            $("#school_list_container").hide();
                        });
                        

                        schoolListContainer.append(schoolItem);
                    });
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }, doneTypingInterval);
    });
});

function submitQualification() {
    // Serialize the form data as a URL-encoded string
    var formData = $("#qualificationForm").serialize();
    console.log(formData);

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send the data to the server using AJAX
    $.ajax({
        type: "POST",
        url: "/qualifications",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            // Handle success, update UI or perform any additional tasks
            console.log(response);

            // Hide the form and button with fade animation
            $(".qualification-form-container").fadeOut();

            // Display success message with fade animation
            $(".qualification-message").text("Qualification submitted successfully.").fadeIn();

            // Hide the success message after 3 seconds with fade animation
            setTimeout(function () {
                $(".qualification-message").fadeOut();
            }, 3000);

            // Add a new <li> to the list with the new qualification data
            var newLi = $("<li>").addClass("list-group-item").html("<b class='text-info'>" + response.data.certificate + ", " + response.data.starting_year + " to " + response.data.completion_year + "</b>");
            $(".list-group").append(newLi);
        },
        error: function (xhr, status, error) {
            // Handle error, show a message to the user, etc.
            console.error(error);
            console.error(xhr.responseText);

            // Display a specific error message from the server with fade animation
            $(".qualification-error").text("Failed to update. Please try again.").fadeIn();

            // Hide the error message after 3 seconds with fade animation
            setTimeout(function () {
                $(".qualification-error").fadeOut();
            }, 3000);
        }
    });
}

let isFormVisible = false; // Initial state of the form visibility
const plusButtonIcon = document.getElementById('plusButton');
const formContainer = document.querySelector('.qualification-form-container');

function toggleForm() {
    // Toggle the visibility of the form container
    if (isFormVisible) {
        formContainer.style.display = 'none';
        plusButtonIcon.classList.remove('fa-minus');
        plusButtonIcon.classList.add('fa-plus');
    } else {
        formContainer.style.display = 'block';
        plusButtonIcon.classList.remove('fa-plus');
        plusButtonIcon.classList.add('fa-minus');
    }

    isFormVisible = !isFormVisible;
}


$(document).ready(function() {
    // Add click event for the toggle button
    $('.toggle-details-btn').on('click', function() {
        var targetId = $(this).data('target');
        $('#' + targetId).slideToggle(300); // Adjust the speed (300 milliseconds in this example)

        // Toggle the chevron icon
        $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
    });
});






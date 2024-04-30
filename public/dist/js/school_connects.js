$(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#getFreeConnects').click(function() {
        $.ajax({
            url: "/credit-school-connects",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.message) {
                    $('#successMessage').text(response.message);
                    $('#modalLink').show().text('Buy More Connects');
                    
                    $('#successModal').modal('show');

                    // Fade out the success message after 3 seconds
                    setTimeout(function() {
                        $('#successMessage').fadeOut(); // Hide the success message
                        $('.successForm').show(); // Display the form
                    }, 3000); // 3 seconds delay (3000 milliseconds)
                } 
                if (response.error_message) {
                    $('.errorMessage').text(response.error_message);
                    if (response.link === 'buy_package') {
                        $('#errorModalSuccessLink').attr('href', buyPackageRoute).text('Update Package').fadeIn(); // fadeIn the link
                    } else if (response.link === 'profile') {
                        console.log($('#errorSuucessMessage').text())
                        $('#buyConnectForm').hide()
                        $('#errorModalLink').attr('href', profileRoute).text('Go to Profile') // fadeIn the link
                    } else {
                        $('#errorModalLink').text('Buy More Connects').fadeIn(); // fadeIn the link
                    }
                    $('#errorModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                var errorMessage = "The system encountered an error.";
                $('#errorMessage').text(errorMessage).fadeIn(); // fadeIn the error message
                $('#errorModalLink').attr('href', contactSupportRoute).text('Click here to contact support').fadeIn(); // fadeIn the link
                $('#errorModal').modal('show');
            }
        });
    });
});

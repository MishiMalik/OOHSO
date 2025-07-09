<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>OOHSO - Coming Soon</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-black">
    <div class="container">
        <div class="coming-soon-content">
            <h1 class="coming-soon">COMING SOON</h1>
            <div class="logo">
                <img src="images/logo-crop.gif" alt="OOHSO Logo" class="img-fluid">
            </div>
            <p class="first-to-know">BE THE FIRST TO KNOW WHEN WE GO LIVE</p>
            <form id="signupForm" class="signup-form">
                <div class="input-container">
                    <input type="email" id="emailInput" name="email" placeholder="Enter your email" aria-label="Email address" required>
                    <div id="errorContainer" class="error-container"></div>
                </div>
                <button type="submit" id="submitButton">SIGN UP</button>
                <div id="message" class="message"></div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const $form = $('#signupForm');
            const $emailInput = $('#emailInput');
            const $message = $('#message');
            const $errorContainer = $('#errorContainer');
            const $submitButton = $('#submitButton'); // Function to display error messages
            function showError(message, details = '') {
                $message.removeClass('success').addClass('error').html(message);
                $errorContainer.hide(); // Always hide the error container first

                // Only show details if they exist and are different from the main message
                if (details && details !== message) {
                    $errorContainer.html(details).show();
                }
            }

            // Function to display success message
            function showSuccess(message) {
                $message.removeClass('error').addClass('success').html(message);
                $errorContainer.hide();
                $emailInput.val(''); // Clear input on success
            }

            // Email validation function
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            $form.on('submit', function(e) {
                e.preventDefault();

                const email = $emailInput.val().trim();

                // Reset messages
                $message.removeClass('success error').text('');
                $errorContainer.hide();

                // Client-side validation
                if (!email) {
                    showError('Please enter your email address.');
                    $emailInput.focus();
                    return;
                }

                if (!isValidEmail(email)) {
                    showError('Please enter a valid email address.');
                    $emailInput.focus();
                    return;
                }

                // Disable the button to prevent multiple submissions
                $submitButton.prop('disabled', true).text('SENDING...');

                // Submit email via AJAX
                $.ajax({
                    url: 'process_signup.php',
                    type: 'POST',
                    data: {
                        email: email
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showSuccess(response.message);
                        } else {
                            // Show the main error message
                            showError(response.message, response.error_details || '');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Log the error for debugging
                        console.error('AJAX Error:', textStatus, errorThrown);
                        console.log('Response:', xhr.responseText);

                        // Try to parse the JSON response
                        try {
                            const response = JSON.parse(xhr.responseText);
                            showError(response.message, response.error_details || '');
                        } catch (e) {
                            // If we can't parse the response, provide more detailed error
                            if (xhr.status === 0) {
                                showError('Unable to connect to the server. Please check your internet connection.');
                            } else if (xhr.status === 404) {
                                showError('The requested page was not found (404).');
                            } else if (xhr.status === 500) {
                                showError('Internal server error (500). Please try again later.');
                            } else {
                                showError('An error occurred: ' + textStatus + '. Please try again later.');
                            }
                        }
                    },
                    complete: function() {
                        // Re-enable the button
                        $submitButton.prop('disabled', false).text('SIGN UP');
                    }
                });
            });

            // Clear error message when user starts typing
            $emailInput.on('input', function() {
                $errorContainer.hide();
                if ($message.hasClass('error')) {
                    $message.text('');
                }
            });
        });
    </script>
</body>

</html>
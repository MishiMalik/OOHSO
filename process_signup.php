<?php
header('Content-Type: application/json');

// Include database configuration
require_once 'db_config.php';

// Validate email
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address',
        'error_code' => 'invalid_email',
        'error_details' => 'The email format is invalid. Please use a standard email format (e.g., example@domain.com).'
    ]);
    exit;
}

try {
    // Connect to database using the function from db_config.php
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Insert email into database
    $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (:email)");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Thank you for signing up!']);
} catch (PDOException $e) {
    // Check if error is due to duplicate email
    if ($e->getCode() == 23000) {
        // Duplicate entry error
        http_response_code(409); // Conflict
        echo json_encode([
            'success' => false,
            'message' => 'This email is already registered',
            'error_code' => 'duplicate_email'
        ]);
    } else {
        // Log the detailed error for administrators
        error_log('Database error in signup process: ' . $e->getMessage());

        // Return a user-friendly error
        http_response_code(500); // Internal Server Error
        echo json_encode([
            'success' => false,
            'message' => 'Unable to complete registration. Please try again later.',
            'error_code' => 'database_error'
        ]);
    }
} catch (Exception $e) {
    // General errors
    error_log('General error in signup process: ' . $e->getMessage());

    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again later.',
        'error_code' => 'general_error'
    ]);
}

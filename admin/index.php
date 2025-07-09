<?php
// Admin authentication and dashboard
require_once '../db_config.php';
session_start();

// Check if user is logged out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Check if user is already logged in
if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true) {
    // Show admin dashboard
    include 'dashboard.php';
    exit;
}

// Process login
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        // Process login
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $error = "Please enter both email and password.";
        } else {
            try {
                $conn = getDbConnection();
                if (!$conn) {
                    throw new Exception('Database connection failed');
                }

                // Get user from database
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
                $stmt->bindParam(':email', $_POST['email']);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify credentials
                if ($user && password_verify($_POST['password'], $user['password'])) {
                    // Set session variables
                    $_SESSION['admin_loggedin'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_name'] = $user['name'] ?? 'Admin User';

                    // Redirect to dashboard
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Invalid email or password.";
                }
            } catch (Exception $e) {
                $error = "Login failed. Please try again later.";
                error_log("Admin login error: " . $e->getMessage());
            }
        }
    }
}

// Show login form by default
include 'login_form.php';

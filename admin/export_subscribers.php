<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Include database configuration
require_once '../db_config.php';

try {
    // Connect to database using the function from db_config.php
    $conn = getDbConnection();
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Get subscribers with explicitly formatted date
    $stmt = $conn->query("SELECT id, email, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') AS formatted_date FROM subscribers ORDER BY created_at DESC");
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="oohso_subscribers_' . date('Y-m-d') . '.csv"');

    // Create a file pointer
    $output = fopen('php://output', 'w');

    // Set column headers
    fputcsv($output, ['ID', 'Email', 'Signup Date']);

    // Output each subscriber
    foreach ($subscribers as $subscriber) {
        // Use the explicitly formatted date from the SQL query
        $formattedDate = isset($subscriber['formatted_date']) ? $subscriber['formatted_date'] : '';

        // Only output the specific columns we need
        fputcsv($output, [
            $subscriber['id'],
            $subscriber['email'],
            $formattedDate
        ]);
    }

    // Close the file pointer
    fclose($output);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

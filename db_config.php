<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'oohso';

// Function to get PDO database connection
function getDbConnection() {
    global $host, $user, $pass, $dbname;

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Log error or handle it appropriately
        error_log('Database connection failed: ' . $e->getMessage());
        return false;
    }
}

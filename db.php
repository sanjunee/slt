<?php
$host = 'localhost'; // WAMP server
$dbname = 'slt_feedback_db'; // Database name
$username = 'root'; // Default username for WAMP
$password = ''; // Default password for WAMP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
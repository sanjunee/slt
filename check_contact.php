<?php
$host = 'localhost'; // WAMP server
$dbname = 'slt_feedback_db'; // Database name
$username = 'root'; // Default username for WAMP
$password = ''; // Default password for WAMP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $contact = $_POST['contact'];

        // Check if the mobile number already exists
        $stmt = $conn->prepare("SELECT * FROM feedback WHERE contact = :contact");
        $stmt->bindParam(':contact', $contact);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo 'exists';
        } else {
            echo 'not_exists';
        }
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
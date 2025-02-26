<?php
// Database configuration
$host = 'localhost'; // WAMP server
$dbname = 'slt_feedback_db'; // Database name
$username = 'root'; // Default username for WAMP
$password = ''; // Default password for WAMP

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize form data
        $name = htmlspecialchars($_POST['name']);
        $address = htmlspecialchars($_POST['address']);
        $contact = htmlspecialchars($_POST['contact']);
        $slt_usage = htmlspecialchars($_POST['slt_usage']);
        $products = htmlspecialchars($_POST['products'] ?? '');
        $other_products = htmlspecialchars($_POST['other_products'] ?? '');
        $connection = htmlspecialchars($_POST['connection']);
        $connection_products = htmlspecialchars($_POST['connection_products'] ?? '');

        // Validate contact number (10 digits)
        if (!preg_match('/^\d{10}$/', $contact)) {
            die("<script>alert('Invalid contact number. Please enter a 10-digit number.'); window.history.back();</script>");
        }

        // Check if the mobile number already exists
        $stmt = $conn->prepare("SELECT * FROM feedback WHERE contact = :contact");
        $stmt->bindParam(':contact', $contact);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Mobile number already exists
            echo "<script>alert('Mobile number already exists!'); window.history.back();</script>";
        } else {
            // Insert new record with current timestamp
            $stmt = $conn->prepare("INSERT INTO feedback (name, address, contact, slt_usage, products, other_products, connection, connection_products, submission_datetime) 
                                    VALUES (:name, :address, :contact, :slt_usage, :products, :other_products, :connection, :connection_products, NOW())");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':slt_usage', $slt_usage);
            $stmt->bindParam(':products', $products);
            $stmt->bindParam(':other_products', $other_products);
            $stmt->bindParam(':connection', $connection);
            $stmt->bindParam(':connection_products', $connection_products);
            $stmt->execute();

            // Success message and redirect
            echo "<script>alert('Feedback submitted successfully!'); window.location.href = 'index.html';</script>";
        }
    }
} catch (PDOException $e) {
    // Handle database errors
    die("<script>alert('Database error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>");
}
?>
<?php
// Set session duration to 1 minute (60 seconds) BEFORE starting the session
ini_set('session.gc_maxlifetime', 60);
session_set_cookie_params(60);

// Start the session
session_start();

// Database connection details
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "slt_feedback_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mobileNumber'])) {
        $countryCode = $_POST['countryCode'];
        $mobileNumber = $_POST['mobileNumber'];

        // Validate Sri Lankan mobile number (9 digits)
        if (strlen($mobileNumber) !== 9 || !preg_match("/^[1-9]\d{8}$/", $mobileNumber)) {
            die("Invalid Sri Lankan mobile number.");
        }

        $fullNumber = $countryCode . $mobileNumber;

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['fullNumber'] = $fullNumber;
        $_SESSION['expire_time'] = time() + 60; // Set session expiration time to 1 minute from now

        // Insert OTP and mobile number into the database
        $currentDateTime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO users (mobile_number, country_code, otp, date, time) 
                VALUES ('$mobileNumber', '$countryCode', '$otp', '$currentDateTime', '$currentDateTime')";

        if ($conn->query($sql) === TRUE) {
            // Display the OTP on the screen for testing
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>OTP Generated</title>
                <link rel='stylesheet' href='styles2.css'>
            </head>
            <body>
            
                <div class='container'>
                    <h1>OTP Generated</h1>
                    <p>OTP sent to <strong>$fullNumber</strong>: <strong>$otp</strong></p>
                    <p>Use this OTP to verify on the next page.</p>
                    <a href='otp_verification.php'><button>Go to OTP Verification</button></a>
                </div>
            </body>
            </html>";
        } else {
            die("Error storing OTP in the database: " . $conn->error);
        }
        exit();
    } elseif (isset($_POST['resend'])) {
        // Resend OTP logic
        if (isset($_SESSION['fullNumber'])) {
            // Generate a new OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['expire_time'] = time() + 60; // Reset expiration time to 1 minute from now

            // Get the mobile number and country code from the session
            $fullNumber = $_SESSION['fullNumber'];
            $countryCode = substr($fullNumber, 0, 3); // Extract country code
            $mobileNumber = substr($fullNumber, 3); // Extract mobile number

            // Update the database with the new OTP
            $currentDateTime = date("Y-m-d H:i:s");
            $sql = "UPDATE users SET otp = '$otp', date = '$currentDateTime', time = '$currentDateTime' 
                    WHERE mobile_number = '$mobileNumber' AND country_code = '$countryCode'";

            if ($conn->query($sql) === TRUE) {
                // Display the new OTP on the screen for testing
                echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>OTP Resent</title>
                    <link rel='stylesheet' href='styles2.css'>
                </head>
                <body>
                
                    <div class='container'>
                        <h1>OTP Resent</h1>
                        <p>New OTP sent to <strong>$fullNumber</strong>: <strong>$otp</strong></p>
                        <p>Use this OTP to verify on the next page.</p>
                        <a href='otp_verification.php'><button>Go to OTP Verification</button></a>
                    </div>
                </body>
                </html>";
            } else {
                die("Error updating OTP in the database: " . $conn->error);
            }
            exit();
        } else {
            die("Session not found. Please start over.");
        }
    } elseif (isset($_POST['otp'])) {
        // Validate OTP
        if ($_POST['otp'] == $_SESSION['otp']) {
            // Clear session data
            session_unset();
            session_destroy();

            // Redirect to form.html
            header("Location: form.html");
            exit();
        } else {
            echo "Invalid OTP. Please try again.";
        }
    }
}

// Close the database connection
$conn->close();
?>
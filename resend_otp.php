<?php
// Set session duration to 1 minute (60 seconds) BEFORE starting the session
ini_set('session.gc_maxlifetime', 60);
session_set_cookie_params(60);

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['fullNumber'])) {
        // Generate a new OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['expire_time'] = time() + 60; // Reset expiration time to 1 minute from now

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
                <p>New OTP sent to <strong>{$_SESSION['fullNumber']}</strong>: <strong>$otp</strong></p>
                <p>Use this OTP to verify on the next page.</p>
                <a href='otp_verification.php'><button>Go to OTP Verification</button></a>
            </div>
        </body>
        </html>";
        exit();
    } else {
        die("Session not found. Please start over.");
    }
}
?>
<?php
session_start();

// Debugging: Print session data
"<pre>";
($_SESSION);
"</pre>";

// Check if the session has expired
$sessionExpired = false;
if (!isset($_SESSION['expire_time']) || time() > $_SESSION['expire_time']) {
    $sessionExpired = true;
}

$errorMessage = ""; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['otp'])) {
        // Validate OTP
        if (!$sessionExpired && $_POST['otp'] == $_SESSION['otp']) {
            // Clear session data
            session_unset();
            session_destroy();

            // Redirect to form.html
            header("Location: form.html");
            exit();
        } else {
            $errorMessage = "Invalid OTP. Please try again."; // Set error message
        }
    }
}

$remainingTime = isset($_SESSION['expire_time']) ? $_SESSION['expire_time'] - time() : 0; // Calculate remaining time in seconds
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="styles2.css">
    <script>
        // JavaScript countdown timer
        let timeLeft = <?php echo $remainingTime; ?>; // Remaining time in seconds

        function startCountdown() {
            const timerElement = document.getElementById('countdown');
            const resendButton = document.getElementById('resendButton');
            const otpInput = document.getElementById('otp');
            const submitButton = document.getElementById('submitBtn');

            const interval = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    timerElement.innerHTML = "Time's up! Session expired.";
                    resendButton.style.display = 'block'; // Show resend button
                    otpInput.disabled = true; // Disable OTP input
                    submitButton.disabled = true; // Disable submit button
                } else {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerElement.innerHTML = `Time remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`;
                    timeLeft--;
                }
            }, 1000);
        }

        // Start the countdown when the page loads
        window.onload = startCountdown;
    </script>
</head>
<body>
<video autoplay muted loop id="bgVideo">
        <source src="bg1.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <h1>OTP Verification</h1>
        <p>Enter the OTP sent to <strong><?php echo $_SESSION['fullNumber']; ?></strong></p>
        <p id="countdown"></p> <!-- Countdown timer will be displayed here -->
        <?php
        // Display error message if OTP is invalid
        if (!empty($errorMessage)) {
            echo "<div class='error-message'>$errorMessage</div>";
        }
        ?>
        <form method="POST" action="">
            <input type="text" id="otp" name="otp" placeholder="Enter OTP" required><br/><br/>
            <button type="submit" id="submitBtn">Verify OTP</button>
        </form>
        <br/>
        <!-- Resend OTP button (hidden by default) -->
        <form method="POST" action="resend_otp.php" style="display: <?php echo $sessionExpired ? 'block' : 'none'; ?>;" id="resendForm">
            <button type="submit" id="resendButton">Resend OTP</button>
        </form>
        <br/>
        <?php
        if ($sessionExpired) {
            echo "<p style='color: red;'>Session expired. Please resend the OTP.</p>";
        }
        ?>
    </div>
</body>
</html>
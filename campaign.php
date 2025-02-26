<h1?php
// campaign.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Page</title>
    <link rel="stylesheet" href="style3.css">
    <!-- Include QRCode.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    </head>
<body>

<h1><center>Sri Lanka Telecom</center></h1>

    <div class="container">
        <h2>Scan the QR Code</h2>
        <div id="qrcode"></div>
    </div>

    <!-- Script to generate QR code -->
    <script>
        // Function to generate QR code
        function generateQRCode() {
            // URL you want the QR code to link to
            const url = "http://localhost/slt_feedback/number.html";

            // Create a new QRCode instance
            new QRCode(document.getElementById("qrcode"), {
                text: url,  // The URL to encode in the QR code
                width: 200, // Width of the QR code
                height: 200 // Height of the QR code
            });
        }

        // Call the function to generate the QR code when the page loads
        window.onload = generateQRCode;
    
    </script><br><br>
    <div>
     <center><a href="index.html" class="btn campaign-btn">Back to Home</a></center><br/><br/>
     <center><a href="number.html" class="btn campaign-btn">Mobile number</a></center>
     </div>
</body>

</html>
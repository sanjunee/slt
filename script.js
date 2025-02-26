// Initialize intl-tel-input
const input = document.querySelector("#mobileNumber");
const error = document.querySelector("#error");
const iti = window.intlTelInput(input, {
    initialCountry: "lk", // Default country (Sri Lanka)
    separateDialCode: true, // Show country code separately
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js", // Utility script
});

// Restrict input to 9 digits and only allow numbers
input.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "").slice(0, 9); // Allow only digits and limit to 9
});

// Handle form submission
document.getElementById("mobileForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const countryCode = iti.getSelectedCountryData().dialCode;
    const mobileNumber = input.value.replace(/\D/g, ""); // Remove non-numeric characters

    // Validate Sri Lankan mobile number (9 digits)
    if (mobileNumber.length !== 9 || !/^[1-9]\d{8}$/.test(mobileNumber)) {
        error.textContent = "Please enter a valid Sri Lankan mobile number (9 digits).";
        return;
    }

    // Clear error message
    error.textContent = "";

    // Set hidden input value
    document.getElementById("countryCode").value = countryCode;

    // Submit the form programmatically
    this.submit();
});
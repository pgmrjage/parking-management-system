<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Application Form</title>


    <style>

            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 8px;
            }

            form {
                display: flex;
                flex-direction: column;
            }

            h1, h2 {
                text-align: center;
            }

            label {
                margin-top: 10px;
            }

            input, select {
                margin-bottom: 10px;
                padding: 8px;
            }

            button {
                background-color: #4caf50;
                color: white;
                padding: 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            button:hover {
                background-color: #45a049;
            }


    </style>

</head>
<body>

<div class="container">
    <form id="studentForm">
        <h1>Student Application for Motor Vehicle Pass</h1>
        <p>Date: <span id="date"></span></p>
        
        <label for="applicationType">Type of Application:</label>
        <select id="applicationType" name="applicationType">
            <option value="selfDrive">Self-Drive</option>
            <option value="renewalDifferentVehicle">Renewal with Different Vehicle</option>
            <option value="dropPickUp">Drop & Pick up</option>
            <option value="new">New</option>
            <option value="twoWheel">Two-Wheel</option>
            <option value="renewalSameVehicle">Renewal with Same vehicle</option>
            <option value="fourWheels">Four Wheels</option>
        </select>

        <h2>Information on Student Application</h2>

        <label for="name">Name of Applicant:</label>
        <input type="text" id="name" name="name" required autocomplete="off">
        
        <label for="courseYear">Course & Year:</label>
        <input type="text" id="courseYear" name="courseYear" required>

        <label for="idNumber">ID Number:</label>
        <input type="text" id="idNumber" name="idNumber" required>

        <label for="cityAddress">City Address:</label>
        <input type="text" id="cityAddress" name="cityAddress" required>

        <label for="mobileNumber">Mobile No.:</label>
        <input type="tel" id="mobileNumber" name="mobileNumber" required>

        <label for="makeBrand">Make / Brand:</label>
        <input type="text" id="makeBrand" name="makeBrand" required>

        <label for="yearModel">Year Model:</label>
        <input type="text" id="yearModel" name="yearModel" required>

        <label for="plateNo">Plate No:</label>
        <input type="text" id="plateNo" name="plateNo" required>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color" required>

        <label for="registrationNo">Registration No.:</label>
        <input type="text" id="registrationNo" name="registrationNo" required>

        <label for="dateIssuedRegistration">Date Issued (Registration):</label>
        <input type="date" id="dateIssuedRegistration" name="dateIssuedRegistration" required>

        <label for="currentORNo">Current OR No.:</label>
        <input type="text" id="currentORNo" name="currentORNo" required>

        <label for="dateIssuedOR">Date Issued (Current OR):</label>
        <input type="date" id="dateIssuedOR" name="dateIssuedOR" required>

        <label for="driversLicenseNo">Driver's License No.:</label>
        <input type="text" id="driversLicenseNo" name="driversLicenseNo" required>

        <label for="dateIssuedLicense">Date Issued (Driver's License):</label>
        <input type="date" id="dateIssuedLicense" name="dateIssuedLicense" required>

        <label for="expiryDateLicense">Expiry Date (Driver's License):</label>
        <input type="date" id="expiryDateLicense" name="expiryDateLicense" required>

        <!-- Additional fields based on the given details -->

        <button type="button" onclick="window.print(); return false";>Test</button>
    </form>
</div>

<script>
    document.getElementById('date').innerText = new Date().toLocaleDateString();

    function printForm() {
        var form = document.getElementById('studentForm');
        var formValues = new FormData(form);
        
        // You can use formValues to send the form data to a server or process it further

        // For now, let's log the form data to the console
        for (var pair of formValues.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
}


</script>
</body>
</html>

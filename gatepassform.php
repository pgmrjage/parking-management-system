<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables and initialize with empty values
    $controlNo = $date = $name = $address = $vehicleType = $plateNumber = $amountPaid = $orNumber = $applicantType = "";

    // Processing form data when form is submitted
    $controlNo = $_POST["controlNo"];
    $date = $_POST["date"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $vehicleType = $_POST["typeOfVehicle"];
    $plateNumber = $_POST["plateNumber"];
    $amountPaid = $_POST["amountPaid"];
    $orNumber = $_POST["orNumber"];
    $applicantType = $_POST["applicantType"];

    // Check if type of applicant is "others" and get the specified value
    if ($applicantType === 'others') {
        $applicantType = $_POST["othersInput"];
    }

    // Retrieve user_id from session
    $user_id = $_SESSION["username"];

    // Prepare an insert statement
    $sql = "INSERT INTO vehicles (controlnumber, date, name, address, vehicletype, platenumber, amountpaid, ORnum, typeofapplicant, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssssssssss", $controlNo, $date, $name, $address, $vehicleType, $plateNumber, $amountPaid, $orNumber, $applicantType, $user_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to success page
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Gate Pass Application Form</title>

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

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            margin-top: 10px;
        }

        input,
        select,
        textarea {
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

        #othersContainer {
            margin-top: 10px;
        }
    </style>

</head>

<body>

    <div class="container">
        <form id="gatePassForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1>Vehicle Gate Pass Application Form</h1>

            <label for="controlNo">Control No.:</label>
            <input type="text" id="controlNo" name="controlNo" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required autocomplete="on">

            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" required>

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" required autocomplete="off"></textarea>

            <label for="typeOfVehicle">Type of Vehicle:</label>
            <input type="text" id="typeOfVehicle" name="typeOfVehicle" required>

            <label for="plateNumber">Plate Number:</label>
            <input type="text" id="plateNumber" name="plateNumber" required>

            <label for="amountPaid">Amount Paid:</label>
            <input type="text" id="amountPaid" name="amountPaid" required>

            <label for="orNumber">OR #:</label>
            <input type="text" id="orNumber" name="orNumber" required>

            <label for="applicantType">Type of Applicant:</label>
            <select id="applicantType" name="applicantType">
                <option value="student">Student</option>
                <option value="fullTimeEmployee">Full-time Employee of NDDU</option>
                <option value="partTimeEmployee">Part-time Employee of NDDU</option>
                <option value="others">Others:</option>
            </select>

            <div id="othersContainer" style="display: none;">
                <label for="othersInput">Specify:</label>
                <input type="text" id="othersInput" name="othersInput">
            </div>
            <button type="submit">Submit</button>
            <button type="button" onclick="window.print(); return false" ;>Print</button>
        </form>
    </div>

    <script>
        function printForm() {
            var form = document.getElementById('gatePassForm');
            var formValues = new FormData(form);

            var output = "<h1>Vehicle Gate Pass Application Form</h1>";

            formValues.forEach(function (value, key) {
                output += "<p><strong>" + key + ":</strong> " + value + "</p>";
            });

            var applicantType = document.getElementById('applicantType');
            var othersContainer = document.getElementById('othersContainer');
            var othersInput = document.getElementById('othersInput');

            if (applicantType.value === 'others') {
                output += "<p><strong>Specify:</strong> " + othersInput.value + "</p>";
            }

            document.body.innerHTML = output;
        }

    </script>
</body>

</html>
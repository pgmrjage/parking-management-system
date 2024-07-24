<?php
session_start(); // Start or resume a session

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // If not logged in, redirect to the login page
    header("Location: login.php"); // Adjust the path to your login page
    exit(); // Stop further execution
}

include("config.php");





// Define the maximum number of slots
$maxSlots = 15;

// SQL query to count pending requests
$queryCount = "SELECT COUNT(*) AS total FROM vehicles WHERE status = 'pending' AND typeofapplicant = 'request'";
$resultCount = $conn->query($queryCount);
$rowCount = $resultCount->fetch_assoc();
$count = $rowCount['total'];

// Calculate remaining open slots
$remainingSlots = $maxSlots - $count;





// Check for a valid database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the user ID from the session
$userId = $_SESSION["username"];
$user_folder = 'Uploads/' . $userId . '/';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert vehicle record into the database
    $vehicletype = isset($_POST['vehicletype']) ? $_POST['vehicletype'] : "";
    $brand = isset($_POST['brand']) ? $_POST['brand'] : "";
    $yearModel = isset($_POST['yearModel']) ? $_POST['yearModel'] : "";
    $color = isset($_POST['color']) ? $_POST['color'] : "";
    $platenumber = isset($_POST['platenumber']) ? $_POST['platenumber'] : "";
    $amount = isset($_POST['amount']) ? $_POST['amount'] : "";
    $ORnum = isset($_POST['ORnum']) ? $_POST['ORnum'] : "";

    // Prepare and execute the SQL statement for vehicle insertion
    $insert_vehicle_stmt = $conn->prepare("INSERT INTO vehicles (date, vehicletype, brand, yearmodel, color, platenumber, amountpaid, ORnum, typeofapplicant, status, user_id) VALUES (CURRENT_DATE(), ?, ?, ?, ?, ?, ?, ?, 'request', 'pending', ?)");
    $insert_vehicle_stmt->bind_param("sssssssi", $vehicletype, $brand, $yearModel, $color, $platenumber, $amount, $ORnum, $userId);

    // Execute the prepared statement for vehicle insertion
    if ($insert_vehicle_stmt->execute()) {
        echo "<script>alert('Upload Successfully. Your request has been reviewed by the Admin.');</script>";
        // Retrieve the auto-generated control number
        $controlNo = $conn->insert_id;

        // Handle image upload if files are selected
        if (isset($_FILES['image']['name']) && is_array($_FILES['image']['name'])) {
            // Create a folder for each vehicle data
            $vehicle_folder = $user_folder . $controlNo . '/';
            if (!file_exists($vehicle_folder)) {
                mkdir($vehicle_folder, 0777, true);
            }

            // Loop through each uploaded file
            foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['image']['name'][$key];
                $file_tmp = $_FILES['image']['tmp_name'][$key];
                $file_destination = $vehicle_folder . $file_name;

                // Move uploaded file to destination
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // Insert image record into the database
                    $insert_stmt = $conn->prepare("INSERT INTO images (user_id, image_name, controlnum) VALUES (?, ?, ?)");
                    $insert_stmt->bind_param("iss", $userId, $file_name, $controlNo);
                    if ($insert_stmt->execute()) {
                        // echo "<script>alert('Image $file_name uploaded successfully.');</script>";       Iterate notification when .jpg submits. 
                    }
                } else {
                    // Error uploading file
                    echo "<script>alert('Error uploading image $file_name.');</script>";
                }
            }
        } else {
            // No files selected
            echo "<script>alert('No files selected for upload.');</script>";
        }
    } else {
        echo "<script>alert('Error uploading vehicle data.');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" class="rel">
    <title>Document</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #00843D;
        }

        .container {
            position: relative;
            max-width: 900px;
            width: 100%;
            border-radius: 6px;
            padding: 30px;
            margin: 0 15px;
            background-color: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);

        }
        header{
            display: flex;
            justify-content: space-between;
            text-align: center;
            align-items: center;
        }
        header .home-button {
            padding: 5px 10px;
            font-size: 20px;
            font-weight: 600;
            background-color: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .container header {
            position: relative;
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .container header::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            height: 3px;
            width: 27px;
            border-radius: 8px;
            background-color: #00843D;
        }

        .container form {
            position: relative;
            margin-top: 16px;
            min-height: 490px;
            background-color: #fff;
            overflow: hidden;
            /* height: 100%; */
        }

        .container form .form {
            position: absolute;
            background-color: #fff;
            transition: 0.3s ease;
        }

        .container form .form.second {
            opacity: 0;
            pointer-events: none;
            transform: translateX(100%);
            width: 100%;
        }

        form.secActive .form.second {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        form.secActive .form.first {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-100%);

        }

        .container form .title {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 500;
            margin: 6px 0;
            color: #333;
        }

        .container form .fields {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        form .fields .input-field {
            display: flex;
            width: calc(100% / 2 - 15px);
            flex-direction: column;
            margin: 4px 0;
            border-radius: 100px;
        }

        .input-field label {
            font-size: 12px;
            font-weight: 500;
            color: #2e2e2e;
        }

        .input-field input, select {
            outline: none;
            font-size: 14px;
            font-weight: 400;
            color: #333;
            border-radius: 5px;
            border: 1px solid #aaa;
            padding: 0 15px;
            height: 42px;
            margin: 8px 0;
        }

        .input-field input:is(:focus, :valid) {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.13);
        }

        /* .input-field input[type=""] */

        .container form button,
        .backBtn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 45px;
            max-width: 200px;
            width: 100%;
            border: 10px;
            /* outline: none; */
            color: #fff;
            border-radius: 5px;
            margin: 25px 0;
            background-color: #00843D;
            transition: all 400ms linear;
            cursor: pointer;
        }

        .container form button,
        .container form .backBtn {
            font-size: 14px;
            font-weight: 400;
        }

        form button:hover {
            background-color: #00843D;

        }

        form button i,
        form .backBtn i {
            margin: 0 6px;
        }

        form .backBtn i {
            transform: rotate(180deg);
        }

        form .buttons {
            display: flex;
            align-items: center;

        }

        form .buttons button,
        .backBtn {
            margin-right: 14px;
        }

        @media (max-width: 750px) {
            .container form {
                overflow-y: scroll;
            }

            .container form::-webkit-scrollbar {
                display: none;
            }

            form .fields .input-field {
                width: calc(100% / 1 - 15px);
            }
        }

        @media (max-width: 550px) {
            form .fields .input-field {
                width: 100%;
            }
        }


        /* Upload Properties*/
        .custum-file-upload {
            height: 200px;
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: space-between;
            gap: 20px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            border: 2px dashed #cacaca;
            background-color: rgba(255, 255, 255, 1);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0px 48px 35px -48px rgba(0, 0, 0, 0.1);
        }

        .custum-file-upload .icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custum-file-upload .icon svg {
            height: 80px;
            fill: rgba(75, 85, 99, 1);
        }

        .custum-file-upload .text {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custum-file-upload .text span {
            font-weight: 400;
            color: rgba(75, 85, 99, 1);
        }

        .custum-file-upload input {
            display: none;
        }



        /**/
        .upload {
            display: flex;
            justify-content: center;
            background-color: white;
            border: 2px dashed;
            width: 100%;
            position: relative;
            margin: 3.17em auto;
            padding: 15px;
            border-radius: 0.43em;
        }

        .upload li {
            font-size: 14px;
            font-weight: 500;
            background-color: #b9d4ff;
            color: #025bee;
            margin-bottom: 1em;
            padding: 1.1em 1em;
            display: flex;
            justify-content: space-between;
        }


        
    </style>
</head>

<body>
    <div class="container">
        <header>New Request
            <a href="index.php" class="home-button">Back</a>
        </header>

        <form id="uploadForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            enctype="multipart/form-data">
            <div class="form first">
                <div class="detail personal">
                    <!-- Display remaining open slots -->
                    <div class="remaining-slots" style="font-style:italic; font-weight:bold; color: red;">Remaining open slots: <?php echo $remainingSlots; ?></div>
                    <span class="title">Vehicle Information</span>
                    
                    <div class="fields">

                        <div class="input-field">
                            <label for="vehicletype">Type of Application:</label>
                            <select name="vehicletype" placeholder="Vehicle Type" required>
                                <option value = ""></option>    <!-- leave as default blank -->
                                <option value = "Two-Wheel">Two-Wheel</option>
                                <option value = "Four Wheels">Four-Wheels</option>
                                <option value = "Drop & Pick-up">Drop & Pick-Up</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="">Brand:</label>
                            <input type="text" name="brand" placeholder="Brand" required>
                        </div>

                        <div class="input-field">
                            <label for="">Year Model:</label>
                            <input type="text" name="yearModel" placeholder="Year Model" required>
                        </div>

                        <div class="input-field">
                            <label for="">Color:</label>
                            <input type="text" name="color" placeholder="Color" required>
                        </div>

                        <div class="input-field">
                            <label for="">Plate Number:</label>
                            <input type="text" name="platenumber" placeholder="Plate Number" required>
                            <!-- <input type="text" name="platenumber" placeholder="Plate Number"> -->
                        </div>

                    </div>
                </div>
                <div class="detail ID">

                    <button class="nextBtn">
                        <span class="btnText">Next</span>
                        <i class="uil uil-navigator"></i>
                    </button>
                </div>
            </div>

            <div class="form second">
                <div class="detail personal">
                    <span class="title">Payment Details</span>
                    <div class="fields">
         
                        <div class="input-field">
                            <label for="amount">Amount Paid</label>
                            <select name="amount">
                                <option value = "500.00">500</option>
                                <option value = "250.00">250</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="">Official Receipt (OR #):</label>
                            <input type="text" name="ORnum" placeholder="OR #" required>
                        </div>

                    </div>
                    <span class="title">Upload Requirements</span>


                    <!--PREVIOUS DESIGN FOR UPLOAD REQUIREMENTS-->
                    <!-- <div class="fields">
                            <input type="file" id="uploadBtn" name="image[]" accept="image/*" multiple>
                    </div> -->

                    <div class="upload">
                        
                        <input type="file" id="file-input" name="image[]" accept="image/*" multiple onchange="checkFileSize(event)">
                        <div id="num-of-files"></div>
                        <ul id="files-list"></ul>

                        <!--NEW DESIGN FOR UPLOAD REQUIREMENTS-->
                        <!-- <label for="file-input">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14"
                                        viewBox="0 0 448 512">
                                        <path
                                            d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3V320c0 17.7 14.3 32 32 32s32-14.3 32-32V109.3l73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 53 43 96 96 96H352c53 0 96-43 96-96V352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V352z" />
                                    </svg>
                                    &nbsp; Choose Files to Upload
                                </label>
                                 -->

                    </div>
                </div>
                <div class="detail ID">
                    <div class="buttons">
                        <div class="backBtn">
                            <i class="uil uil-navigator"></i>
                            <span class="btnText">Back</span>
                        </div>

                        <button type="submit" class="submit">
                            <span class="submit">Submit</span>
                            <i class="uil uil-navigator"></i>
                            <a href="index.php"></a>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const form = document.querySelector("form");
        const nextBtn = form.querySelector(".nextBtn");
        const backBtn = form.querySelector(".backBtn");
        const allInput = form.querySelectorAll(".first input");

        nextBtn.addEventListener("click", () => {
            allInput.forEach(input => {
                if (input.value != "") {
                    form.classList.add('secActive');
                } else {
                    form.classList.remove('secActive');
                    //alert("input is empty");
                }
            });
        });

        backBtn.addEventListener("click", () => form.classList.remove('secActive'));



        // Confirm upload before submitting form


    </script>
    <script>
        // Function to check if files are selected
        // Function to check if files are selected
        // Function to check if files are selected
        function filesSelected() {
            const fileInput = document.getElementById("file-input");
            const files = fileInput.files;
            return files && files.length > 0;
        }



        // Confirm upload before submitting form
        // Confirm upload before submitting form
        document.getElementById('uploadForm').addEventListener('submit', function (event) {
            // Check if files are selected
            if (!filesSelected()) {
                alert("Please Select Files to Upload.");
                event.preventDefault();
            } else {
                var confirmUpload = confirm("Are you sure you want to submit the data?");
                if (!confirmUpload) {
                    event.preventDefault();
                }
            }
        });

        // Debugging output
        console.log(filesSelected());



        function checkFileSize(event) {
        const files = event.target.files;
        const maxSize = 2 * 1024 * 1024; // 2 MB in bytes

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.size > maxSize) {
                alert('File size exceeds the maximum limit of 2MB.');
                event.target.value = ''; // Clear the selected file
                return;
            }
        }
        }

    </script>


</body>

</html>


<?php

// Close the database connection
$conn->close();
?>
<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include("config.php");


// Define the maximum number of slots
$maxSlots = 15;

// SQL query to count pending requests
$queryCount = "SELECT COUNT(*) AS total FROM vehicles WHERE status = 'pending' AND typeofapplicant = 'renew'";
$resultCount = $conn->query($queryCount);
$rowCount = $resultCount->fetch_assoc();
$count = $rowCount['total'];

// Calculate remaining open slots
$remainingSlots = $maxSlots - $count;


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmUpload'])) {
    $controlNo = $_POST["controlnumber"];
    $amount = $_POST['amount'];
    $ORnum = $_POST['ORnum'];   

    $user_folder = 'Uploads/' . $userId . '/' . $controlNo . '/';
    $update_stmt = $conn->prepare("UPDATE vehicles SET status = 'pending', typeofapplicant = 'renew', amountpaid = ?, ORnum = ? WHERE controlnumber = ?");
    $update_stmt->bind_param("dsi", $amount, $ORnum, $controlNo);

    if ($update_stmt->execute()) {
        echo "<script>alert('Your Renewal Request has been reviewed by the Admin');</script>";
        // echo "<script>alert('Database updated successfully.');</script>";
        
    } else {
        echo "<script>alert('Error updating database.');</script>";
    }

    // Handle image upload if files are selected
    if (isset($_FILES['image']['name']) && is_array($_FILES['image']['name'])) {
        $confirmUpload = isset($_POST['confirmUpload']) ? $_POST['confirmUpload'] : false;
        if ($confirmUpload) {
            // Delete existing files and records once before processing new uploads
            $existing_files = glob($user_folder . '*');
            foreach ($existing_files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            $delete_stmt = $conn->prepare("DELETE FROM images WHERE user_id = ? AND controlnum = ?");
            $delete_stmt->bind_param("ii", $userId, $controlNo);
            $delete_stmt->execute();
        
            // Now loop through uploaded files and handle them individually
            $file_count = count($_FILES['image']['name']);
            for ($i = 0; $i < $file_count; $i++) {
                $file_name = $_FILES['image']['name'][$i];
                $file_tmp = $_FILES['image']['tmp_name'][$i];
                $file_destination = $user_folder . $file_name;
        
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    $insert_stmt = $conn->prepare("INSERT INTO images (user_id, image_name, created_at, controlnum) VALUES (?, ?, NOW(), ?)");
                    $insert_stmt->bind_param("iss", $userId, $file_name, $controlNo);
                    $insert_stmt->execute();
        
                    // echo "<script>alert('Image $file_name uploaded successfully.');</script>";
                } else {
                    echo "<script>alert('Error uploading image $file_name.');</script>";
                }
            }
        
        
        } else {
            echo "Upload canceled.";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ? AND status = 'accepted' AND Expiration_date <= NOW()");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
// Display vehicle information and allow editing
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renewal Request</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #00843D;
        }

        .container {
            display: grid;
            width: 100%;
            max-width: 800px;
            margin: 21px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-height: 600px;
            overflow-y: auto;
        }

        .multi-query-container{
            display: flex;
            flex-wrap: wrap;
        }

        .query-container {
            display: grid;
            margin: 24px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.300);
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            font-size: 19px;
            font-weight: 600;
            padding: 16px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            background-color: #00843D;
            color: #fff;
        }

        .form-information{
            display: flex;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            padding: 14px;
            border: dashed #333 3px;
            height: auto;
            align-items: center;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 16px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            color: #333;
            font-weight: 600;

        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
        }

        button {
            height: 45px;
            width: 190px;
            background-color: #4caf50;
            color: white;
            font-family: 'Poppins';
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 400ms;
        }

        button:hover {
            background-color: #00843D;
        }

        .uploaded-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .home-button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            background-color: red;
        }


        .form-fields {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }

        .input-field {
            display: grid;
            width: 100%;
        }

        /**Upload Properties*/
        .details {
            /* width: calc(100% / 2 - 15px); */
            /* align-items: center; */
            /* justify-content: center; */
            /* text-align: center; */
            /* align-items: center; */
            /* width: auto; */
            /* height: 100%; */
            display: flex;
            font-size: 16px;
            margin-top: 18px;
            /* border: 1px dashed #ccc; */
            /* border-radius: 397px; */
            width: 85%;
            outline: none;

            box-shadow: 0 0 10px rgba(0, 0, 0, 0.250);
        }

        ::-webkit-file-upload-button {
            color: #fff;
            background-color: #206a5d;
            padding: 20px;
            border: none;
            /* border-radius: 397px; */
        }

        .details form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .details form input[type="file"] {
            margin-bottom: 10px;
        }

        .details form button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .details form button:hover {
            background-color: #45a049;
        }

        #imageContainer {
            margin-top: 20px;
        }

        .uploaded-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: black;
        }

        .content-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .form-footer {
            display: flex;
            justify-content: center;
            padding: 14px;
            font-family: 'Poppins' sans-serif;
            font-size: 27px;
            font-weight: 600;
        }

        @media(width: calc(100% / 1)) {}

        #uploadForm {
            flex-wrap: wrap;
            width: calc(100% / 1);
        }


        
    </style>
</head>

<body>


    <div class="container">
        <div class="header">
            <div class="content-title">
                
                <label for="">Renewal Request</label>
                <div class="remaining-slots" style="font-size: 16px;font-style:italic; font-weight:bold; color: red;">(Remaining open slots: <?php echo $remainingSlots; ?>)</div>
            </div>
            
            <div class="content">
                <a href="index.php" class="home-button">Back</a>
                <a href="index.php"><i class="fas fa-arrow-left"></i></a>
            </div>
        </div>


        <?php
        // Check if there are any vehicles
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $vehicletype = $row['vehicletype'];
                $brand = $row['brand'];
                $yearmodel = $row['yearmodel'];
                $color = $row['color']; // Added this line to fetch Phone number
                $platenumber = $row['platenumber']; // Added this line to fetch User_Type
                $ORnum = $row['ORnum'];
                $amountpaid = $row['amountpaid'];
                ?>
                
                <div class="multi-query-container">
                    <div class="query-container">
                        <div class="form-header">
                            <span class="title">Vehicle Information</span>
                            <span class="title">Plate Number:
                                <?php echo $platenumber ?>
                            </span>

                        </div>

                        <div class="form-information">
                            <form id="uploadForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                                enctype="multipart/form-data">
                                <div class="form-container">
                                    <div class="">
                                        <label for="form-label">Upload Requirements:</label>
                                    </div>
                                    <div class="details">

                                        
                                        <!-- Image upload input with multiple attribute -->
                                        <input type="file" name="image[]" accept="image/*" multiple onchange="checkFileSize(event)">
                                        <!-- Hidden input for control number -->
                                        <input type="hidden" name="controlnumber" value="<?php echo $row['controlnumber']; ?>">


                                        <!-- Display uploaded images -->
                                        <div id="imageContainer">
                                            <?php
                                            // Prepare and execute the query to fetch uploaded images for the current user
                                            $controlNo = $row['controlnumber'];
                                            $stmt_images = $conn->prepare("SELECT image_name FROM images WHERE controlnum = ?");
                                            $stmt_images->bind_param("i", $controlNo);
                                            $stmt_images->execute();
                                            $result_images = $stmt_images->get_result();

                                            if ($result_images && $result_images->num_rows > 0) {
                                                while ($row_image = $result_images->fetch_assoc()) {
                                                    //DISPLAYING PHOTO IN THE UPLOAD
                                                    // echo '<img class="uploaded-image" src="' . $user_folder . $controlNo . '/' . $row_image['image_name'] . '" alt="Vehicle Image">';
                                                    // echo '<img class="uploaded-image" src="' . $user_folder . $controlNo . '/' . $row_image['image_name'] . '" alt="Vehicle Image">';
                                                }
                                            }
                                            // Close the prepared statement for fetching images
                                            $stmt_images->close();
                                            ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-fields">
                                    <div class="input-field">
                                        <label for="form-label">Type of Application:</label>
                                        <input type="text" name="vehicletype" placeholder="Vehicle Type"
                                            value="<?php echo $vehicletype ?>" required>
                                    </div>

                                    <div class="input-field">
                                        <label for="form-label">Brand:</label>
                                        <input type="text" name="brand" placeholder="Brand:" value="<?php echo $brand; ?>">
                                    </div>

                                    <div class="input-field">
                                        <label for="form-label">Year Model:</label>
                                        <input type="text" name="yearmodel" placeholder="Yearmodel:" value="<?php echo $yearmodel; ?>"
                                            required>
                                    </div>

                                    <div class="input-field">
                                        <label for="form-label">Color:</label>
                                        <input type="text" name="color" placeholder="Color:" value="<?php echo $color; ?>">
                                    </div>

                                    <div class="input-field">
                                        <label for="form-label">Plate Number:</label>
                                        <input type="text" name="platenumber" placeholder="Plate Number"
                                            value="<?php echo $row['platenumber']; ?>" required>
                                    </div>

                                    <div class="input-field">
                                        <!-- <label for="form-label">Amount Paid:</label>
                                        <input type="number" name="amount" placeholder="Amount Paid" required> -->
                                        <label for="amount">Amount Paid</label>
                                        <select name="amount">
                                            <option value = "" required></option>
                                            <option value = "500.00">500</option>
                                            <option value = "250.00">250</option>
                                        </select>
                                    </div>

                                    <div class="input-field">
                                        <label for="form-label">Official Receipt (OR #):</label>
                                        <input type="text" name="ORnum" placeholder="OR #" required>
                                    </div>
                                </div>

                                <div class="form-footer">
                                    <!-- Submit button -->
                                    <button type="submit" name="confirmUpload" value="true">Submit for Renewal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
            }
        } else {
            // If no vehicles meet the criteria, display a message
            echo "No vehicles eligible for renewal found.";
        }

        // Close the prepared statements and database connection
        $stmt->close();
        $conn->close();
        ?>
    </div>

    <!-- JavaScript for confirmation popup -->
    <script>
        // Confirm upload before submitting form
        document.getElementById('uploadForm').addEventListener('submit', function (event) {
            var confirmUpload = confirm("Are you sure you want to upload and replace the old images?");
            if (!confirmUpload) {
                event.preventDefault();
            }
        });


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
<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uploadImages'])) {
    // Check if the user ID and control number are provided
    if (isset($_POST['userId']) && isset($_POST['controlNumber'])) {
        $userId = $_POST['userId'];
        $controlNumber = $_POST['controlNumber'];

        // Specify the directory where images will be stored
        $targetDirectory = "Uploads/{$userId}/{$controlNumber}/";

        // Create the directory if it doesn't exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        // Process each uploaded image
        foreach ($_FILES['uploadedImages']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['uploadedImages']['name'][$key];
            $image_tmp = $_FILES['uploadedImages']['tmp_name'][$key];
            $image_type = $_FILES['uploadedImages']['type'][$key];

            // Check if the file is an image
            if (strpos($image_type, "image") !== false) {
                // Move the uploaded image to the target directory
                $targetPath = $targetDirectory . $image_name;
                move_uploaded_file($image_tmp, $targetPath);

                // Insert the image metadata into the database
                $stmt = $conn->prepare("INSERT INTO images (user_id, image_name, controlnum) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $userId, $image_name, $controlNumber);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Redirect back to the upload_images.php page
        header("Location: index.php?userId={$userId}&controlNumber={$controlNumber}");
        exit();
    } else {
        echo "User ID and control number not provided.";
    }
} else {
    // If the request is not a POST request or uploadImages is not set, redirect to the homepage or display an error message
    header("Location: index.php");
    exit();
}
?>
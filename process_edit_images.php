<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editImages'])) {
    // Get the user ID and control number
    $userId = $_POST['userId'];
    $controlNumber = $_POST['controlNumber'];

    // Check if images are selected for editing
    if (!empty($_POST['selectedImages'])) {
        // Loop through the selected image IDs
        foreach ($_POST['selectedImages'] as $imageId) {
            // Delete the image file from the folder
            $stmt_select_image = $conn->prepare("SELECT image_name FROM images WHERE id = ?");
            $stmt_select_image->bind_param("i", $imageId);
            $stmt_select_image->execute();
            $result_image = $stmt_select_image->get_result();
            $image_row = $result_image->fetch_assoc();
            $image_name = $image_row['image_name'];
            $image_path = "Uploads/{$userId}/{$controlNumber}/{$image_name}";
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the file
            }
            $stmt_select_image->close();

            // Delete the image record from the database
            $stmt_delete_image = $conn->prepare("DELETE FROM images WHERE id = ?");
            $stmt_delete_image->bind_param("i", $imageId);
            $stmt_delete_image->execute();
            $stmt_delete_image->close();
        }
    }

    // Redirect to upload new images page
    header("Location: upload_images.php?userId={$userId}&controlNumber={$controlNumber}");
    exit();
} else {
    // Handle invalid requests or direct access to this page
    header("Location: index.php"); // Redirect to homepage
    exit();
}
?>
<?php
include("config.php"); // Include your database connection file

// Check if the user_id and controlnumber parameters are set
if (isset($_POST['user_id']) && isset($_POST['controlnumber'])) {
    // Sanitize the input to prevent SQL injection
    $user_id = $_POST['user_id'];
    $controlnumber = $_POST['controlnumber'];

    // Prepare a SQL statement to delete images associated with the given controlnumber
    $delete_images_sql = "DELETE FROM images WHERE controlnum = ?";
    $stmt_delete_images = $conn->prepare($delete_images_sql);
    $stmt_delete_images->bind_param("i", $controlnumber);

    // Execute the deletion of images
    if ($stmt_delete_images->execute()) {
        // Images deleted successfully, now delete the vehicle record
        $delete_vehicle_sql = "DELETE FROM vehicles WHERE controlnumber = ?";
        $stmt_delete_vehicle = $conn->prepare($delete_vehicle_sql);
        $stmt_delete_vehicle->bind_param("i", $controlnumber);

        // Execute the deletion of the vehicle record
        if ($stmt_delete_vehicle->execute()) {
            // Vehicle record deleted successfully
            echo "Vehicle record and associated images deleted successfully.";
        } else {
            // Error deleting vehicle record
            echo "Error deleting vehicle record: " . $stmt_delete_vehicle->error;
        }
    } else {
        // Error deleting images
        echo "Error deleting associated images: " . $stmt_delete_images->error;
    }

    // Close prepared statements
    $stmt_delete_images->close();
    $stmt_delete_vehicle->close();
} else {
    // Missing parameters
    echo "Missing user_id or controlnumber parameter.";
}

// Close database connection
$conn->close();
?>
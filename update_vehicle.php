<?php
// Include your database connection file
include("config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $controlnumber = $_POST['controlnumber'] ?? '';
    $vehicletype = $_POST['vehicletype'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $yearmodel = $_POST['yearmodel'] ?? '';
    $color = $_POST['color'] ?? '';
    $platenumber = $_POST['platenumber'] ?? '';
    $ORnum = $_POST['ORnum'] ?? '';

    // Retrieve the existing expiration date
    $stmt = $conn->prepare("SELECT expiration_date FROM vehicles WHERE controlnumber = ?");
    $stmt->bind_param("s", $controlnumber);
    $stmt->execute();
    $stmt->bind_result($expiration_date);
    $stmt->fetch();
    $stmt->close();

    // Prepare and execute the query to update the vehicle information
    $stmt = $conn->prepare("UPDATE vehicles SET vehicletype=?, brand=?, yearmodel=?, color=?, platenumber=?, ORnum=? WHERE controlnumber=?");
    $stmt->bind_param("sssssss", $vehicletype, $brand, $yearmodel, $color, $platenumber, $ORnum, $controlnumber);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
        // Update existing uploaded images if any
        if (isset($_FILES['newImages']) && !empty($_FILES['newImages']['name'][0])) {
            // Handle uploading of new images
            // Assuming you have a function to handle image uploads
            // For example, uploadImages($_FILES['newImages'], $controlnumber);
            // Implement this function accordingly to handle the upload process
        }

        // Delete existing images if any selected for deletion
        if (isset($_POST['deleteImages']) && !empty($_POST['deleteImages'])) {
            // Handle deletion of existing images
            // Assuming you have a function to handle image deletion
            // For example, deleteImages($_POST['deleteImages']);
            // Implement this function accordingly to handle the deletion process
        }

        // Restore the expiration date
        $stmt = $conn->prepare("UPDATE vehicles SET expiration_date=? WHERE controlnumber=?");
        $stmt->bind_param("ss", $expiration_date, $controlnumber);
        $stmt->execute();

        // Redirect back to index.php
        header("Location: index.php");
        exit; // Stop further execution
    } else {
        echo "Failed to update vehicle information.";
    }

    // Close the prepared statement and database connection
    $stmt->close();
} else {
    echo "Form submission error.";
}
?>

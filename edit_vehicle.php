<?php
// Include your database connection file
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $controlnumber = $_POST["controlnumber"];
    $brand = $_POST["brand"];
    $yearmodel = $_POST["yearmodel"];
    $color = $_POST["color"];
    $platenumber = $_POST["platenumber"];
    $amountpaid = $_POST["amountpaid"];
    $ORnum = $_POST["ORnum"];
    $typeofapplicant = $_POST["typeofapplicant"];

    // Check if a new image was uploaded
    if ($_FILES["image"]["name"]) {
        // Handle image upload here
        // Move the uploaded image to the destination directory
        // Update the image name in the database
    }

    // Prepare SQL statement to update the vehicle information
    $sql = "UPDATE vehicles SET brand=?, yearmodel=?, color=?, platenumber=?, amountpaid=?, ORnum=?, typeofapplicant=? WHERE controlnumber=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdssi", $brand, $yearmodel, $color, $platenumber, $amountpaid, $ORnum, $typeofapplicant, $controlnumber);

    // Execute the SQL statement
    if ($stmt->execute()) {
        // Redirect to a success page
        header("Location: success.php");
        exit;
    } else {
        // Redirect to an error page
        header("Location: error.php");
        exit;
    }

    // Close statement and database connection

} else {
    // Redirect if accessed directly without form submission
    header("Location: index.php");
    exit;
}
?>
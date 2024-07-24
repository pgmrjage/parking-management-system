<?php
// Include your database connection file
include("config.php");

// Check if control number parameter is set in the request
if(isset($_GET['controlnumber'])) {
    // Sanitize control number parameter
    $controlnumber = $_GET['controlnumber'];

    // Prepare SQL statement to fetch vehicle data based on control number
    $sql = "SELECT * FROM vehicles WHERE controlnumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $controlnumber);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Fetch data and store in associative array
        $row = $result->fetch_assoc();

        // Fetch existing images for the vehicle
        // Assuming you have a function to fetch existing images based on control number
        // For example, $existingImages = fetchExistingImages($controlnumber);
        // Implement this function accordingly to fetch existing images from your database

        // Add existing images data to the row array
        // For example:
        // $row['existingImages'] = $existingImages;

        // Encode data as JSON and output it
        echo json_encode($row);
    } else {
        // If no rows are returned, return an empty object
        echo json_encode((object)[]);
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If control number parameter is not set, return an error message
    echo json_encode(["error" => "Control number parameter is missing"]);
}
?>

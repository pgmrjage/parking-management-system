<?php
include("config.php");

// Check for a valid database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the userId and controlNumber parameters are set
if (isset($_GET['userId']) && isset($_GET['controlNumber'])) {
    $userId = $_GET['userId'];
    $controlNumber = $_GET['controlNumber']; // Corrected case to match parameter name
    $user_folder = 'Uploads/' . $userId . '/' . $controlNumber . '/';

    // Prepare and execute the query to fetch images for the given user ID and control number
    $stmt = $conn->prepare("SELECT image_name FROM images WHERE user_id = ? AND controlnum = ?");
    $stmt->bind_param("is", $userId, $controlNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    $images = array();

    // Iterate over the result set and construct the image URLs
    while ($row = $result->fetch_assoc()) {
        // Append the user folder and control number folder to the image name
        $row['image_url'] = $user_folder . $row['image_name'];
        $images[] = $row;
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();

    // Output the images array as JSON
    echo json_encode($images);
} else {
    // If userId or controlNumber parameter is not set, return an empty array
    echo json_encode([]);
}
?>
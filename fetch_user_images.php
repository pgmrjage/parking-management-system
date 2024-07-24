<?php
session_start();
include("config.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User is not logged in']);
    exit;
}

// Fetch user_id from session
$user_id = $_SESSION['user_id'];

// Fetch images associated with the user's request
$stmt = $conn->prepare("SELECT image_name FROM images WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$images = [];

// Construct image URLs
while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_name'];
}


// Output the images array as JSON
echo json_encode($images);
?>
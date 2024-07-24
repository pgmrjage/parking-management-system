<?php
include("config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Display</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* CSS styling with green and blue accents */
        /* CSS styling with green and blue accents */
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
        }

        .home-button, a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 20px;
        font-weight: 600;
        background-color: #00843D;
        color: #333;
        text-decoration: none;
        border-radius: 5px;
        padding-left: 13px;
}
.home-button a i {
    margin-right: 5px; /* Adjust spacing between icon and text */
    color: #fff;   /* Adjust icon color */
    font-size: 24px;  /* Adjust icon size */
}


        .outer-container
        {
            display: grid;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.380);

        }

        .image-container>div {
            width: calc(33.33% - 20px);
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;

        }

        .image-container>div img {
            width: 100%;
            height: auto;
        }

        .image-checkbox {
            display: none;
        }

        .image-checkbox+label {
            display: block;
            position: relative;
            cursor: pointer;
        }

        .image-checkbox+label::before {
            content: '';
            display: block;
            position: absolute;
            top: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            background-color: #fff;
            border: 2px solid #007bff;
            border-radius: 50%;
        }

        .image-checkbox:checked+label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        .upload-btn {
            display: block;
            margin: 16px;
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            font-family: 'Poppins';
        }

        @media (max-width: 768px) {
            .image-container>div {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 576px) {
            .image-container>div {
                width: calc(100% - 20px);
            }
        }
    </style>
</head>

<body>
    


    <?php
    // Check if the user ID and control number are provided in the URL parameters
    if (isset($_GET['userId']) && isset($_GET['controlNumber'])) {
        $userId = $_GET['userId'];
        $controlNumber = $_GET['controlNumber'];

        // Fetch images associated with the specified user ID and control number
        $stmt = $conn->prepare("SELECT id, image_name FROM images WHERE user_id = ? AND controlnum = ?");
        $stmt->bind_param("is", $userId, $controlNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if images are found
        if ($result->num_rows > 0) {
            
            echo '<form method="post" action="process_edit_images.php">';
            echo '<div class ="outer-container">';
            echo '<div class="home-button">
                    <a href="index.php"><i class="fas fa-arrow-left"></i></a>
                    <button type="submit" class="upload-btn" name="editImages">Upload New</button>
                </div>';
            echo '<input type="hidden" name="userId" value="' . $userId . '">';
            echo '<input type="hidden" name="controlNumber" value="' . $controlNumber . '">';
            echo '<div class="image-container">';

            // Display images with checkboxes for selection
            while ($row = $result->fetch_assoc()) {
                echo '<div>';
                echo '<input type="checkbox" id="image_' . $row['id'] . '" class="image-checkbox" name="selectedImages[]" value="' . $row['id'] . '">';
                echo '<label for="image_' . $row['id'] . '">';
                echo '<img src="Uploads/' . $userId . '/' . $controlNumber . '/' . $row['image_name'] . '" alt="Image">';
                echo '</label>';
                echo '</div>';
            }

            echo '</div>';
            // Submit button for processing selected images
            
            echo '</div>';
            echo '</form>';
            
        } else {
            echo '<script>alert("No images found for this vehicle.");</script>';
            //header("location: index.php");
            // echo '<a href="upload_images.php?userId=' . $userId . '&controlNumber=' . $controlNumber . '">Upload Images</a>';
        }
    } else {
        echo "Invalid request. User ID and control number not provided.";
    }
    ?>
</body>

</html>
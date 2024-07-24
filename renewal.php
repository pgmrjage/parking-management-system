<?php
include("config.php");

// SQL query to select rows
$query = "SELECT * FROM vehicles WHERE status = 'pending' AND typeofapplicant = 'renew' ORDER BY date LIMIT 15";
$result = $conn->query($query);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>NMPMS Request</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="assets/css/requeststyle.css" rel="stylesheet">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="admin.php" class="logo d-flex align-items-center">
                <img src="assets/img/seal.png" alt="">
                <span class="d-none d-lg-block">NDDU Main PMS</span>
            </a>
        </div><!-- End Logo -->
        
    </header>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Renewal Request Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                    <li class="breadcrumb-item active">Admin Dashboard</li>
                </ol>
            </nav>
        </div>
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">404</a></li>
                                    <li><a class="dropdown-item" href="#">404</a></li>
                                    <li><a class="dropdown-item" href="#">404</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">New Request Applicant <span>| </span></h5>
                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Control Number</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">Type of Application</th>
                                            <th scope="col">Plate Number</th>
                                            <th scope="col">Amount Paid</th>
                                            <th scope="col">OR Number</th>
                                            <th scope="col">Type of Applicant</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">View</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody> <!-- Add tbody tag here -->


                                        <?php
                                        // Fetch and display data
                                        while ($row = $result->fetch_assoc()) {
                                            $user_id = $row['user_id']; // Assuming 'user_id' is the correct column name
                                        
                                            // Prepared statement to prevent SQL injection
                                            $query2 = "SELECT * FROM users WHERE User_Type != 'ADMIN' AND IDnum = ?";
                                            $stmt = $conn->prepare($query2);
                                            $stmt->bind_param("s", $user_id);
                                            $stmt->execute();
                                            $userdata = $stmt->get_result()->fetch_assoc();
                                            ?>
                                            <tr>
                                                <td><?php echo $row['controlnumber']; ?></td>
                                                <td><?php echo $userdata['firstname'] . ' ' . $userdata['lastname']; ?></td>
                                                <td><?php echo $userdata['Phone number']; ?></td> <!-- Adjust column name as per your actual table structure -->
                                                <td><?php echo $row['vehicletype']; ?></td>
                                                <td><?php echo $row['platenumber']; ?></td>
                                                <td><?php echo $row['amountpaid']; ?></td>
                                                <td><?php echo $row['ORnum']; ?></td>
                                                <td><?php echo $userdata['User_Type']; ?></td>
                                                <td><?php echo $row['date']; ?></td>
                                                <td><?php echo $row['status']; ?></td>
                                                <td><button class="viewFilesButton" onclick="onViewButtonClick('<?php echo $row['user_id']; ?>', '<?php echo $row['controlnumber']; ?>')">View</button></td>
                                                <td>
                                                    <form method="post" action="">
                                                        <!-- Input field for the control number (you need to adapt this based on your application logic) -->
                                                        <input type="hidden" name="controlNumber"
                                                            value="<?php echo $row["controlnumber"]; ?>">
                                                        <!-- Button to trigger the update -->
                                                        <button type="submit" name="acceptButton">Accept</button>
                                                        <button type="submit" name="rejectButton">Reject</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                            if (isset($_POST['acceptButton'])) {
                                                $controlNumber = $_POST['controlNumber'];
                                                $amountpaid = $row['amountpaid'];
                                            
                                                if($amountpaid == 250.00){
                                                    $interval = '3 MONTH';  //Half of Semester
                                                    $sql = "UPDATE vehicles SET status = 'accepted', Expiration_date = DATE_ADD(NOW(), INTERVAL 3 MONTH) WHERE controlnumber = '$controlNumber'";
                                                    if ($conn->query($sql) === TRUE) {
                                                      echo "<script>alert('Vehicle request accepted successfully');</script>";
                                                    //   echo "Vehicle request accepted successfully";
                                                      break;
                                                    } else {
                                                      echo "<script>alert('Error updating vehicle request status: " . $conn->error . "');</script>";
                                                    //   echo "Error updating vehicle request status: " . $conn->error;
                                                    }
                                                    break;
                                                  }else if ($amountpaid == 500.00){
                                                    $interval = '6 MONTH';  //Whole Semester
                                                    $sql = "UPDATE vehicles SET status = 'accepted', Expiration_date = DATE_ADD(NOW(), INTERVAL 6 MONTH) WHERE controlnumber = '$controlNumber'";
                                                    if ($conn->query($sql) === TRUE) {
                                                      echo "<script>alert('Vehicle request accepted successfully');</script>";
                                                    //   echo "Vehicle request accepted successfully";
                                                      break;
                                                      
                                                    } else {
                                                      echo "<script>alert('Error updating vehicle request status: " . $conn->error . "');</script>";
                                                    //   echo "Error updating vehicle request status: " . $conn->error;
                                                    }
                                                    break;
                                                  }
                                            
                                            
                                                
                                            }
                                            
                                            if (isset($_POST['rejectButton'])) {
                                                $controlNumber = $_POST['controlNumber'];
                                            
                                                // Fetch user_id from vehicles table
                                                $stmt = $conn->prepare("SELECT user_id FROM vehicles WHERE controlnumber = ?");
                                                $stmt->bind_param("i", $controlNumber);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $row = $result->fetch_assoc();
                                                $userId = $row['user_id'];
                                            
                                                // Update status to 'rejected' in the vehicles table
                                                $stmt = $conn->prepare("UPDATE vehicles SET status = 'rejected' WHERE controlnumber = ?");
                                                $stmt->bind_param("i", $controlNumber);
                                                $stmt->execute();
                                            
                                                // Delete images from the file system and database
                                                $stmt = $conn->prepare("SELECT image_name, controlnum FROM images WHERE user_id = ?");
                                                $stmt->bind_param("i", $userId);
                                                $stmt->execute();
                                                $resultImages = $stmt->get_result();
                                            
                                                while ($rowImage = $resultImages->fetch_assoc()) {
                                                    $imageName = $rowImage['image_name'];
                                                    $imageNum = $rowImage['controlnum'];
                                                    $imagePath = "Uploads/$userId/$controlNumber/$imageName";
                                            
                                                    if (file_exists($imagePath)) {
                                                        if (unlink($imagePath)) {
                                                            echo "File $imageName deleted successfully.";
                                                        } else {
                                                            echo "Error deleting file $imageName.";
                                                            // Handle errors here
                                                        }
                                                    } else {
                                                        echo "File $imageName does not exist.";
                                                    }
                                                }
                                            
                                                // Delete records from the database
                                                $deleteStmt = $conn->prepare("DELETE FROM images WHERE user_id = ?");
                                                $deleteStmt->bind_param("i", $userId);
                                                $deleteStmt->execute();
                                            
                                                // Close prepared statements
                                                $stmt->close();
                                                $deleteStmt->close();
                                            }
                                        }
                                        ?>



                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Modal -->
    <div id="fileViewerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Uploaded Files</h2>
            <div id="imageGallery" class="image-gallery"></div>
        </div>
    </div>


    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script>
        // Function to handle view button click event
        function onViewButtonClick(userId, controlNumber) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var images = JSON.parse(xhr.responseText);
                        displayImages(images);
                    } else {
                        console.error('Error fetching images:', xhr.status);
                    }
                }
            };
            xhr.open('GET', 'fetch_images.php?userId=' + userId + '&controlNumber=' + controlNumber, true);
            xhr.send();
        }

        // Function to display images in modal
        function displayImages(images) {
            var imageGallery = document.getElementById('imageGallery');
            imageGallery.innerHTML = '';

            if (images.length === 0) {
                console.log('No images found.');
                return;
            }

            images.forEach(function (image) {
                var imgElement = document.createElement('img');
                imgElement.src = image.image_url;
                imgElement.alt = 'Image';
                imgElement.style.maxWidth = '100%';
                imgElement.style.maxHeight = '400px';
                imgElement.style.marginBottom = '10px';
                imageGallery.appendChild(imgElement);
            });

            // Show the modal
            var modal = document.getElementById('fileViewerModal');
            modal.style.display = 'block';
        }

        // Function to close modal
        function closeModal() {
            var modal = document.getElementById('fileViewerModal');
            modal.style.display = 'none'; // Hide the modal
        }

        
    </script>
</body>

</html>
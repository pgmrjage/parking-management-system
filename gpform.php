<?php
include("config.php");


// SQL query to select rows
$query = "SELECT * FROM users WHERE status = 'pending' AND user_type = 'request' ORDER BY created_Date";
$result = $conn->query($query);
$query2 = "SELECT * FROM images,users WHERE user_id = IDnum AND status = 'pending' AND user_type = 'request' ORDER BY user_id" . "";
$result2 = $conn->query($query2);
if (isset($_POST['acceptButton'])) {
    $userId = $_POST['userId'];
    $sql = "UPDATE users SET status = 'accept'  WHERE IDnum = $userId";
    if ($conn->query($sql) === TRUE) {
        echo "User type updated successfully";
    } else {
        echo "Error updating user type: " . $conn->error;
    }
}
if (isset($_POST['rejectButton'])) {
    $userId = $_POST['userId'];
    $sql = "UPDATE users SET status = 'rejected'  WHERE IDnum = $userId";
    if ($conn->query($sql) === TRUE) {
        echo "User type rejected successfully";
    } else {
        echo "Error updating user type: " . $conn->error;
    }
    // Using prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT image_name FROM images WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $imageName = $row['image_name'];
        $imagePath = "Uploads/$imageName";

        // Deleting the image file
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Deleting records from the database using prepared statement
    $deleteStmt = $conn->prepare("DELETE FROM images WHERE user_id = ?");
    $deleteStmt->bind_param("i", $userId);
    $deleteStmt->execute();

    // Close the prepared statements
    $stmt->close();
    $deleteStmt->close();
}

?>






<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Gate Pass Form</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2">UserID: 0001</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-login.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul>
        </li>
      </ul>
    </nav>
  </header>

  
  <main id="main" class="main">

    <div class="pagetitle">
        <h1>Gate Pass Form Dashboard</h1>
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
                        <h5 class="card-title">Gate Pass Applicants <span>| </span></h5>
                        <table class="table table-borderless datatable">
                          <thead>
                            <tr>
                                <th scope="col">Control No.</th>
                                <th scope="col">Date Issued</th>
                                <th scope="col">Firstname</th>
                                <th scope="col">Surname</th>
                                <th scope="col">Type of Vehicle</th>
                                <th scope="col">Plate Number</th>
                                <th scope="col">OR No.</th>
                                <th scope="col">Type of Applicant</th>
                                <th scope="col">Actions</th>
                            </tr>
                          </thead>

                            <?php
                            // Fetch and display data
                            while ($row = $result->fetch_assoc()) 
                            {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['IDnum']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['firstname']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['lastname']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['email']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['']; ?>
                                    </td>
                                    
                                    <td>
                                        <form method="post" action="">
                                            <!-- Input field for the user ID (you need to adapt this based on your application logic) -->
                                            <input type="hidden" name="userId" value="<?php echo $row["IDnum"]; ?>">
                                            <!-- Button to trigger the update -->
                                            <button type="submit" name="acceptButton">Accept</button>
                                            <button type="submit" name="rejectButton">Reject</button>
                                        </form>
                                    </td>

                                    <td>
                                        <?php
                                        while ($row2 = $result2->fetch_assoc()) {
                                            ?>
                                            <img src="Uploads/<?php echo $row2['image_name'] ?>">
                                        <?php }
                                        ?>
                                    </td>

                                </tr>

                              <?php
                            }

                            ?>

                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>

  </main>
















  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>


<?php
include("config.php");
$query = "SELECT COUNT(*) AS request_count FROM vehicles WHERE typeofapplicant = 'request' AND status = 'pending'";
$result = mysqli_query($conn, $query);

if ($result) {
  // Fetch the result as an associative array
  $row = mysqli_fetch_assoc($result);

  // Extract the count from the associative array
  $requestCount = $row['request_count'];

  // Output the count into the specified <h1> tag

} else {
  // Handle the case where the query fails
  echo "Error executing query: " . mysqli_error($conn);
}
$query2 = "SELECT COUNT(*) AS request_count2 FROM vehicles WHERE typeofapplicant = 'renew' AND status = 'pending'";
$result2 = mysqli_query($conn, $query2);

if ($result) {
  // Fetch the result as an associative array
  $row2 = mysqli_fetch_assoc($result2);

  // Extract the count from the associative array
  $requestCount2 = $row2['request_count2'];

  // Output the count into the specified <h1> tag

} else {
  // Handle the case where the query fails
  echo "Error executing query: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - NDDU Main PMS</title>
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
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="admin.php" class="logo d-flex align-items-center">
        <img src="assets/img/seal.png" alt="">
        <span class="d-none d-lg-block">NDDU Main PMS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Reject Request</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>


            <li>
              <hr class="dropdown-divider">
            </li>

            <a href="uploadrequest.php">
              <li class="notification-item">
                <i class="bi bi-check-circle text-success"></i>
                <div>
                  <h4>Accepted Request</h4>
                  <p>Quae dolorem earum veritatis oditseno</p>
                  <p>2 hrs. ago</p>
                </div>
              </li>
            </a>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <!-- <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a> End Messages Icon

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul> End Messages Dropdown Items

        </li>End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2"></span>
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
              <a class="dropdown-item d-flex align-items-center" href="login.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="request.php">
          <span>New Request</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="renewal.php">
          <span>Renewal Request</span>
        </a>
      </li>

      

      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="register.php">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="login.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- ACTIVE NEW REQUEST Card -->

            <?php
            echo '
                  <div class="col-md-6">
                      <div class="card info-card sales-card">
                          <div class="card-body">
                              <h5 class="card-title"> ACTIVE NEW REQUEST </h5>
                              <h1> ' . $requestCount . ' </h1>
                          </div>
                      </div>
                  </div>';

            echo '
                  <div class=" col-md-6">
                      <div class="card info-card sales-card">
                          <div class="card-body">
                          <h5 class="card-title"> ACTIVE RENEWAL REQUEST </h5>
                              <h1> ' . $requestCount2 . ' </h1>
                          </div>
                      </div>
                  </div>';
            ?>


            <!-- ACTIVE RENEWAL REQUEST Card -->


            <!-- PROFILES SECTION -->
           
          </div>

          <!-- STICKER INFO -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="#">Today</a></li>
                  <li><a class="dropdown-item" href="#">This Month</a></li>
                  <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Records <span>| Active Users</span></h5>

                <table class="table table-borderless datatable">
                  <thead>
                    <tr>
                      <!-- <th scope="col">Control No.</th>  Pwede man cguro na auto increment? or basi may suggest ka -->
                      <th scope="col">ID Number</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Mobile No.</th>
                      <th scope="col">Type of Application</th>
                      <th scope="col">Brand</th>
                      <th scope="col">Year Model</th>
                      <th scope="col">Color</th>
                      <th scope="col">Plate Number</th>
                      <th scope="col">User Type</th>
                      <th scope="col">Amount Paid</th>
                      <th scope="col">OR Number</th>
                      <th scope="col">Date Issued</th>
                      <th scope="col">Expiration Date</th>
                      <th scope="col">Uploads</th> <!--View Button for accessing submitted requirements-->
                      <th scope="col">Vechicle Status</th><!-- Active or Expired -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Assuming you have fetched the data from the database and stored it in $result
                    // SQL query to select all records with status 'accepted' (both new and renewal requests)
                    

                    //$sql = "SELECT * FROM users";
                    //$sql = "SELECT * FROM vehicles WHERE status = 'accepted'";

                    $sql = "SELECT u.*, v.*
                    FROM users u
                    JOIN vehicles v ON u.IDnum = v.user_id
                    WHERE v.status = 'accepted'";

                      // Execute the query
                      $result = mysqli_query($conn, $sql);

                      // Check if any records are returned
                      if (mysqli_num_rows($result) > 0) {
                          // Output data of each row
                          while ($row = mysqli_fetch_assoc($result)) {
                              // Display the data as needed, for example:
                                echo '<tr>';
                                echo '<td>' . $row['user_id'] . '</td>';
                                echo '<td>' . $row['firstname'] ." ". $row['lastname'].'</td>';
                                echo '<td>' . $row['email'] . '</td>';
                                echo '<td>' . $row['Phone number'] . '</td>';
                                echo '<td>' . $row['vehicletype'] . '</td>';
                                echo '<td>' . $row['brand'] . '</td>';
                                echo '<td>' . $row['yearmodel'] . '</td>';
                                echo '<td>' . $row['color'] . '</td>';
                                echo '<td>' . $row['platenumber'] . '</td>';
                                echo '<td>' . $row['User_Type'] . '</td>';
                                echo '<td>' . $row['amountpaid'] . '</td>';
                                echo '<td>' . $row['ORnum'] . '</td>';
                                echo '<td>' . $row['date'] . '</td>';
                                echo '<td>' . $row['Expiration_date'] . '</td>';
                                echo '<td><button class="viewFilesButton" onclick="onViewButtonClick(' . $row['user_id'] . ', \'' . $row['controlnumber'] . '\')">View</button></td>';

                                //Display Status Either Active or Expired
                                if ($row['status'] === "accepted" && $row["Expiration_date"] <= date("Y-m-d h:i:sa")) {
                                  echo "<td class = 'badge bg-danger' style = 'color: #fff;'>EXPIRED</td>";
                                } elseif ($row['status'] === "accepted") {
                                  echo "<td class = 'badge bg-success' style = 'color: #fff;'>ACTIVE</td>";
                                } else {
                                  echo "<td></td>"; // Empty cell if status is not "accepted"
                                }

                                echo '</tr>';
                              
                              // Display other columns as needed
                          }
                      } else {
                          echo "No records found with status 'accepted'.";
                      }

                    
                    // Close the database connection
                    mysqli_close($conn);

                    ?>
                  </tbody>
                </table>

              </div>

            </div>
          </div>

        </div>


      </div>
      </div>



    </section>

  </main>

  <div id="fileViewerModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Uploaded Files</h2>
        <div id="imageGallery" class="image-gallery"></div>
      </div>
    </div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

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
    <script>
      function toggleSidebar(){
        var sidebar = document.getElementById("sidebar");
        if(sidebar.style.width==="300px"){
          sidebar.style.width = "0";
        }
        else{
          sidebar.style.width = "300px";
        }
      }
  </script>

</body>

</html>
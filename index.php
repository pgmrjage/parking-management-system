<?php

include("config.php");
session_start();

if (isset($_SESSION["username"])) {
  $IDnum = $_SESSION["username"];
  $userData = $_SESSION["user_id"];

  // Fetch images associated with the user's request
  $stmt2 = $conn->prepare("SELECT image_name FROM images WHERE user_id = ?");
  $stmt2->bind_param("i", $user_id);
  $stmt2->execute();
  $resultimg = $stmt2->get_result();

  $images = [];

  // Construct image URLs
  while ($rowimg = $resultimg->fetch_assoc()) {
    $images[] = $rowimg['image_name'];
  }

  $sql2 = "UPDATE users AS u
      SET u.vehicle_count = (
      SELECT COUNT(*)
      FROM vehicles AS v
      WHERE v.user_id = u.IDnum
  )";
  // Prepare and execute a query to fetch firstname and lastname based on the ID number
  $sql = "SELECT * FROM users WHERE IDnum = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $IDnum);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user data exists
  if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
  }

  $firstname = $userData['firstname'];
  $lastname = $userData['lastname'];
  $email = $userData['email'];
  $phoneNumber = $userData['Phone number']; // Added this line to fetch Phone number
  $userType = $userData['User_Type']; // Added this line to fetch User_Type
  $vehicleCount = $userData['vehicle_count'];
  ?>


  <?php if ($userData["vehicle_count"] != 10) { ?>
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
          <a href="index.php" class="logo d-flex align-items-center">
            <img src="assets/img/seal.png" alt="">
            <span class="d-none d-lg-block">NDDU Main PMS</span>
          </a>
          <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
          <ul class="d-flex align-items-center">








          <li class="nav-item dropdown">
            <a class="nav-link nav-icon" href="#" id="notificationDropdown" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <!-- Add a badge to indicate the number of notifications -->
                <span class="badge bg-primary badge-number"></span>
            </a>


              <?php
              $user_id = $_SESSION['username'] ?? null;

              $stmt = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ? AND (status = 'accepted' OR status = 'rejected')");
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $result = $stmt->get_result();
              ?>

              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" id="notification-list">
                <?php if ($result && $result->num_rows > 0): ?>
                  <li class="dropdown-header">
                    You have
                    <?= $result->num_rows ?> new notifications
                    <a href="index.php"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>

                  <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $notificationId = $row['user_id']; ?>
                    <?php if (!isset($_SESSION['deleted_notifications'][$notificationId])): ?>
                      <li class="notification-item" id="notification-item-<?= $notificationId ?>">
                        <?php if ($row["status"] === "accepted"): ?>
                          <!-- Content of accepted notification -->
                          <div>
                            <h4>Accepted Request</h4>
                            <?php echo "Plate No:" . $row['platenumber'] ?>
                            <p>Your request has been approved. Please claim Parking Sticker at PPO.</p>
                          </div>
                        <?php elseif ($row["status"] === "rejected"): ?>
                          <!-- Content of rejected notification -->
                          <div>
                            <h4>Reject Request</h4>
                            <?php echo "Plate No:" . $row['platenumber'] ?>
                            <p>Your request has been rejected. Please try again.</p>
                          </div>
                        <?php endif; ?>
                        <button onclick="deleteNotification(<?= $notificationId ?>)">Delete</button>
                      </li>
                      <li>
                        <hr class="dropdown-divider">
                      </li>
                    <?php endif; ?>
                  <?php endwhile; ?>

                <?php else: ?>
                  <li class="dropdown-header">You have 0 new notifications<a href="#"><span
                        class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>No notifications found for this user.</li>
                <?php endif; ?>

                <li class="dropdown-footer">
                  <a href="index.php">Show all notifications</a>
                </li>
              </ul>

              <?php
              $stmt->close();
              $conn->close();
              ?>

              <script>
                function deleteNotification(notificationId) {
                  // Remove the notification item from the DOM
                  var notificationItem = document.getElementById("notification-item-" + notificationId);
                  if (notificationItem) {
                    notificationItem.parentNode.removeChild(notificationItem);
                    // Store the deleted notification in localStorage
                    var deletedNotifications = JSON.parse(localStorage.getItem('deletedNotifications')) || [];
                    deletedNotifications.push(notificationId);
                    localStorage.setItem('deletedNotifications', JSON.stringify(deletedNotifications));
                  } else {
                    console.error('Notification item not found');
                  }
                }

                // Check for deleted notifications on page load
                window.onload = function () {
                  var deletedNotifications = JSON.parse(localStorage.getItem('deletedNotifications')) || [];
                  deletedNotifications.forEach(function (notificationId) {
                    var notificationItem = document.getElementById("notification-item-" + notificationId);
                    if (notificationItem) {
                      notificationItem.parentNode.removeChild(notificationItem);
                    }
                  });
                };
              </script>


























































              <!-- End Notification Nav -->



            <li class="nav-item dropdown pe-3">

              <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <span class="d-none d-md-block dropdown-toggle ps-2">UserID:
                  <?php echo "" . $_SESSION["username"]; ?>
                </span>
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

        <li class="nav-heading">Action Requests</li>

          <li class="nav-item">
            <a class="nav-link " href="uploadrequest(new).php">
              <span>New Request</span>
            </a>
          </li><!-- End Dashboard Nav -->

          <li class="nav-item">
            <a class="nav-link " href="uploadrenewal.php">
              <span>Renewal Request</span>
            </a>
          </li><!-- End Dashboard Nav -->

          <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#actions-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-journal-text"></i><span>Requests Options</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="actions-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="uploadrequest(new).php">
                  <i class="bi bi-circle"></i><span>New Request</span>
                </a>
              </li>
              <li>
                <a href="uploadrenewal.php">
                  <i class="bi bi-circle"></i><span>Renewal Request</span>
                </a>
              </li>
            </ul>
          </li>End Actions Nav -->


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
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
          <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
              <div class="row">

              <!-- USER PROFILE SECTION -->
              <section class="section profile">
                    <div class="row">
                      <div class="col-xl-4">

                        <div class="card">
                          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="assets/img/" alt="Profile" class="rounded-circle">
                            <h2>
                              <?php echo "" . $userData["firstname"] . " " . $userData["lastname"]; ?>
                            </h2>
                            <h3>
                              <?php echo "" . $_SESSION["username"]; ?>
                            </h3>
                            <div class="social-links mt-2">
                              <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                              <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                              <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                              <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="col-xl-8">

                        <div class="card">
                          <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                              <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                  data-bs-target="#profile-overview">Overview</button>
                              </li>

                              <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                  Profile</button>
                              </li>

                              <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                  data-bs-target="#profile-settings">Settings</button>
                              </li>

                              <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                  data-bs-target="#profile-change-password">Change Password</button>
                              </li>

                            </ul>
                            <div class="tab-content pt-2">

                              <div class="tab-pane fade show active profile-overview" id="profile-overview">
                          
                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                  <div class="col-lg-3 col-md-4 label ">Account Name:</div>
                                  <div class="col-lg-9 col-md-8">
                                    <?php echo $userData["firstname"] . " " . $userData["lastname"]; ?>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-lg-3 col-md-4 label">School Campus:</div>
                                  <div class="col-lg-9 col-md-8">Nddu Main Campus</div>
                                </div>

                                <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Email:</div>
                                  <div class="col-lg-9 col-md-8">
                                    <?php echo $userData["email"]; ?>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-lg-3 col-md-4 label">User Type:</div>
                                  <div class="col-lg-9 col-md-8">
                                    <?php echo $userType; ?>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-lg-3 col-md-4 label">Phone:</div>
                                  <div class="col-lg-9 col-md-8">
                                    <?php echo $phoneNumber; ?>
                                  </div>
                                </div>



                              </div>

                              <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form>

                                  <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                      <input name="fullName" type="text" class="form-control" id="fullName"
                                        value="<?php echo "" . $userData["firstname"] . " " . $userData["lastname"]; ?>">
                                    </div>
                                  </div>


                                  <div class="row mb-3">
                                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                    <div class="col-md-8 col-lg-9">
                                      <input name="phone" type="text" class="form-control" id="Phone"
                                        value="<?php echo $phoneNumber; ?>">

                                    </div>
                                  </div>

                                  <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                      <input name="email" type="email" class="form-control" id="Email"
                                        value="<?php echo $userData['email'] ?>">
                                    </div>
                                  </div>

                                  <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                  </div>
                                </form><!-- End Profile Edit Form -->

                              </div>

                              <div class="tab-pane fade pt-3" id="profile-settings">

                                <!-- Settings Form -->
                                <form>

                                  <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">404 Not Found</label>

                                  </div>

                                  <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                  </div>
                                </form><!-- End settings Form -->

                              </div>

                              <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form>

                                  <div class="row mb-3">
                                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                                      Password</label>
                                    <div class="col-md-8 col-lg-9">
                                      <input name="password" type="password" class="form-control" id="currentPassword">
                                    </div>
                                  </div>

                                  <div class="row mb-3">
                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                      <input name="newpassword" type="password" class="form-control" id="newPassword">
                                    </div>
                                  </div>

                                  <div class="row mb-3">
                                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New
                                      Password</label>
                                    <div class="col-md-8 col-lg-9">
                                      <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                                    </div>
                                  </div>

                                  <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Change Password</button>
                                  </div>
                                </form><!-- End Change Password Form -->

                              </div>

                            </div><!-- End Bordered Tabs -->

                          </div>
                        </div>

                      </div>
                    </div>
              </section>

                <!-- REQUIREMENTS Card -->
                <div class="col-xxl-4 col-md-6">
                  <div class="card info-card sales-card">
                    <div class="card-body">
                      <h5 class="card-title">Requirements </h5>
                      <p>1. Copy of Accomplished Application Form. <br> 2. 1pc Recent 2x2 Picture <br> </p>

                      <a href="#target-requirements">
                        <div tabindex="0" class="plusButton">

                          <svg class="plusIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
                            <g mask="url(#mask0_21_345)">
                              <path d="M13.75 23.75V16.25H6.25V13.75H13.75V6.25H16.25V13.75H23.75V16.25H16.25V23.75H13.75Z">
                              </path>
                            </g>
                          </svg>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>

                <!-- STEPS Card -->
                <div class="col-xxl-4 col-md-6">
                  <div class="card info-card sales-card">
                    <div class="card-body">
                      <h5 class="card-title">Steps </h5>
                      <p>1. Download available Application Form from the website.<br> </p>
                      <a href="#target-steps">
                        <div tabindex="0" class="plusButton">
                          <svg class="plusIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
                            <g mask="url(#mask0_21_345)">
                              <path d="M13.75 23.75V16.25H6.25V13.75H13.75V6.25H16.25V13.75H23.75V16.25H16.25V23.75H13.75Z">
                              </path>
                            </g>
                          </svg>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>

                <!-- PARKING REGULATION Card -->
                <div class="col-xxl-4 col-md-6">
                  <div class="card info-card sales-card">
                    <div class="card-body">
                      <h5 class="card-title">Parking Regulation</h5>
                      <p>1. "NO VEHICLE PASS, NO ENTRY".<br> 2. The use of the school parking space and drop off areas is
                        privilege and not a right. </p>
                      <a href="#target-regulations">
                        <div tabindex="0" class="plusButton">
                          <svg class="plusIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
                            <g mask="url(#mask0_21_345)">
                              <path d="M13.75 23.75V16.25H6.25V13.75H13.75V6.25H16.25V13.75H23.75V16.25H16.25V23.75H13.75Z">
                              </path>
                            </g>
                          </svg>
                        </div>
                      </a>
                    </div>
                  </div>
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
                  </div>
                </div>


                <!-- REGISTERED USER'S VEHICLES INFO -->
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
                      <h5 class="card-title">Registered Vehicles</h5>

                      <table class="table table-borderless datatable">
                        <thead class = "thread-container">
                          <tr>
                            <th scope="col">Type of Application</th>
                            <th scope="col">Brand</th>
                            <!-- <th scope="col">Year Model</th> -->
                            <th scope="col">Color</th>
                            <th scope="col">Plate Number</th>
                            <th scope="col">View More</th>
                            <th scope="col">Request Status</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Actions</th>
                            
                            <!-- <th scope="col">Official Receipt Number</th> -->
                            <!-- <th scope="col">Amount Paid</th> -->
                            <!-- <th scope="col">Date Issued</th> -->
                            
                            <!-- <th scope="col">View</th> -->
                            
                            <!-- <th scope="col">Actions</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          include("config.php"); // Assuming you have already established a database connection
                          if (isset($_SESSION["username"])) {
                            $IDnum = $_SESSION["username"];

                            // Prepare and execute a query to fetch firstname and lastname based on the ID number
                            $sql_user = "SELECT firstname, lastname, email FROM users WHERE IDnum = ?";
                            $stmt_user = $conn->prepare($sql_user);
                            $stmt_user->bind_param("s", $IDnum);
                            $stmt_user->execute();
                            $result_user = $stmt_user->get_result();

                            // Check if user data exists
                            if ($result_user->num_rows > 0) {
                              $userData = $result_user->fetch_assoc();

                              // Prepare and execute a query to fetch vehicles data based on the user's IDnum
                              $sql_vehicles = "SELECT * FROM vehicles WHERE user_id = ?";
                              $stmt_vehicles = $conn->prepare($sql_vehicles);
                              $stmt_vehicles->bind_param("s", $IDnum);
                              $stmt_vehicles->execute();
                              $result_vehicles = $stmt_vehicles->get_result();

                              // Check if vehicles data exists
                              if ($result_vehicles->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result_vehicles->fetch_assoc()) {
                                  echo "<tr>";
                                  
                                  echo "<td>" . $row["vehicletype"] . "</td>";
                                  echo "<td>" . $row["brand"] . "</td>";    //Brand
                                  echo "<td>" . $row["color"] . "</td>";    //Color
                                  echo "<td>" . $row["platenumber"] . "</td>";
                                  // echo "<td>" . $row["yearmodel"] . "</td>";    //Year Model
                                  // echo "<td>" . $row["ORnum"] . "</td>";
                                  // echo "<td>" . $row["amountpaid"] . "</td>";
                                  // echo "<td>" . $row["date"] . "</td>";

                                  // VIEW MORE MODAL
                                  echo '<td><button class="viewFilesButton" onclick="onViewMoreButtonClick(' . $row['user_id'] . ', \'' . $row['controlnumber'] . '\')">View More</button></td>';
                                  // // VIEW BUTTON
                                  // echo '<td><button class="viewFilesButton" onclick="onViewButtonClick(' . $row['user_id'] . ', \'' . $row['controlnumber'] . '\')">View</button></td>';
                                  



                                  

// DATA IS NOT APPROPRIATELY ALIGNED............. SLIGHT FRONT END ADJUSTMENT



                                  // Output status badge
                                  echo "<td>";
                                    if ($row['status'] === "accepted") {     
                                        echo "<p class='badge bg-success' style = color:#fff;>" . $row['status'] . "</p>";
                                    } elseif ($row['status'] === "pending") { 
                                      echo "<p class='badge bg-warning'>" . $row['status'] . "</p>";
                                    } elseif ($row['status'] === "rejected") { 
                                      echo "<p class='badge bg-danger' style = color:#fff;>" . $row['status'] . "</p>";
                                    }
                                  echo "</td>";

                                  // Display expiration date only if status is "accepted"
                                  echo "<td>";
                                    if ($row['status'] === "accepted" && $row["Expiration_date"] <= date("Y-m-d h:i:sa")) {   
                                        echo "<p class = 'badge bg-danger' style = color:#fff; >EXPIRED</p>"; 
                                    } elseif ($row['status'] === "accepted") {                       
                                        echo $row["Expiration_date"];
                                    } else {
                                      // Empty cell if status is not "accepted"
                                    }
                                  echo "</td>";


                                  echo
                                    '<td>
                                          <a href="editsection.php?userId=' . $row['user_id'] . '&controlNumber=' . $row['controlnumber'] . '"><button class="btn btn-primary btn-sm">Edit Images</button></a>
                                          <button class="btn btn-danger btn-sm" onclick="onDeleteButtonClick(' . $row['user_id'] . ', \'' . $row['controlnumber'] . '\')">Delete</button>           
                                    </td>';
                        
                                  
                                  echo "</tr>";
                                }
                              } else {
                                echo "<tr><td colspan='9'>0 vehicles found for this user.</td></tr>";
                              }
                            } else {
                              echo "<tr><td colspan='9'>User data not found.</td></tr>";
                            }
                          } else {
                            echo "<tr><td colspan='9'>User not logged in.</td></tr>";
                          }
                          ?>

                        </tbody>
                      </table>

                    </div>

                  </div>
                </div>

                <!-- FEATURES SECTION -->
                <div class="col-12">
                  
                  <!-- REQUIREMENTS SECTION -->
                  <section class="requirements" id="target-requirements">
                    <div class="col-12">
                      <div class="card recent-sales overflow-auto">

                        <div class="card-body">
                          <h5 class="card-title">Requirements:</h5>

                          <div class="requirement-details">
                            <h6>1.) Copy of Accomplished Application Form</h6>
                            <h6>2.) 1pc Recent 2x2 Picture.</h6>
                            <h6>3.) 1 Clear Photocopy of Driver's License of student applicant or the driver who will
                              drop/pick up of the student applicant.</h6>
                            <h6>4.) 1 Clear Photocopy Certificate of Registration of Vehicle.</h6>
                            <h6>5.) 1 Clear Photocopy of updated Official Receipt of Registration of Vehicle.</h6>
                            <h6>6.) 1 Clear Photocopy of recent Validated Subject Load Form of Student Application.</h6>
                            <h6>7.) Old Application Form (For Renewal Application Only); PPO GIA Shall attach this to the
                              recent application form.</h6>
                            <h6>8.) Clean Photocopy of recently validated student ID of student applicant.</h6>
                          </div>

                        </div>

                      </div>
                    </div>
                  </section>


                  <!-- STEPS SECTION -->
                  <section class="steps" id="target-steps">
                    <div class="col-12">
                      <div class="card recent-sales overflow-auto">

                        <div class="card-body">
                          <h5 class="card-title">Steps:</h5>

                          <div class="requirement-details">
                            <h6>1.) Download and secure Application Form from the website.</h6>
                            <h6>2.) Fill in form and attach all requirements needed. Submit form and requirements to the
                              designated action request and wait for the endorsement and approval.</h6>
                            <h6>3.) Once approved, pay vehicle pass processing fee to the cashier and submit official receipt
                              to the the cashier.</h6>
                            <h6>4.) Vehicle Pass sticker will be online generated and placed on the front left windshield of
                              the vehicle or the left front panel of the motorcycle.</h6>
                          </div>

                        </div>

                      </div>
                    </div>
                  </section>


                  <!-- PARKING REGULATIONS SECTION -->
                  <section class="steps" id="target-regulations">
                    <div class="col-12">
                      <div class="card recent-sales overflow-auto">

                        <div class="card-body">
                          <h5 class="card-title">Parking Regulation:</h5>

                          <div class="requirement-details">
                            <h6>1.) "NO VEHICLE PASS, NO ENTRY".</h6>
                            <h6>2.) The use of the school parking space and drop off areas is privilege and not a right. NDDU
                              reserves the right to refuse any vehicle violating its rules and regulations.</h6>
                            <h6>3.) All vehicles are subject to security inspection upon entry and exit. Roll down window for
                              inspection</h6>
                            <h6>4.) A valid NDDU school ID of the student must be presented to the security personnel upon
                              entry.</h6>
                            <h6>5.) Passengers without valid NDDU school ID shall not be allowed to enter the school premises.
                              He/She must embark outside of the campus.</h6>
                            <h6>6.) Once inside the campus, the following must be strictly observed:</h6>
                            <p>6.1: University Rules and Regulations.
                            <p>
                            <p>6.2: Courtesy to security personnel.
                            <p>
                            <p>6.3: No overspeeding (speed limit 10km/h).
                            <p>
                            <p>6.4: No parking or sharp curves and corners were traffic is obstructed.
                            <p>
                            <p>6.5: No loud radio/stereo music.
                            <p>
                            <p>6.6: No blowing of horns.
                            <p>
                          </div>

                        </div>

                      </div>
                    </div>
                  </section>

                </div>

              </div>


              


                  <!-- CONTACT SECTION -->
                  <section class="section contact">

                    <div class="row gy-4">

                      <div class="col-xl-8">

                        <div class="row">
                          <div class="col-lg-6">
                            <div class="info-box card">
                              <i class="bi bi-geo-alt"></i>
                              <h3>Address</h3>
                              <p>Notre Dame Dadiangas University,<br>General Santos City</p>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="info-box card">
                              <i class="bi bi-telephone"></i>
                              <h3>Call Us</h3>
                              <p>xxxxxxxxxxxx<br>xxxxxxxxxxxxxx</p>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="info-box card">
                              <i class="bi bi-envelope"></i>
                              <h3>Email Us</h3>
                              <p>email@example.com<br>contact@example.com</p>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="info-box card">
                              <i class="bi bi-clock"></i>
                              <h3>Open Hours</h3>
                              <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                            </div>
                          </div>
                        </div>

                      </div>

                    </div>

                  </section>
             
            </div>
          </div>

        </section>

      </main><!-- End #main -->















      
    <!-- VIEWMORE MODAL -->
    <!-- ============================================================================================ -->
     <!-- ============================================================================================ -->
    <div id = "ViewMoreModal" class ="modal">
      <div class="outscroll">
      <div class = "modal-content">
        <span class="close" onclick="closeViewMoreModal()">&times;</span>
        <h2>Information:</h2>

        <form id="editForm" method="post" action="update_vehicle.php" enctype="multipart/form-data">
        <!-- Form inputs for updating vehicle details -->
        <div class="modal-details" style ="margin: 15px;">
          <label for="vehicletype">Vehicle Type:</label>
          <input type="text" name="vehicletype" id="vehicletype" class="form-control" placeholder="Vehicle Type">
        </div>
        <div class="modal-details" style ="margin: 15px;">
          <label for="brand">Brand:</label>
          <input type="text" name="brand" id="brand" class="form-control" placeholder="Brand">
        </div>
        <div class="modal-details" style ="margin: 15px;">
          <label for="yearmodel">Year Model:</label>
          <input type="text" name="yearmodel" id="yearmodel" class="form-control" placeholder="Year Model">
        </div>
        <div class="modal-details" style ="margin: 15px;">
          <label for="color">Color:</label>
          <input type="text" name="color" id="color" class="form-control" placeholder="Color">
        </div>
        <div class="modal-details" style ="margin: 15px;">
          <label for="platenumber">Plate Number:</label>
          <input type="text" name="platenumber" id="platenumber" class="form-control" placeholder="Plate Number">
        </div>
        <div class="modal-details" style ="margin: 15px;">
          <label for="ORnum">Official Receipt Number (OR#):</label>
          <input type="text" name="ORnum" id="ORnum" class="form-control" placeholder="OR Number">
        </div>
        <!-- Hidden input to store the control number -->
        <input type="hidden" name="controlnumber" id="controlnumber">
        <div class="modal-details" style ="margin: 15px;">
          <!-- <input type="submit" value="Update" class="btn btn-primary" style="background-color: red; color: #fff; border: none;"> -->
          <button onclick ="onUpdateButtonClick" class="btn btn-primary" >Update</button>
          <!-- <button onclick ="onEditImageButtonClick" class="btn btn-primary">Edit Images</button> -->
          <!-- <button onclick ="onDeleteButtonClick()" class="btn btn-danger" >Delete</button> -->
        

          <!-- onEditButtonClick -->
          
        </div>
        </form>

        <h2>Uploaded Files:</h2>
        <div id="imageGallery" class="image-gallery">
        </div>

        

      </div>
      </div>
      
    </div>
    <!-- ============================================================================================ -->
    <!-- ============================================================================================ -->


















      
      <!-- Modal for View Button
      <div id="fileViewerModal" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closefileViewerModal()">&times;</span>
          <h2>Uploaded Files</h2>
          <div id="imageGallery" class="image-gallery"></div>
        </div>
      </div> -->



      <!-- Outer container for the form
<div class="form-container">
   Modal for editing vehicle information 
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeeditModal()">&times;</span>
      <h2>Edit Vehicle Information</h2>
      
    </div>
  </div>
</div> -->








      <!-- ======= Footer ======= -->
      <footer id="footer" class="footer">
        <div class="copyright">
          &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
        </div>
      </footer><!-- End Footer -->

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

        

        // //  MODAL FOR VIEW BUTTON
        // function onViewButtonClick(userId, controlNumber) {
        //   var xhr = new XMLHttpRequest();
        //   xhr.onreadystatechange = function () {
        //     if (xhr.readyState == 4) {
        //       if (xhr.status == 200) {
        //         var images = JSON.parse(xhr.responseText);
        //         displayImages(images);
        //       } else {
        //         console.error('Error fetching images:', xhr.status);
        //       }
        //     }
        //   };
        //   xhr.open('GET', 'fetch_images.php?userId=' + userId + '&controlNumber=' + controlNumber, true);
        //   xhr.send();
          
        // }



        // MODAL FOR VIEW MORE
        //==========================================================================================
        //==========================================================================================
        function onViewMoreButtonClick(userId, controlNumber)
        {

          
        //  TESTING CODE SECTION

        // Fetch images
        var xhrImages = new XMLHttpRequest();
        xhrImages.onreadystatechange = function() {
            if (xhrImages.readyState === 4 && xhrImages.status === 200) {
                var images = JSON.parse(xhrImages.responseText);
                displayImages(images);
            }
        };
        xhrImages.open('GET', 'fetch_images.php?userId=' + userId + '&controlNumber=' + controlNumber, true);
        xhrImages.send();

        // Fetch vehicle data
        var xhrVehicleData = new XMLHttpRequest();
        xhrVehicleData.onreadystatechange = function() {
            if (xhrVehicleData.readyState === 4 && xhrVehicleData.status === 200) {
                var data = JSON.parse(xhrVehicleData.responseText);
                // Populate form fields with existing data
                document.getElementById('vehicletype').value = data.vehicletype;
                document.getElementById('brand').value = data.brand;
                document.getElementById('yearmodel').value = data.yearmodel;
                document.getElementById('color').value = data.color;
                document.getElementById('platenumber').value = data.platenumber;
                document.getElementById('ORnum').value = data.ORnum;
                // Set control number
                document.getElementById('controlnumber').value = controlNumber;
                // Display the modal
                document.getElementById('editModal').style.display = 'block';
            }
        };
        xhrVehicleData.open('GET', 'fetch_vehicle_data.php?controlnumber=' + controlNumber, true);
        xhrVehicleData.send();









          // Show the modal
          var modal = document.getElementById('ViewMoreModal');
          modal.style.display = 'block';
          
        }

        //==========================================================================================
        //==========================================================================================




//BUTTONS JS
function onUpdateButtonClick(userId, controlNumber) {
            // Retrieve updated values from form fields
            var updatedVehicle = {
                vehicletype: document.getElementById('vehicletype').value,
                brand: document.getElementById('brand').value,
                yearmodel: document.getElementById('yearmodel').value,
                color: document.getElementById('color').value,
                platenumber: document.getElementById('platenumber').value,
                ORnum: document.getElementById('ORnum').value,
                controlnumber: document.getElementById('controlnumber').value
            };

            // Make an AJAX request to update the vehicle data
            var xhrUpdate = new XMLHttpRequest();
            xhrUpdate.open('POST', 'update_vehicle.php', true);
            xhrUpdate.setRequestHeader('Content-Type', 'application/json');
            xhrUpdate.onreadystatechange = function () {
                if (xhrUpdate.readyState === 4 && xhrUpdate.status === 200) {
                    // Handle response from server (if needed)
                    // For example, display success message or update UI
                    console.log(xhrUpdate.responseText); // Log response to console
                }
            };
            xhrUpdate.send(JSON.stringify(updatedVehicle)); // Send updated vehicle data as JSON string
        }


        function onEditImageButtonClick(userId, controlNumber) {
            // Redirect to editsection.php with necessary parameters
            var editUrl = 'editsection.php?userId=' + userId + '&controlNumber=' + controlNumber;
            window.location.href = editUrl;
        }


        function onDeleteButtonClick(userId, controlNumber) {
    // Confirm with the user before proceeding with the deletion
    if (confirm('Are you sure you want to delete this vehicle record?')) {
        // Create a new XMLHttpRequest object
        var xhrDelete = new XMLHttpRequest();

        // Define the request method, URL, and set it to asynchronous
        xhrDelete.open('POST', 'delete_vehicle.php', true);

        // Set the request header for JSON data
        xhrDelete.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Prepare the data to be sent in the request
        var params = 'user_id=' + encodeURIComponent(userId) + '&controlnumber=' + encodeURIComponent(controlNumber);

        // Define what to do on load
        xhrDelete.onload = function () {
            if (xhrDelete.readyState === 4 && xhrDelete.status === 200) {
                // Handle success response
                console.log(xhrDelete.responseText); // Log response to console
                alert('Vehicle record deleted successfully.'); // Notify user
                location.reload(); // Reload the page to reflect changes
            } else {
                // Handle error response
                console.error('Error deleting vehicle record: ' + xhrDelete.responseText);
                alert('Error deleting vehicle record. Please try again.'); // Notify user
            }
        };

        // Send the request with the defined parameters
        xhrDelete.send(params);
    }
}

        


        
        
        // function onDeleteButtonClick(userId, controlNumber) {
        //     // Confirm with the user before proceeding with the deletion
        //     if (confirm('Are you sure you want to delete this vehicle record?')) {
        //         // Create a new XMLHttpRequest object
        //         var xhrDelete = new XMLHttpRequest();

        //         // Define the request method, URL, and set it to asynchronous
        //         xhrDelete.open('POST', 'delete_vehicle.php', true);

        //         // Set the request header for JSON data
        //         xhrDelete.setRequestHeader('Content-Type', 'application/json');

        //         // Prepare the data to be sent as a JSON object
        //         var data = {
        //             user_id: userId,
        //             controlnumber: controlNumber
        //         };

        //         // Define what to do on load
        //         xhrDelete.onload = function () {
        //             if (xhrDelete.readyState === 4 && xhrDelete.status === 200) {
        //                 // Handle success response
        //                 console.log(xhrDelete.responseText); // Log response to console
        //                 alert('Vehicle record deleted successfully.'); // Notify user
        //                 location.reload(); // Reload the page to reflect changes
        //             } else {
        //                 // Handle error response
        //                 console.error('Error deleting vehicle record: ' + xhrDelete.responseText);
        //                 alert('Error deleting vehicle record. Please try again.'); // Notify user
        //             }
        //         };

        //         // Send the request with the JSON data
        //         xhrDelete.send(JSON.stringify(data));
        //     }
        // }



        




      // Function to update notification count
      function updateNotificationCount() {
          // Make an AJAX request to fetch the latest notification count
          var xhr = new XMLHttpRequest();
          xhr.open('GET', 'fetch_notification_count.php', true);
          xhr.onreadystatechange = function () {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  // Parse the JSON response
                  var count = JSON.parse(xhr.responseText).count;
                  // Update the badge number with the latest count
                  document.querySelector('.badge-number').textContent = count;
              }
          };
          xhr.send();
      }

      // Call the updateNotificationCount function initially
      updateNotificationCount();

      // Call the updateNotificationCount function periodically (every 60 seconds in this example)
      setInterval(updateNotificationCount, 20000); // 60000 milliseconds = 1 minute












        




        

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

          
        }



        // ================================================================
        // CLOSE MODALS SECTION
        // ================================================================

        // Function to close Viewmore Modal
        function closeViewMoreModal(){
          var modal = document.getElementById('ViewMoreModal')
          modal.style.display = 'none'; // Hide the modal
        }

        // // Function to close fileViewerModal
        // function closefileViewerModal() {
        //   var modal = document.getElementById('fileViewerModal');
        //   modal.style.display = 'none'; // Hide the modal
        // }
        // // Function to close editmodal
        // function closeeditModal() {
        //   var modal = document.getElementById('editModal');
        //   modal.style.display = 'none'; // Hide the modal
        // }

        // ================================================================

      </script>



      <script>
        function toggleSidebar() {
          var sidebar = document.getElementById("sidebar");
          if (sidebar.style.width === "300px") {
            sidebar.style.width = "0";
          }
          else {
            sidebar.style.width = "300px";
          }
        }
      </script>






    </body>

    </html>
  <?php } else {
    echo '<input type="button" value="back" onclick="window.location=\'index.php\'">';
    echo "You already have 10 requests";
  }
?>
<?php
}
$conn->close();
?>
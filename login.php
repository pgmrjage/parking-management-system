<?php
session_start(); // Start session at the beginning

$server = "localhost";
$username = "root";
$password = "";
$database = "nddusticker";
$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["loginbtn"])) {
  $IDnum = $_POST["username"];
  $password = $_POST["passw"];
  if ($IDnum == "" || $password == "") {
    echo "Both fields are required";
    exit(); // Exit after displaying error
  }

  // No need to sanitize IDnum as it's an integer

  $sql = "SELECT IDnum, password, type FROM users WHERE IDnum = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $IDnum); // Assuming IDnum is an integer
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();

  if ($data == NULL) {
    echo '<script>alert("No data found"); window.location.href = "login.php";</script>';
    exit(); // Exit if no data found
  }

  // Directly compare the password from the database with the entered password
  if ($password != $data["password"]) {
    echo '<script>alert("Wrong ID Number or Password. Please try again"); window.location.href = "login.php";</script>';
    exit(); // Exit if password is incorrect
  }

  if ($data['type'] == 1) { // Assuming type 1 represents admin
    $_SESSION['username'] = $IDnum;
    $_SESSION['type'] = $data['type'];
    header("Location: admin.php");
    exit(); // Exit after redirecting
  } else {
    $_SESSION['username'] = $IDnum;
    $_SESSION['type'] = $data['type'];
    header("Location: checker.php");
    exit(); // Exit after redirecting
  }
}
?>



<!-- Your HTML code goes here -->


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pages / Login - NiceAdmin Bootstrap Template</title>
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

  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>
                  <form method="post" class=" row g-3 needs-validation" novalidate>
                    <div class="col-12">
                      <label for="username" class="form-label">ID Number</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control" id="username" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="passw" class="form-label">Password</label>
                      <input type="password" name="passw" class="form-control" id="passw" required>
                      <div class="invalid-feedback">Please enter your password!</div>

                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" style="background-color: #00843D; border:none; "type="submit" name="loginbtn">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have an account? <a href="register.php">Create an account</a></p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
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


</body>

</html>
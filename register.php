<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Create New Account</title>
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

  <?php
  include('config.php');

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $IDnum = $_POST['IDnum'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];
    $usertype = $_POST['usertype'];

    // Perform SQL injection prevention and other necessary validation
  
    // Insert the data into the database
    $query = "INSERT INTO users (firstname, lastname, email, IDnum, password, `Phone number`, User_Type) VALUES ('$firstname', '$lastname', '$email', '$IDnum', '$password', '$phone_number', '$usertype')";
    $result = mysqli_query($conn2, $query);

    if ($result) {
      echo "<script>alert('Account created successfully!');</script>";
    } else {
      echo "<script>alert('Error creating account. Please try again.');</script>";
    }
  }
  ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <main>
      <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="pt-4 pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                      <p class="text-center small">Enter your personal details to create account</p>
                    </div>
                    <form class="row g-3 needs-validation" novalidate>
                      <div class="col-12">
                        <label for="firstname" class="form-label">Firstname</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" required>
                        <div class="invalid-feedback">Please, enter your name!</div>
                      </div>

                      <div class="col-12">
                        <label for="lastname" class="form-label">Surname</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" required>
                        <div class="invalid-feedback">Please, enter your name!</div>
                      </div>

                      <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email" required>
                        <div class="invalid-feedback">Please enter a valid Email address!</div>
                      </div>

                      <div class="col-12">
                        <label for="IDnum" class="form-label">Username / ID Number</label>
                        <input type="text" name="IDnum" class="form-control" id="IDnum" required>
                      </div>

                      <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                        <div class="invalid-feedback">Please enter your password!</div>
                      </div>

                      <div class="col-12">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" id="phone_number" required>
                        <div class="invalid-feedback">Please enter your phone number!</div>
                      </div>

                      <div class="col-12"> <!-- Added mb-3 class for margin-bottom -->
                        <label for="usertype" class="form-label">Type of User:</label>
                        <select id="usertype" name="usertype" class="form-control" id="usertype" required>>
                          <option value="student">Student</option>
                          <option value="Employee">Employee of NDDU</option>
                        </select>
                      </div>

                      <div class="pt-4 pb-2">
                        <button class="btn btn-primary w-100" style="background-color: #00843D; border:none; type="submit" name="submit" id="btn">Create Account</button>
                        <p class="small mb-0">Already have an account? <a href="login.php">Log in</a></p>
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
  </form>

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
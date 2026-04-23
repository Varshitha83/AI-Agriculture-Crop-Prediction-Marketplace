<?php
session_start();
require('../sql.php'); // DB connection

// Make sure farmer is logged in and we know his email
if (!isset($_SESSION['farmer_login_user']) || $_SESSION['farmer_login_user'] == '') {
    // If session lost, send back to login
    header("Location: flogin.php");
    exit;
}

$user = $_SESSION['farmer_login_user'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../assets/img/logo.png" />
  <title>Agriculture Portal</title>

  <!-- CSS & fonts -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

  <link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css "/>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/creativetim.min.css" type="text/css">
</head>

<body class="bg-white" id="top" onload="send_otp();">
  <!-- Navbar -->
  <nav id="navbar-main"
       class="navbar navbar-main navbar-expand-lg bg-default navbar-light position-sticky top-0 shadow py-0">
    <div class="container">
      <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
        <li class="nav-item dropdown">
          <a href="../index.php" class="navbar-brand mr-lg-5 text-white">
            <img src="../assets/img/nav.png" />
          </a>
        </li>
      </ul>

      <button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbar_global"
              aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon text-white"></span>
      </button>

      <div class="navbar-collapse collapse bg-default" id="navbar_global">
        <div class="navbar-collapse-header">
          <div class="row">
            <div class="col-10 collapse-brand">
              <a href="../index.php">
                <img src="../assets/img/nav.png" />
              </a>
            </div>
            <div class="col-2 collapse-close bg-danger">
              <button type="button" class="navbar-toggler" data-toggle="collapse"
                      data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false"
                      aria-label="Toggle navigation">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>

        <ul class="navbar-nav align-items-lg-center ml-auto">
          <li class="nav-item">
            <a href="../contact.php" class="nav-link">
              <span class="text-white nav-link-inner--text">
                <i class="text-white fas fa-address-card"></i> Contact
              </span>
            </a>
          </li>

          <li class="nav-item">
            <div class="dropdown show">
              <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-white nav-link-inner--text">
                  <i class="text-white fas fa-user-plus"></i> Sign Up
                </span>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="fregister.php">Farmer</a>
                <a class="dropdown-item" href="../customer/cregister.php">Customer</a>
              </div>
            </div>
          </li>

          <li class="nav-item">
            <div class="dropdown show">
              <a class="nav-link dropdown-toggle text-success" href="#" role="button" id="dropdownMenuLink"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-success nav-link-inner--text">
                  <i class="text-success fas fa-sign-in-alt"></i> Login
                </span>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="flogin.php">Farmer</a>
                <a class="dropdown-item" href="../customer/clogin.php">Customer</a>
                <a class="dropdown-item" href="../admin/alogin.php">Admin</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <section class="section section-lg" style="background-image: url('../assets/img/far_register.jpeg'); background-size: cover; background-position: center; background-attachment: fixed; position: relative; min-height: 700px;">
    <div style="position:absolute; top:0; left:0; right:0; bottom:0; background-color: rgba(0,0,0,0.4);"></div>

    <div class="container" style="position: relative; z-index: 1;">

      <div class="row">
        <div class="col-md-8 mx-auto text-center">
          <span class="badge badge-info badge-pill mb-3">Login</span>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-8 mb-3 mx-auto text-center">
          <div class="nav nav-tabs nav-fill bg-gradient-default" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active font-weight-bold text-warning" id="nav-home-tab" data-toggle="tab"
               href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
              2 Factor Authentication
            </a>
          </div>

          <div class="tab-content py-3 px-3 px-sm-0 bg-gradient-inf" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
              <div class="card card-body" style="background-color: transparent; border: none;">

                <form>
                  <!-- OTP sent -->
                  <div class="alert alert-success alert-dismissible fade show text-center"
                       style="display: none;" id="popup" role="alert">
                    <strong class="text-center text-dark">OTP Sent Successfully</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <!-- Invalid OTP -->
                  <div class="alert alert-danger alert-dismissible fade show text-center"
                       style="display: none;" id="invalid" role="alert">
                    <strong class="text-center text-dark">Invalid OTP</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="form-group row">
                    <label for="otp" class="col-md-2 col-form-label">
                      <h6 class="text-white font-weight-bold">Enter OTP</h6>
                    </label>

                    <div class="col-md-6">
                      <input type="text" id="otp" class="form-control" required placeholder="Enter OTP" name="farmer_otp">
                      <span id="otp_error" class="field_error"></span>
                    </div>

                    <div class="offset-md-1 col-md-3">
                      <button class="btn btn-info btn-block text-dark" type="button" onclick="send_otp()">
                        ReSend OTP
                      </button>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="offset-md-2 col-md-10">
                      <button type="button" class="btn btn-success btn-block text-dark" onclick="submit_otp()">
                        Submit
                      </button>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <?php require("footer.php"); ?>

  <!-- JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"
          integrity="sha384-vjEeZqQfNQnAxm8FpU0uKbjrKMg09uXBJgp7wa9u0J7pKnz03Wx0A0RduCMsZ71" crossorigin="anonymous"></script>

  <script>
  // Send OTP (called on page load and on "Resend OTP")
  function send_otp () {
    $.ajax({
      url: "fsend_otp.php",
      type: "POST",
      success: function(result){
        console.log("FSEND_OTP RESPONSE:", result);

        if (result === 'yes') {
          $("#popup").css({'display':'block'});
          $("#popup").fadeTo(2000, 500).slideUp(500, function(){
            $("#popup").slideUp(500);
          });
        } else if (result === 'no_session_email') {
          alert("Session email not found. Please login again.");
        } else if (result === 'no_db_email') {
          alert("Account not found in database.");
        } else if (result === 'mail_error') {
          alert("Could not send OTP email. Please try again.");
        } else {
          alert("Unknown error: " + result);
        }
      },
      error: function(xhr, status, error){
        console.error("AJAX error:", status, error);
        alert("Failed to contact server.");
      }
    });
  }

  // Verify OTP
  function submit_otp(){
    var otp = $('#otp').val();
    $.ajax({
      url: 'fcheck_otp.php',
      type: 'POST',
      data: { otp: otp },
      success: function(result){
        console.log("OTP CHECK RESPONSE:", result);

        if (result === 'yes') {
          window.location = 'fprofile.php';
        }
        else if (result === 'wrong') {
          $("#invalid").css({'display':'block'});
          $("#invalid").fadeTo(2000, 500).slideUp(500, function(){
            $("#invalid").slideUp(500);
          });
        }
        else if (result === 'expired') {
          alert("OTP expired. Please click Resend OTP to get a new code.");
        }
        else if (result === 'locked') {
          alert("Too many wrong attempts. Please request a new OTP or try again later.");
        }
        else if (result === 'not_found') {
          alert("Account not found. Please login again.");
        }
        else if (result === 'missing') {
          alert("Please enter the OTP.");
        }
        else {
          alert("Something went wrong. Please try again.");
        }
      },
      error: function(xhr, status, error){
        console.error("AJAX error:", status, error);
        alert("Failed to contact server.");
      }
    });
  }
  </script>
</body>
</html>

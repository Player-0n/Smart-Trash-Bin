<?php
session_start();
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">

  <!--====== Title ======-->
  <title> Smart Trash Bin </title>

  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!--====== Favicon Icon ======-->
  <link rel="shortcut icon" href="../assets/global/images/stb-logo.png" type="image/png">

  <!--====== Animate CSS ======-->
  <link rel="stylesheet" href="../assets/landing-page/css/animate.css">

  <!--====== Glide CSS ======-->
  <link rel="stylesheet" href="../assets/landing-page/css/tiny-slider.css">

  <!--====== Line Icons CSS ======-->
  <link rel="stylesheet" href="../assets/landing-page/css/LineIcons.2.0.css">

  <!--====== Bootstrap CSS ======-->
  <link rel="stylesheet" href="../assets/landing-page/css/bootstrap-5.0.0-beta1.min.css">

  <!--====== Default CSS ======-->
  <link rel="stylesheet" href="../assets/landing-page/css/default.css">

  <!--====== Style CSS ======-->
  <link rel="stylesheet" href="../assets/landing-page/css/style.css">
  <link rel="stylesheet" href="../assets/landing-page/css/custom.css">

  <!-- Bootstrap 5 Icons CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>

  <!--[if IE]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

  <!--====== HEADER PART START ======-->
  <section id="home" class="header_area">

    <header id="header_navbar" class="header_navbar d-flex align-items-center fixed-top" style="height: 100px">

      <div class="container">

        <div class="row">

          <div class="col-lg-12">

            <nav class="navbar navbar-expand-lg">

              <a class="navbar-brand" href="index.php">
                <img src="../assets/global/images/stb-logo.png" alt="STB Logo">
                <img src="../assets/global/images/stb-name.png" alt="STB Name">
              </a>

              <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                  <li class="nav-item"><a class="page-scroll active" href="#home">Home</a></li>
                  <li class="nav-item"><a class="page-scroll" href="#about">About</a></li>
                  <li class="nav-item"><a class="page-scroll" href="#services">Services</a></li>
                  <li class="nav-item"><a class="page-scroll" href="#team">Team</a></li>
                </ul>
              </div>

              <div class="dropdown" style="padding-left: 20px;">

                <button class="btn btn-primary dropdown-toggle" type="button" id="signInDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  Sign In
                </button>

                <ul class="dropdown-menu" aria-labelledby="signInDropdown">
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#adminModal"> Sign in as Admin </a></li>
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#studentModal"> Sign in as Student </a></li>
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#personnelModal"> Sign in as Personnel </a></li>
                </ul>

              </div>

            </nav>

          </div>

        </div>

      </div>

    </header>

    <!-- Modal for Admin Sign In -->
    <div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">

      <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="adminModalLabel"> Admin Login </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../process/admin/admin-auth.php" method="POST">

            <div class="modal-body">

              <div class="mb-3">

                <label for="admin_email" class="form-label"> Email Address: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  </div>

                  <input type="email" class="form-control" id="admin_email" name="admin-email" placeholder="Enter Email Address..." required>

                </div>

              </div>

              <div class="mb-3">

                <label for="admin_password" class="form-label"> Password: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                  </div>

                  <input type="password" class="form-control" id="admin_password" name="admin-password" placeholder="Enter Password..." required>

                </div>

              </div>

              <div class="form-check mt-2 d-flex justify-content-between align-items-center">

                <div>
                  <input class="form-check-input" type="checkbox" id="show-admin-password" onclick="togglePasswords('admin_password')">
                  <label class="form-check-label" for="show-admin-password"> Show Password </label>
                </div>

                <div>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#forgot-admin-password" data-bs-dismiss="modal"> Forgot Password? </a>
                </div>

              </div>

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="admin-login"> Login </button>
            </div>

          </form>

        </div>

      </div>

    </div>

    <!-- Modal for Admin Forgot Password -->
    <div class="modal fade" id="forgot-admin-password" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">

      <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel2"> Forgot Admin Password </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../process/admin/admin-auth.php" method="POST">

            <div class="modal-body">

              <div class="mb-3">
                <h6> Please enter your <strong>email address</strong> to continue. </h6>
              </div>

              <div class="mb-3">

                <label for="fa_email_address" class="form-label"> Email Address: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  </div>

                  <input type="email" class="form-control" id="fa_email_address" name="email-address" placeholder="Enter Email Address..." required>

                </div>

              </div>

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="verify-account"> Reset Password </button>
              <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#adminModal" data-bs-dismiss="modal"> 
                Back to Login 
              </button>
            </div>

          </form>

        </div>

      </div>

    </div>

    <!-- Modal for Student Sign In -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">

      <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="adminModalLabel"> Student Login </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../process/student/student-auth.php" method="POST">

            <div class="modal-body">

              <div class="mb-3">

                <label for="student_lrn" class="form-label"> Student LRN: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                  </div>

                  <input type="text" class="form-control" id="student_lrn" name="student-lrn" required placeholder="Enter Student LRN...">

                </div>

              </div>

              <div class="mb-3">

                <label for="student_password" class="form-label"> Password: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                  </div>

                  <input type="password" class="form-control" id="student_password" name="student-password" required placeholder="Enter Password...">

                </div>

              </div>

              <div class="form-check mt-2 d-flex justify-content-between align-items-center">

                <div>
                  <input class="form-check-input" type="checkbox" id="show-student-password" onclick="togglePasswords('student_password')">
                  <label class="form-check-label" for="show-student-password"> Show Password </label>
                </div>

                <div>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#forgot-student-password" data-bs-dismiss="modal"> Forgot Password? </a>
                </div>

              </div>

            </div>

            <div class="modal-footer">
              <p>
                Don't have an account?
                <a href="student/register-account.php">Click here to sign up</a>
              </p>
              <button type="submit" class="btn btn-primary" name="login-student"> Login </button>
            </div>

          </form>

        </div>

      </div>

    </div>

    <!-- Modal for Student Forgot Password -->
    <div class="modal fade" id="forgot-student-password" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">

      <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel2"> Forgot Student Password </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../process/student/student-auth.php" method="POST">

            <div class="modal-body">

              <div class="mb-3">
                <h6>
                  Please enter your <strong>email address</strong> and 
                  <strong>student LRN</strong> to continue. 
                </h6>
              </div>

              <div class="mb-3">

                <label for="fs_email_address" class="form-label"> Email Address: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  </div>

                  <input type="email" class="form-control" id="fs_email_address" name="email-address" placeholder="Enter Email Address..." required>

                </div>

              </div>

              <div class="mb-3">

                <label for="fs_student_lrn" class="form-label"> Learner Reference Number(LRN): </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                  </div>

                  <input type="text" class="form-control" id="fs_student_lrn" name="student-lrn" placeholder="Enter Student LRN..." required>

                </div>

              </div>

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="verify-account"> Reset Password </button>
              <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#studentModal" data-bs-dismiss="modal"> 
                Back to Login 
              </button>
            </div>

          </form>

        </div>

      </div>

    </div>

    <!-- Modal for Personnel Sign In -->
    <div class="modal fade" id="personnelModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">

      <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="adminModalLabel"> Personnel Login </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../process/personnel/personnel-auth.php" method="POST">

            <div class="modal-body">

              <div class="mb-3">

                <label for="personnel_email" class="form-label"> Email Address: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  </div>

                  <input type="email" class="form-control" id="personnel_email" name="personnel-email" required placeholder="Enter Email Address...">

                </div>

              </div>

              <div class="mb-3">

                <label for="personnel_password" class="form-label"> Password: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                  </div>

                  <input type="password" class="form-control" id="personnel_password" name="personnel-password" required placeholder="Enter Password...">

                </div>

              </div>

              <div class="form-check mt-2 d-flex justify-content-between align-items-center">

                <div>
                  <input class="form-check-input" type="checkbox" id="show-personnel-password" onclick="togglePasswords('personnel_password')">
                  <label class="form-check-label" for="show-personnel-password"> Show Password </label>
                </div>

                <div>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#forgot-personnel-password" data-bs-dismiss="modal"> Forgot Password? </a>
                </div>

              </div>

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="personnel-login"> Login </button>
            </div>

          </form>

        </div>

      </div>

    </div>

    <!-- Modal for Personnel Forgot Password -->
    <div class="modal fade" id="forgot-personnel-password" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">

      <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel2"> Forgot Personnel Password </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../process/personnel/personnel-auth.php" method="POST">

            <div class="modal-body">

              <div class="mb-3">
                <h6> Please enter your <strong>email address</strong> to continue. </h6>
              </div>

              <div class="mb-3">

                <label for="fp_email_address" class="form-label"> Email Address: </label>

                <div class="input-group">

                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  </div>

                  <input type="email" class="form-control" id="fp_email_address" name="email-address" placeholder="Enter Email Address..." required>

                </div>

              </div>

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="verify-account"> Reset Password </button>
              <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#personnelModal" data-bs-dismiss="modal"> 
                Back to Login 
              </button>
            </div>

          </form>

        </div>

      </div>

    </div>

    <div class="header_hero">

      <div class="single_hero bg_cover d-flex align-items-center" style="background-image: url(../assets/landing-page/images/Recycle4.png)">

        <div class="container">

          <div class="row justify-content-center">

            <div class="col-lg-8 col-md-10">

              <div class="hero_content text-center">

                <h2 class="hero_title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">
                  Smarter Waste Management</br> for a Greener Future
                </h2>

                <p class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.5s">
                  Our Smart Trash Bin makes recycling easy and efficient. <br class="d-none d-xl-block">
                </p>

                <a href="#about" class="main-btn wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.8s">
                  Explore
                </a>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>
  <!--====== HEADER PART ENDS ======-->

  <div class="section-divider"></div>

  <!--====== ABOUT PART START ======-->
  <section id="about" class="">

    <div class="about_area">

      <div class="container">

        <div class="row">

          <div class="col-lg-6">

            <div class="about_content pt-120 pb-130">

              <div class="section_title pb">

                <h4 class="title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">About Us</h4>

                <p class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.4s">We are dedicated to creating smart
                  solutions for a cleaner and greener future. Our Smart Trash Bin uses advanced detection
                  technology to automatically segregate plastic, paper, and metal, ensuring proper
                  disposal and reducing environmental impact.
                </p>

                <p class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.6s">More than just waste collection,
                  we reward users with points for every correct disposal, which can be redeemed through
                  our online store. Together, we make recycling easy, fun, and impactful for the community
                  and the planet.
                </p>

              </div>

            </div>

          </div>

        </div>

      </div>

      <div class="about_image bg_cover wow fadeInLeft" data-wow-duration="1.3s" data-wow-delay="0.2s"
        style="background-image: url(../assets/landing-page/images/Recycle7.jpg)">
      </div>

    </div>

  </section>
  <!--====== ABOUT PART ENDS ======-->

  <div class="section-divider"></div>

  <!--====== FEATURES PART START ======-->
  <section id="services" class="features_area pt-120">

    <div class="container">

      <div class="row justify-content-center">

        <div class="col-lg-6">

          <div class="section_title text-center pb-25">

            <h4 class="title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">
              Our Services
            </h4>

            <p class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.4s">
              Enhancing waste management with smart Arduino-powered technology. Our system sorts waste efficiently,
              tracks bin capacity in real time, and rewards users for responsible disposal—creating a cleaner and greener future!
            </p>

          </div>

        </div>

      </div>

      <div class="row">

        <div class="col-lg-4 col-md-6">

          <div class="single_features text-center mt-60 wow fadeInUp" data-wow-duration="1.3s"
            data-wow-delay="0.2s">

            <i class="bi bi-recycle"></i>

            <h4 class="features_title"> Automated Waste Sorting </h4>

            <p>
              Uses Arduino-based sensors to help categorize waste for better recycling.
            </p>

          </div>

        </div>

        <div class="col-lg-4 col-md-6">

          <div class="single_features text-center mt-60 wow fadeInUp" data-wow-duration="1.3s"
            data-wow-delay="0.3s">

            <i class="bi bi-activity"></i>

            <h4 class="features_title"> Smart Monitoring System </h4>

            <p>
              Tracks bin capacity in real time and notifies when it's time for collection.
            </p>

          </div>
        </div>

        <div class="col-lg-4 col-md-6">

          <div class="single_features text-center mt-60 wow fadeInUp" data-wow-duration="1.3s"
            data-wow-delay="0.4s">

            <i class="bi bi-coin"></i>

            <h4 class="features_title"> Reward & Redemption System </h4>

            <p>
              Encourages responsible disposal by rewarding users with redeemable points.
            </p>

          </div>
        </div>

      </div>

    </div>

  </section>
  <!--====== FEATURES PART ENDS ======-->

  <div class="section-divider"></div>

  <!--====== TEAM PART START ======-->
  <section id="team" class="team_area pt-120 pb-130">

    <div class="container">

      <div class="row justify-content-center">

        <div class="col-lg-6">

          <div class="section_title text-center pb-25">

            <h4 class="title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s"> Meet The TeaM </h4>

            <p class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.4s"> The Archons </p>

          </div>

        </div>

      </div>

      <div class="row justify-content-center team_active">

        <div class="col-lg-4 col-md-8 col-sm-10">

          <div class="single_team mt-30 wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">

            <img src="../assets/landing-page/images/chan1.jpg" alt="team">

            <div class="team_content">

              <h4 class="team_name">
                <a href="#0"> Christian Paul Bascoguin </a>
              </h4>

              <p> Project Manager </p>

            </div>

          </div>

        </div>

        <div class="col-lg-4 col-md-8 col-sm-10">

          <div class="single_team mt-30 wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.3s">

            <img src="../assets/landing-page/images/joshua.jpg" alt="team">

            <div class="team_content">

              <h4 class="team_name">
                <a href="#0"> Joshua Borra </a>
              </h4>

              <p> Hardware Developer </p>

            </div>

          </div>

        </div>

        <div class="col-lg-4 col-md-8 col-sm-10">

          <div class="single_team mt-30 wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.4s">

            <img src="../assets/landing-page/images/arland.jpg" alt="team">

            <div class="team_content">

              <h4 class="team_name">
                <a href="#0"> Arland Vincent Hervias </a>
              </h4>

              <p> Software Developer </p>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>
  <!--====== TEAM PART ENDS ======-->

  <div class="section-divider"></div>

  <!--====== FOOTER PART START ======-->
  <footer id="footer" class="footer_area">

    <div class="container">

      <div class="footer_wrapper text-center d-lg-flex align-items-center justify-content-between">

        <p class="credit">
          Designed and Developed by <a href="#" rel="nofollow">The Archons</a>
        </p>

        <div class="footer_social pt-15">
        </div>

      </div>
    </div>

  </footer>
  <!--====== FOOTER PART ENDS ======-->

  <!--====== BACK TOP TOP PART START ======-->
  <a href="#" class="back-to-top"><i class="lni lni-chevron-up"></i></a>
  <!--====== BACK TOP TOP PART ENDS ======-->

  <!--====== Bootstrap js ======-->
  <script src="../assets/landing-page/js/bootstrap.bundle-5.0.0-beta1.min.js"></script>

  <!--====== glide js ======-->
  <script src="../assets/landing-page/js/tiny-slider.js"></script>

  <!--====== wow js ======-->
  <script src="../assets/landing-page/js/wow.min.js"></script>

  <!--====== Main js ======-->
  <script src="../assets/landing-page/js/main.js"></script>

  <!-- Toggle Passwords -->
  <script src="../assets/global/js/password.js"></script>

  <!-- SweetAlert CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <?php if (isset($_SESSION["alert-status"]) && $_SESSION["alert-status"] !== ""): ?>
    <script>
      const notification = JSON.parse('<?php echo json_encode(json_decode($_SESSION["alert-status"])); ?>');
      Swal.fire({
        icon: notification.icon || 'info',
        title: notification.title || '',
        text: notification.text || '',
        showConfirmButton: false,
        timer: 3000
      });
    </script>

    <?php unset($_SESSION["alert-status"]); ?>
  <?php endif; ?>

</body>

</html>
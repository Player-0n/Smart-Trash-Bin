<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title> STB: Create Student Account </title>

    <link rel="shortcut icon" type="image/x-icon" href="../../assets/global/images/stb-logo.png">

    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php
    include_once "../includes/css-files.php";
    ?>

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <main>

        <section class="section register min-vh-100 d-flex align-items-center">

            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-xl-10 col-lg-10 col-md-11">

                        <!-- Logo -->
                        <div class="text-center mb-0">
                            <a href="../index.php" class="d-flex justify-content-center align-items-center">
                                <img src="../../assets/global/images/stb-logo.png" alt="" class="me-2" style="height: 150px;">
                            </a>
                        </div>

                        <!-- Registration Card -->
                        <div class="card shadow-lg border-0 rounded-4">

                            <div class="card-body p-4 p-md-5">

                                <!-- Header -->
                                <div class="mb-4 text-center">
                                    <h3 class="fw-bold"> Create an Account </h3>
                                    <p class="text-muted small"> Enter your personal details to register. </p>
                                </div>

                                <!-- Form -->
                                <form action="../../process/student/student-auth.php" method="POST" class="row g-3">

                                    <!-- First Name -->
                                    <div class="col-md-4">
                                        <label for="first_name" class="form-label"> First Name: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control" id="first_name" name="first-name" placeholder="First Name" required>
                                        </div>
                                    </div>

                                    <!-- Middle Name -->
                                    <div class="col-md-4">
                                        <label for="middle_name" class="form-label"> Middle Name(Optional): </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control" id="middle_name" name="middle-name" placeholder="Middle Name">
                                        </div>
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-4">
                                        <label for="last_name" class="form-label"> Last Name: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control" id="last_name" name="last-name" placeholder="Last Name" required>
                                        </div>
                                    </div>

                                    <!-- LRN -->
                                    <div class="col-md-4">
                                        <label for="lrn" class="form-label"> Learner Reference Number(LRN): </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                            <input type="text" class="form-control" id="lrn" name="student-lrn" placeholder="Learner Reference Number" required>
                                        </div>
                                    </div>

                                    <!-- Grade Level -->
                                    <div class="col-md-4">
                                        <label for="grade_level" class="form-label"> Grade Level: </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                <i class="bi bi-mortarboard"></i> <!-- Bootstrap icon -->
                                            </span>

                                            <select name="grade-level" id="grade_level" class="form-select" required>
                                                <option value="" disabled selected>Select Grade Level</option>
                                                <option value="Grade 7">Grade 7</option>
                                                <option value="Grade 8">Grade 8</option>
                                                <option value="Grade 9">Grade 9</option>
                                                <option value="Grade 10">Grade 10</option>
                                                <option value="Grade 11">Grade 11</option>
                                                <option value="Grade 12">Grade 12</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Email Address -->
                                    <div class="col-md-4">
                                        <label for="email_address" class="form-label"> Email Address: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" class="form-control" id="email_address" name="email-address" placeholder="Email Address" required>
                                        </div>
                                    </div>

                                    <!-- Gender  -->
                                    <div class="col-md-6">

                                        <label for="gender" class="form-label"> Gender: </label>
                                        <div class="input-group">

                                            <span class="input-group-text">
                                                <i class="bi bi-gender-ambiguous"></i>
                                            </span>

                                            <select name="gender" id="gender" class="form-select" required>
                                                <option value="" disabled selected>Select Your Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Civil  -->
                                    <div class="col-md-6">

                                        <label for="status" class="form-label"> Civil Status: </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                <i class="bi bi-person-heart"></i> <!-- Bootstrap icon -->
                                            </span>

                                            <select name="civil-status" id="status" class="form-select" required>
                                                <option value"" disabled selected>Select Your Status</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Divorced">Divorced</option>
                                                <option value="Widowed">Widowed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- DOB -->
                                    <div class="col-md-6">
                                        <label for="dob" class="form-label"> Date of Birth: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                            <input type="date" class="form-control" id="dob" name="date-of-birth" required>
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label"> Phone Number: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                            <input type="text" class="form-control" id="phone_number" name="phone-number" placeholder="Phone Number" required>
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-12">
                                        <label for="address" class="form-label"> Address: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                                        </div>
                                    </div>

                                    <!-- Password Fields -->
                                    <div class="col-md-6">
                                        <label for="password" class="form-label"> Password: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$" title="Passwords must at least 8 characters long, at least 1 uppercase, lowecase letters, digits and symbols." required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="confirm_password" class="form-label"> Confirm Password: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm-password" placeholder="Confirm Password" required>
                                        </div>
                                    </div>

                                    <!-- Show Password -->
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="showPasswordToggle" onclick="showPasswords()">
                                            <label class="form-check-label" for="showPasswordToggle">
                                                Show Passwords
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100" name="register-student"> Create Account </button>
                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php
    include_once "../includes/script-files.php";
    ?>

    <script>
        function showPasswords() {
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("confirm_password");

            if (password.type === "password") {
                password.type = "text";
                confirmPassword.type = "text";
            } else {
                password.type = "password";
                confirmPassword.type = "password";
            }
        }
    </script>

</body>

</html>
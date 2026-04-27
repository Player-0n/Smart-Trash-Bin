<?php
    require_once "../../config/conn.config.php";

    if(!isset($_GET["reset_token"]) || !isset($_GET["student_lrn"])) {
        header("Location: ../index.php");
        exit();
    }

    else {
        $reset_token = htmlspecialchars(trim($_GET["reset_token"]));
        $student_lrn = htmlspecialchars(trim($_GET["student_lrn"]));

        $check_token_expiry = $conn->prepare("SELECT * FROM student_accounts_tbl WHERE reset_password_token = :token AND student_lrn = :student_lrn LIMIT 1");
        $check_token_expiry->execute([
            ":token" => $reset_token,
            ":student_lrn" => $student_lrn
        ]);

        if($check_token_expiry->rowCount() === 1) {
            $token_data = $check_token_expiry->fetch(PDO::FETCH_OBJ);

            if(strtotime($token_data->password_token_expiry) > time()) {
                $full_name = $token_data->first_name . " " . $token_data->last_name;
                $link_expiry = $token_data->password_token_expiry;
            }

            else {
                header("Location: ../index.php");
                exit();
            }
        }

        else {
            header("Location: ../index.php");
            exit();
        }
    }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> STB: Reset Password </title>

    <link rel="shortcut icon" type="image/x-icon" href="../../assets/global/images/stb-logo.png" />

    <?php
    include_once "../includes/css-files.php"
    ?>
</head>

<body>

    <!-- Main -->

    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">

            <div class="d-flex align-items-center justify-content-center w-100">

                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">

                            <div class="card-body">

                                <a href="" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="../../assets/global/images/stb-logo.png" width="180" alt="">
                                </a>
                                <p class="text-center"> Reset Student Password </p>

                                <form action="../../process/student/student-auth.php" method="POST">

                                    <input type="hidden" name="student-lrn" value="<?php echo htmlspecialchars($student_lrn); ?>">
                                    <input type="hidden" name="reset-token" value="<?php echo htmlspecialchars($reset_token); ?>">

                                    <div class="mb-3">
                                        <p class="mb-0" style="font-size: 13px;">
                                            Reset Password for: <strong><?php echo htmlspecialchars($full_name); ?></strong>
                                        </p>
                                        <p class="text-muted" style="font-size: 13px;">
                                            The link will expire at: 
                                            <strong>
                                                <?php echo htmlspecialchars(date("M. d, Y, h:i:s A", strtotime($link_expiry))); ?>
                                            </strong>
                                        </p>
                                    </div>

                                    <hr>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label"> Password: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$" title="Passwords must at least 8 characters long, at least 1 uppercase, lowecase letters, digits and symbols." required>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label"> Confirm Password: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm-password" placeholder="Re-enter password" required>
                                        </div>
                                    </div>


                                    <div class="d-flex align-items-center justify-content-between mb-4">

                                        <div class="form-check">
                                            <input class="form-check-input primary" type="checkbox" id="show-password" onclick="showPasswords()">
                                            <label class="form-check-label text-dark" for="show-password">
                                                Show Password
                                            </label>
                                        </div>

                                    </div>

                                    <input type="submit" name="reset-password" value="Reset Password" class="btn btn-primary w-100 btn-sm mb-2 py-2 rounded-2">

                                </form>

                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- End Main -->

    <!-- Script File -->
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
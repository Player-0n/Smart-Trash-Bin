<?php
    require_once "../../config/conn.config.php";
    require_once "../../config/dropdowns.config.php";
    require_once "../../config/functions.config.php";
    require_once "../../config/mailer.config.php";
    
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register-student"])) {
        $first_name = htmlspecialchars($_POST["first-name"]);
        $middle_name = htmlspecialchars($_POST["middle-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);
        $student_lrn = htmlspecialchars(trim($_POST["student-lrn"]));
        $grade_level = htmlspecialchars($_POST["grade-level"]);
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $gender = htmlspecialchars($_POST["gender"]);
        $civil_status = htmlspecialchars($_POST["civil-status"]);
        $dob = htmlspecialchars($_POST["date-of-birth"]);
        $phone_number = htmlspecialchars(trim($_POST["phone-number"]));
        $address = htmlspecialchars($_POST["address"]);
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm-password"]));

        if(empty($first_name) || empty($last_name) || empty($student_lrn) || empty($email_address) || empty($gender) || empty($civil_status) || empty($dob) || empty($phone_number) || empty($address) || empty($password) || empty($confirm_password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again. 1"
            ]);

            header("Location: ../../pages/student/register-account.php");
            exit();
        }

        else if(!in_array($grade_level, $allowed_grade_levels)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Level!",
                "text" => "Invalid grade level! Please try again."
            ]);

            header("Location: ../../pages/student/register-account.php");
            exit();
        }

        else if(!in_array($gender, $allowed_genders)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Gender!",
                "text" => "Invalid gender type! Please try again."
            ]);

            header("Location: ../../pages/student/register-account.php");
            exit();
        }

        else if(!in_array($civil_status, $allowed_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Status!",
                "text" => "Invalid status type! Please try again."
            ]);

            header("Location: ../../pages/student/register-account.php");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Format!",
                "text" => "Invalid email address format! Please try again."
            ]);

            header("Location: ../../pages/student/register-account.php");
            exit();
        }

        else if(!preg_match($password_pattern, $password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Format!",
                "text" => "Invalid password format! Please try again."
            ]);

            header("Location: ../../pages/student/register-account.php");
            exit();
        }

        else if($password !== $confirm_password) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Password Mistmatch!",
                "text" => "Passwords don't match! Please try again."
            ]);

            header("Location: ../../pages/student/register-account.php");
            exit();
        }
        
        else {
            try {
                $check_enrolled_student = $conn->prepare("SELECT * FROM enrolled_students_tbl WHERE student_lrn = :student_lrn LIMIT 1");
                $check_enrolled_student->execute([":student_lrn" => $student_lrn]);

                if($check_enrolled_student->rowCount() === 1) {

                    $check_student_account = $conn->prepare("SELECT * FROM student_accounts_tbl WHERE student_lrn = :student_lrn OR email_address = :email_address");
                    $check_student_account->execute([
                        ":student_lrn" => $student_lrn,
                        ":email_address" => $email_address
                    ]);

                    if($check_student_account->rowCount() > 0) {
                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "error",
                            "title" => "Account Exists!",
                            "text" => "This account already exists! Please try again."
                        ]);

                        header("Location: ../../pages/student/register-account.php");
                        exit();
                    }

                    else {
                        $insert_account = $conn->prepare("INSERT INTO student_accounts_tbl(student_lrn, student_rfid, first_name, middle_name, last_name, email_address, student_password, grade_level)
                                                        VALUES(:student_lrn, :student_rfid, :first_name, :middle_name, :last_name, :email_address, :student_password, :grade_level)
                                                        ");
                        $insert_account->execute([
                            ":student_lrn" => $student_lrn,
                            ":student_rfid" => null,
                            ":first_name" => $first_name,
                            ":middle_name" => $middle_name,
                            ":last_name" => $last_name,
                            ":email_address" => $email_address,
                            ":student_password" => password_hash($password, PASSWORD_BCRYPT),
                            ":grade_level" => $grade_level
                        ]);

                        $insert_profile = $conn->prepare("INSERT INTO student_profile_tbl(student_lrn, date_of_birth, gender, civil_status, address, phone_number)
                                                        VALUES(:student_lrn, :dob, :gender, :civil_status, :address, :phone_number)
                                                        ");
                        $insert_profile->execute([
                            ":student_lrn" => $student_lrn,
                            ":dob" => $dob,
                            ":gender" => $gender,
                            ":civil_status" => $civil_status,
                            ":address" => $address,
                            ":phone_number" => $phone_number
                        ]);

                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "success",
                            "title" => "Account Created!",
                            "text" => "Student account created successfully!"
                        ]);

                        $_SESSION["student-lrn"] = $student_lrn;

                        header("Location: ../../pages/student/home.php");
                        exit();
                    }
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Student Not Enrolled!",
                        "text" => "This student is not enrolled in this school! Please try again."
                    ]);

                    header("Location: ../../pages/student/register-account.php");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/student/register-account.php");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login-student"])) {
        $student_lrn = htmlspecialchars(trim($_POST["student-lrn"]));
        $password = htmlspecialchars(trim($_POST["student-password"]));

        if (empty($student_lrn) || empty($password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/index.php");
            exit();
        }
        
        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM student_accounts_tbl WHERE student_lrn = :student_lrn LIMIT 1");
                $check_account->execute([":student_lrn" => $student_lrn]);

                if($check_account->rowCount() === 1) {
                    $user_account = $check_account->fetch(PDO::FETCH_OBJ);

                    if(password_verify($password, $user_account->student_password)) {
                        $_SESSION["student-lrn"] = $student_lrn;

                        header("Location: ../../pages/student/home.php");
                        exit();
                    }

                    else {
                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "error",
                            "title" => "Invalid Crededntials!",
                            "text" => "Invalid student LRN or password! Please try again."
                        ]);

                        header("Location: ../../pages/index.php");
                        exit();
                    }
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Invalid Crededntials!",
                        "text" => "Invalid student LRN or password! Please try again."
                    ]);

                    header("Location: ../../pages/index.php");
                    exit();
                }
            } 
            
            catch (PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/index.php");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["verify-account"])) {
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $student_lrn = htmlspecialchars(trim($_POST["student-lrn"]));

        if(empty($email_address)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/index.php");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
             $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Format!",
                "text" => "Invalid email address format! Please try again."
            ]);

            header("Location: ../../pages/index.php");
            exit();
        }

        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM student_accounts_tbl WHERE student_lrn = :student_lrn AND email_address = :email_address LIMIT 1");
                $check_account->execute([
                    ":student_lrn" => $student_lrn,
                    ":email_address" => $email_address
                ]);

                if($check_account->rowCount() === 1) {
                    $user_data = $check_account->fetch(PDO::FETCH_OBJ);

                    $full_name = $user_data->first_name . " " . $user_data->last_name;

                    $reset_token = generate_reset_token(16);
                    $token_expiry = generate_expiry_time(5);

                    $reset_password_link = "http://localhost/stb_final/pages/student/reset-password.php?reset_token=$reset_token&student_lrn=$student_lrn";

                    send_reset_password_link($email_address, $full_name, $reset_password_link);

                    $update_account = $conn->prepare("UPDATE student_accounts_tbl
                                                    SET reset_password_token = :token,
                                                    password_token_expiry = :expiry
                                                    WHERE student_lrn = :student_lrn
                                                    ");
                    $update_account->execute([
                        ":token" => $reset_token,
                        ":expiry" => $token_expiry,
                        ":student_lrn" => $student_lrn
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Reset Link Sent!",
                        "text" => "The password reset link has been sent to your email address!"
                    ]);

                    header("Location: ../../pages/index.php");
                    exit();
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Invalid Account!",
                        "text" => "Account not found! Please try again."
                    ]);

                    header("Location: ../../pages/index.php");
                    exit();
                }
            }

            catch(PDOException $e) {
                 $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/index.php");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reset-password"])) {

        $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/";

        $student_lrn = htmlspecialchars(trim($_POST["student-lrn"]));
        $token = htmlspecialchars(trim($_POST["reset-token"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm-password"]));

        if(empty($student_lrn) || empty($token) || empty($password) || empty($confirm_password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/student/reset-password.php?reset_token=$token&student_lrn=$student_lrn");
            exit();
        }

        else if(!preg_match($password_pattern, $password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Pattern",
                "text" => "Invalid password pattern! Please try again."
            ]);

            header("Location: ../../pages/student/reset-password.php?reset_token=$token&student_lrn=$student_lrn");
            exit();
        }

        else if($password !== $confirm_password) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Password Mismatch!",
                "text" => "Passwords do not match! Please try again."
            ]);

            header("Location: ../../pages/student/reset-password.php?reset_token=$token&student_lrn=$student_lrn");
            exit();
        }

        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM student_accounts_tbl WHERE reset_password_token = :token AND student_lrn = :student_lrn LIMIT 1");
                $check_account->execute([
                    ":token" => $token,
                    ":student_lrn" => $student_lrn
                ]);

                if($check_account->rowCount() === 1) {
                    $token_data = $check_account->fetch(PDO::FETCH_OBJ);

                    if(strtotime($token_data->password_token_expiry) > time()) {
                        $reset_password = $conn->prepare("UPDATE student_accounts_tbl
                                                        SET student_password = :student_password,
                                                        reset_password_token = NULL,
                                                        password_token_expiry = NULL
                                                        WHERE student_lrn = :student_lrn
                                                        ");
                        $reset_password->execute([
                            ":student_password" => password_hash($password, PASSWORD_BCRYPT),
                            ":student_lrn" => $student_lrn
                        ]);

                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "success",
                            "title" => "Password Reset!",
                            "text" => "Password has been reset!"
                        ]);

                        header("Location: ../../pages/index.php");
                        exit();
                    }

                    else {
                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "error",
                            "title" => "Link Expired!",
                            "text" => "The link is already expired! Please try again."
                        ]);

                        header("Location: ../../pages/index.php");
                        exit();
                    }
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Invalid Account!",
                        "text" => "Account not found! Please try again."
                    ]);

                    header("Location: ../../pages/index.php");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/student/reset-password.php?reset_token=$token&student_lrn=$student_lrn");
                exit();
            }
        }
    }
?>
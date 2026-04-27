<?php
    require_once "../../config/conn.config.php";
    require_once "../../config/dropdowns.config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-profile"])) {

        $student_lrn = htmlspecialchars(trim($_SESSION["student-lrn"]));
        $first_name = htmlspecialchars($_POST["first-name"]);
        $middle_name = htmlspecialchars($_POST["middle-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);
        $date_of_birth = htmlspecialchars($_POST["date-of-birth"]);
        $gender = htmlspecialchars($_POST["gender"]);
        $civil_status = htmlspecialchars($_POST["civil-status"]);
        $grade_level = htmlspecialchars($_POST["grade-level"]);
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $phone_number = htmlspecialchars($_POST["phone-number"]);
        $address = htmlspecialchars($_POST["address"]);
        $facebook_link = htmlspecialchars($_POST["facebook-link"]);

        if(empty($student_lrn) || empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($gender) || empty($civil_status) || empty($email_address) || empty($grade_level) || empty($phone_number) || empty($address)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
            exit();
        }

        else if(!in_array($gender, $allowed_genders)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Gender!",
                "text" => "Invalid type of gender! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
            exit();
        }

        else if(!in_array($civil_status, $allowed_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Status!",
                "text" => "Invalid type of status! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
            exit();
        }

        else if(!in_array($grade_level, $allowed_grade_levels)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Level!",
                "text" => "Invalid grade level! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Format!",
                "text" => "Invalid email address format! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
            exit();
        }

        else {
            try {
                $check_existing_email_address = $conn->prepare("SELECT * FROM student_accounts_tbl WHERE email_address = :email_address AND student_lrn != :student_lrn");
                $check_existing_email_address->execute([
                    ":email_address" => $email_address,
                    ":student_lrn" => $student_lrn
                ]);

                if($check_existing_email_address->rowCount() > 0) {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Existing Email Address",
                        "text" => "This email address is already taken! Please try again."
                    ]);

                    header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
                    exit();
                }

                else {

                    $update_account = $conn->prepare("UPDATE student_accounts_tbl
                                                    SET first_name = :first_name,
                                                    middle_name = :middle_name,
                                                    last_name = :last_name,
                                                    email_address = :email_address,
                                                    grade_level = :grade_level
                                                    WHERE student_lrn = :student_lrn
                                                    ");
                    $update_account->execute([
                        ":first_name" => $first_name,
                        ":middle_name" => $middle_name,
                        ":last_name" => $last_name,
                        ":email_address" => $email_address,
                        ":grade_level" => $grade_level,
                        ":student_lrn" => $student_lrn
                    ]);

                    $update_profile = $conn->prepare("UPDATE student_profile_tbl
                                                    SET date_of_birth = :dob,
                                                    gender = :gender,
                                                    civil_status = :civil_status,
                                                    phone_number = :phone_number,
                                                    address = :address,
                                                    facebook_link = :facebook_link
                                                    WHERE student_lrn = :student_lrn
                                                    ");
                    $update_profile->execute([
                        ":dob" => $date_of_birth,
                        ":gender" => $gender,
                        ":civil_status" => $civil_status,
                        ":phone_number" => $phone_number,
                        ":address" => $address,
                        ":facebook_link" => $facebook_link,
                        ":student_lrn" => $student_lrn
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Profile Updated!",
                        "text" => "Profile updated successfully!"
                    ]);

                    header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/student/home.php?page=user-profile&update-profile=true");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-profile-picture"])) {

        $file_location = "../../uploads/user-images/";

        $student_lrn = htmlspecialchars(trim($_SESSION["student-lrn"]));
        $uploaded_photo = $_FILES["uploaded-photo"]["name"];
        $tmp_name = $_FILES["uploaded-photo"]["tmp_name"];

        $file_extension = strtolower(pathinfo($uploaded_photo, PATHINFO_EXTENSION));

        if(empty($student_lrn) || empty($uploaded_photo)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-photo=true");
            exit();
        }

        if(!in_array($file_extension, $allowed_img_format)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid File!",
                "text" => "Invalid file type! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-photo=true");
            exit();
        }

        else {
            try {
                
                $unique_filename = 'student_' . $student_lrn . '_' . uniqid() . '.' . $file_extension;

                $update_profile_picture = $conn->prepare("UPDATE student_profile_tbl SET profile_picture = :profile_picture WHERE student_lrn = :student_lrn");
                $update_profile_picture->execute([
                    ":profile_picture" => $unique_filename,
                    ":student_lrn" => $student_lrn
                ]);

                $file_path = $file_location . $unique_filename;
                move_uploaded_file($tmp_name, $file_path);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Photo Updated!",
                    "text" => "Profile photo updated successfully!"
                ]);

                header("Location: ../../pages/student/home.php?page=user-profile&update-photo=true");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/student/home.php?page=user-profile&update-photo=true");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-password"])) {
        $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/";

        $student_lrn = htmlspecialchars(trim($_SESSION["student-lrn"]));
        $current_password = htmlspecialchars(trim($_POST["current-password"]));
        $new_password = htmlspecialchars(trim($_POST["new-password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm-new-password"]));

        if(empty($student_lrn) || empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-password=true");
            exit();
        }

        else if(!preg_match($pattern, $new_password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Pattern!",
                "text" => "Invalid password pattern! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=user-profile&update-password=true");
            exit();
        }

        else {
            if($new_password === $confirm_password) {
                try {
                    $check_account = $conn->prepare("SELECT * FROM student_accounts_tbl WHERE student_lrn = :student_lrn LIMIT 1");
                    $check_account->execute([":student_lrn" => $student_lrn]);
                    
                    if($check_account->rowCount() === 1) {
                        $password_data = $check_account->fetch(PDO::FETCH_OBJ);

                        if(password_verify($current_password, $password_data->student_password)) {
                            $set_new_password = $conn->prepare("UPDATE student_accounts_tbl SET student_password = :new_password WHERE student_lrn = :student_lrn");
                            $set_new_password->execute([
                                ":new_password" => password_hash($new_password, PASSWORD_BCRYPT),
                                ":student_lrn" => $student_lrn
                            ]);

                            $change_update = $conn->prepare("UPDATE student_profile_tbl
                                                        SET updated_at = CURRENT_TIMESTAMP()
                                                        WHERE student_lrn = :student_lrn");
                            $change_update->execute([":student_lrn" => $student_lrn]);

                            $_SESSION["alert-status"] = json_encode([
                                "icon" => "success",
                                "title" => "Password Updated!",
                                "text" => "Password updated successfully!"
                            ]);

                            header("Location: ../../pages/student/home.php?page=user-profile&update-password=true");
                            exit();

                        }

                        else {
                            $_SESSION["alert-status"] = json_encode([
                                "icon" => "error",
                                "title" => "Invalid Password!",
                                "text" => "Invalid current password! Please try again."
                            ]);

                            header("Location: ../../pages/student/home.php?page=user-profile&update-password=true");
                            exit();
                        }
                    }

                    else {
                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "error",
                            "title" => "Invalid Account!",
                            "text" => "Accountn not found! Please try again."
                        ]);

                        header("Location: ../../pages/student/home.php?page=user-profile&update-password=true");
                        exit();
                    }
                }

                catch(PDOException $e) {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Unknown Error!",
                        "text" => "An unknown error occured! Please try again."
                    ]);

                    header("Location: ../../pages/student/home.php?page=user-profile&update-password=true");
                    exit();
                }
            }

            else {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Password Mismatch!",
                    "text" => "Passwords do not match! Please try again."
                ]);

                header("Location: ../../pages/student/home.php?page=user-profile&update-password=true");
                exit();
            }
        }
    }
?>
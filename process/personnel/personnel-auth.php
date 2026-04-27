<?php
    require_once "../../config/conn.config.php";
    require_once "../../config/functions.config.php";
    require_once "../../config/mailer.config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["personnel-login"])) {
        $email_address = filter_var($_POST["personnel-email"], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST["personnel-password"]));

        if (empty($email_address) || empty($password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/index.php");
            exit();
        } 
        
        else if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Email Address!",
                "text" => "Invalid email address format! Please try again."
            ]);

            header("Location: ../../pages/index.php");
            exit();
        } 
        
        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM personnel_accounts_tbl WHERE email_address = :email_address LIMIT 1");
                $check_account->execute([":email_address" => $email_address]);

                if($check_account->rowCount() === 1) {
                    $user_account = $check_account->fetch(PDO::FETCH_OBJ);

                    if(password_verify($password, $user_account->personnel_password)) {
                        $_SESSION["personnel-id"] = $user_account->personnel_id;

                        header("Location: ../../pages/personnel/home.php");
                        exit();
                    }

                    else {
                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "error",
                            "title" => "Invalid Crededntials!",
                            "text" => "Invalid email address or password! Please try again."
                        ]);

                        header("Location: ../../pages/index.php");
                        exit();
                    }
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Invalid Crededntials!",
                        "text" => "Invalid email address or password! Please try again."
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
                $check_account = $conn->prepare("SELECT * FROM personnel_accounts_tbl WHERE email_address = :email_address LIMIT 1");
                $check_account->execute([":email_address" => $email_address]);

                if($check_account->rowCount() === 1) {
                    $user_data = $check_account->fetch(PDO::FETCH_OBJ);
                    $personnel_id = $user_data->personnel_id;
                    $full_name = $user_data->first_name . " " . $user_data->last_name;

                    $reset_token = generate_reset_token(16);
                    $token_expiry = generate_expiry_time(5);

                    $reset_password_link = "http://localhost/stb_final/pages/personnel/reset-password.php?reset_token=$reset_token&personnel_id=$personnel_id";

                    send_reset_password_link($email_address, $full_name, $reset_password_link);

                    $update_account = $conn->prepare("UPDATE personnel_accounts_tbl
                                                    SET reset_password_token = :token,
                                                    password_token_expiry = :expiry
                                                    WHERE personnel_id = :personnel_id
                                                    ");
                    $update_account->execute([
                        ":token" => $reset_token,
                        ":expiry" => $token_expiry,
                        ":personnel_id" => $personnel_id
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

        $personnel_id = htmlspecialchars(trim($_POST["personnel-id"]));
        $token = htmlspecialchars(trim($_POST["reset-token"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm-password"]));

        if(empty($personnel_id) || empty($token) || empty($password) || empty($confirm_password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/personnel/reset-password.php?reset_token=$token&personnel_id=$personnel_id");
            exit();
        }

        else if(!preg_match($password_pattern, $password)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Pattern",
                "text" => "Invalid password pattern! Please try again."
            ]);

            header("Location: ../../pages/personnel/reset-password.php?reset_token=$token&personnel_id=$personnel_id");
            exit();
        }

        else if($password !== $confirm_password) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Password Mismatch!",
                "text" => "Passwords do not match! Please try again."
            ]);

            header("Location: ../../pages/personnel/reset-password.php?reset_token=$token&personnel_id=$personnel_id");
            exit();
        }

        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM personnel_accounts_tbl WHERE reset_password_token = :token AND personnel_id = :personnel_id LIMIT 1");
                $check_account->execute([
                    ":token" => $token,
                    ":personnel_id" => $personnel_id
                ]);

                if($check_account->rowCount() === 1) {
                    $token_data = $check_account->fetch(PDO::FETCH_OBJ);

                    if(strtotime($token_data->password_token_expiry) > time()) {
                        $reset_password = $conn->prepare("UPDATE personnel_accounts_tbl
                                                        SET personnel_password = :personnel_password,
                                                        reset_password_token = NULL,
                                                        password_token_expiry = NULL
                                                        WHERE personnel_id = :personnel_id
                                                        ");
                        $reset_password->execute([
                            ":personnel_password" => password_hash($password, PASSWORD_BCRYPT),
                            ":personnel_id" => $personnel_id
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

                header("Location: ../../pages/personnel/reset-password.php?reset_token=$token&personnel_id=$personnel_id");
                exit();
            }
        }
    }
?>

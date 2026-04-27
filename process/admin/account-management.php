<?php
    require_once "../../config/conn.config.php";
    require_once "../../config/dropdowns.config.php";

    // Admin Accounts
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-admin-account"])) {
        $first_name = htmlspecialchars($_POST["first-name"]);
        $middle_name = htmlspecialchars($_POST["middle-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $gender = htmlspecialchars($_POST["gender"]);
        $civil_status = htmlspecialchars($_POST["civil-status"]);
        $phone_number = htmlspecialchars($_POST["phone-number"]);
        $address = htmlspecialchars($_POST["address"]);

        if(empty($first_name) || empty($last_name) || empty($email_address) || empty($gender) || empty($civil_status) || empty($phone_number) || empty($address)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=admin-accounts");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Email Address!",
                "text" => "Invalid email address format! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=admin-accounts");
            exit();
        }

        else if(!in_array($gender, $allowed_genders)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Gender!",
                "text" => "Invalid gender type! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=admin-accounts");
            exit();
        }

        else if(!in_array($civil_status, $allowed_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Status!",
                "text" => "Invalid status type! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=admin-accounts");
            exit();
        }

        else {
            try {
                $check_email_address = $conn->prepare("SELECT * FROM admin_accounts_tbl WHERE email_address = :email_address");
                $check_email_address->execute([":email_address" => $email_address]);

                if($check_email_address->rowCount() === 0) {
                    $insert_account = $conn->prepare("INSERT INTO admin_accounts_tbl(first_name, middle_name, last_name, email_address, admin_password)
                                                    VALUES(:first_name, :middle_name, :last_name, :email_address, :admin_password)
                                                    ");

                    $insert_account->execute([
                        ":first_name" => $first_name,
                        ":middle_name" => $middle_name,
                        ":last_name" => $last_name,
                        ":email_address" => $email_address,
                        ":admin_password" => password_hash(strtoupper($last_name), PASSWORD_BCRYPT)
                    ]);

                    $admin_id = $conn->lastInsertId();

                    $insert_profile = $conn->prepare("INSERT INTO admin_profile_tbl(admin_id, gender, civil_status, address, phone_number)
                                                    VALUES(:admin_id, :gender, :civil_status, :address, :phone_number)
                                                    ");
                    $insert_profile->execute([
                        ":admin_id" => $admin_id,
                        ":gender" => $gender,
                        ":civil_status" => $civil_status,
                        ":address" => $address,
                        ":phone_number" => $phone_number
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Admin Added!",
                        "text" => "New admin account has been added successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=admin-accounts");
                    exit();
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Email Address Taken!",
                        "text" => "This email address is already taken! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=admin-accounts");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Invalid Email Address!",
                    "text" => "Invalid email address format! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=admin-accounts");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["set-admin-account-status"])) {
        $admin_id = htmlspecialchars(trim(base64_decode($_POST["admin-id"])));
        $account_status = htmlspecialchars(trim($_POST["account-status"]));

        if(empty($admin_id) || empty($account_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=admin-accounts");
            exit();
        }

        else {
            try {
                $locked_account = $account_status === "Yes" ? "Locked" : "Unlocked";
                $text = $account_status === "Yes"
                    ? "This account has been locked!"
                    : "This account has been unlocked!";

                $set_account_status = $conn->prepare("UPDATE admin_accounts_tbl SET locked_account = :locked_account WHERE admin_id = :admin_id");
                $set_account_status->execute([
                    ":locked_account" => $account_status,
                    ":admin_id" => $admin_id
                ]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Account " . $locked_account . "!",
                    "text" => $text
                ]);

                header("Location: ../../pages/admin/home.php?page=admin-accounts");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=admin-accounts");
            exit();
            }
        }
    }

    // Personnel Accounts
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-personnel-account"])) {
        $first_name = htmlspecialchars($_POST["first-name"]);
        $middle_name = htmlspecialchars($_POST["middle-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $gender = htmlspecialchars($_POST["gender"]);
        $civil_status = htmlspecialchars($_POST["civil-status"]);
        $phone_number = htmlspecialchars($_POST["phone-number"]);
        $address = htmlspecialchars($_POST["address"]);

        if(empty($first_name) || empty($last_name) || empty($email_address) || empty($gender) || empty($civil_status) || empty($phone_number) || empty($address)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=personnel-accounts");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Email Address!",
                "text" => "Invalid email address format! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=personnel-accounts");
            exit();
        }

        else if(!in_array($gender, $allowed_genders)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Gender!",
                "text" => "Invalid gender type! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=personnel-accounts");
            exit();
        }

        else if(!in_array($civil_status, $allowed_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Status!",
                "text" => "Invalid status type! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=personnel-accounts");
            exit();
        }

        else {
            try {
                $check_email_address = $conn->prepare("SELECT * FROM personnel_accounts_tbl WHERE email_address = :email_address");
                $check_email_address->execute([":email_address" => $email_address]);

                if($check_email_address->rowCount() === 0) {
                    $insert_account = $conn->prepare("INSERT INTO personnel_accounts_tbl(first_name, middle_name, last_name, email_address, personnel_password)
                                                    VALUES(:first_name, :middle_name, :last_name, :email_address, :personnel_password)
                                                    ");

                    $insert_account->execute([
                        ":first_name" => $first_name,
                        ":middle_name" => $middle_name,
                        ":last_name" => $last_name,
                        ":email_address" => $email_address,
                        ":personnel_password" => password_hash(strtoupper($last_name), PASSWORD_BCRYPT)
                    ]);

                    $personnel_id = $conn->lastInsertId();

                    $insert_profile = $conn->prepare("INSERT INTO personnel_profile_tbl(personnel_id, gender, civil_status, address, phone_number)
                                                    VALUES(:personnel_id, :gender, :civil_status, :address, :phone_number)
                                                    ");
                    $insert_profile->execute([
                        ":personnel_id" => $personnel_id,
                        ":gender" => $gender,
                        ":civil_status" => $civil_status,
                        ":address" => $address,
                        ":phone_number" => $phone_number
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Personnel Added!",
                        "text" => "New personnel account has been added successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=personnel-accounts");
                    exit();
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Email Address Taken!",
                        "text" => "This email address is already taken! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=personnel-accounts");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Invalid Email Address!",
                    "text" => "Invalid email address format! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=personnel-accounts");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["set-personnel-account-status"])) {
        $personnel_id = htmlspecialchars(trim(base64_decode($_POST["personnel-id"])));
        $account_status = htmlspecialchars(trim($_POST["account-status"]));

        if(empty($personnel_id) || empty($account_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=personnel-accounts");
            exit();
        }

        else {
            try {
                $locked_account = $account_status === "Yes" ? "Locked" : "Unlocked";
                $text = $account_status === "Yes"
                    ? "This account has been locked!"
                    : "This account has been unlocked!";

                $set_account_status = $conn->prepare("UPDATE personnel_accounts_tbl SET locked_account = :locked_account WHERE personnel_id = :personnel_id");
                $set_account_status->execute([
                    ":locked_account" => $account_status,
                    ":personnel_id" => $personnel_id
                ]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Account " . $locked_account . "!",
                    "text" => $text
                ]);

                header("Location: ../../pages/admin/home.php?page=personnel-accounts");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=personnel-accounts");
            exit();
            }
        }
    }
?>
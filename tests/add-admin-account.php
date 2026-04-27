<?php
    require_once "../config/conn.config.php";

    try {
        $insert_admin_account = $conn->prepare("INSERT INTO admin_accounts_tbl(first_name, middle_name, last_name, email_address, admin_password)
                                                VALUES(:first_name, :middle_name, :last_name, :email_address, :admin_password)
                                            ");

        $insert_admin_account->execute([
            ":first_name" => "Joshua",
            ":middle_name" => "Forro",
            ":last_name" => "Borra",
            ":email_address" => "stephmarvin30@gmail.com",
            ":admin_password" => password_hash("Joshua123!", PASSWORD_BCRYPT)
        ]);

        $admin_id = $conn->lastInsertId();

        $insert_admin_profile = $conn->prepare("INSERT INTO admin_profile_tbl(admin_id, gender, civil_status, address, phone_number)
                                                VALUES(:admin_id, :gender, :civil_status, :address, :phone_number)
                                            ");
        $insert_admin_profile->execute([
            ":admin_id" => $admin_id,
            ":gender" => "Male",
            ":civil_status" => "Single",
            ":address" => "Janiuay, Iloilo",
            ":phone_number" => "09927415812"
        ]);

        echo "Account and Data Inserted!";
    }

    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
<?php

    ob_start();

    require_once "../../config/conn.config.php";
    require_once "../../config/format-data.config.php";
    require_once "includes/page-titles.php";

    $page_name = isset($_GET["page"]) ? $_GET["page"] : "dashboard";
    $file_path = "../../uploads/user-images/";

    if (empty($_SESSION["personnel-id"]) || $_SESSION["personnel-id"] === "" || $_SESSION["personnel-id"] === null) {
        header("Location: ../index.php");
        exit();
    } else {
        $personnel_id = htmlspecialchars(trim($_SESSION["personnel-id"]));

        $get_user_data = $conn->prepare("SELECT
                                                    pa.*, pp.*
                                                FROM personnel_accounts_tbl pa
                                                LEFT JOIN personnel_profile_tbl pp
                                                ON pa.personnel_id = pp.personnel_id
                                                WHERE pa.personnel_id = :personnel_id
                                            ");
        $get_user_data->execute([":personnel_id" => $personnel_id]);

        while ($user_data = $get_user_data->fetch(PDO::FETCH_OBJ)) {

            // Primary Data
            $first_name = $user_data->first_name;
            $middle_name = $user_data->middle_name;
            $last_name = $user_data->last_name;
            $role = $user_data->role;
            $email_address = $user_data->email_address;
            $locked_account = $user_data->locked_account;

            // Secondary Data
            $profile_picture = $user_data->profile_picture;
            $date_of_birth = $user_data->date_of_birth;
            $gender = $user_data->gender;
            $civil_status = $user_data->civil_status;
            $phone_number = $user_data->phone_number;
            $address = $user_data->address;
            $facebook_link = $user_data->facebook_link;
            $updated_at = $user_data->updated_at;
        }

        if($locked_account === "Yes") {
            unset($_SESSION["personnel-id"]);

            $_SESSION["locked-account"] = true;
            header("Location: ../locked/locked-account.php");
            exit();
        }

        if (empty($profile_picture) || !file_exists($file_path . $profile_picture)) {
            $profile_picture = "default-img.png";
        }

            // Not Completed Maintenance
            $get_maintenance_count = $conn->prepare("SELECT 
                                                    COUNT(*) AS 'maintenance_count' 
                                                FROM maintenance_tbl
                                                WHERE maintenance_status != :maintenance_status
                                                AND assigned_personnel = :personnel_id
                                                ");
            $get_maintenance_count->execute([
                ":maintenance_status" => "Completed",
                ":personnel_id" => $personnel_id
            ]);
            $maintenance_count = $get_maintenance_count->fetch()["maintenance_count"];
        }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Webtitle and Logo -->
    <?php
    include_once "../includes/web-title.php";
    ?>
    <!-- End Webtitle and Logo -->

    <!-- CSS Files -->
    <?php
    include_once "../includes/css-files.php";
    ?>
    <!-- End CSS Files -->

    <!-- Custom CSS -->
    <style>
        .active-page i,
        .active-page span {
            color: #7CBF42;
        }

        .custom-table {
            cursor: pointer;
        }

        .custom-table thead tr th {
            text-align: center;
        }

        .custom-table thead tr th,
        .custom-table tr td {
            text-align: center;
            vertical-align: middle !important;
        }

        .custom-table tr th {
            background-color: #7CBF42;
            height: 50px;
            color: #f2f2f2;
        }

        .custom-table tr td img {
            width: 50px;
        }

        .custom-add-btn,
        .custom-save-btn {
            background-color: #7CBF42;
            border: none;
        }

        .custom-add-btn:hover {
            background-color: #7CBF42;
            filter: brightness(90%);
        }

        .custom-card-title {
            font-size: 24px;
        }

        .custom-form input:focus,
        .custom-form select:focus {
            outline: none;
            border: 1px solid #7CBF42;
            box-shadow: none;
        }

        .custom-form select,
        .custom-form .date-time {
            height: 50px;
        }

        .edit-dropdown {
            height: 40px;
        }

        .custom-overview .custom-title {
            color: #2b3c49;
        }

        .custom-form label {
            color: #2b3c49;
        }

        .custom-table tr th,
        .custom-table tr td {
            font-size: 13px;
        }

        .custom-table a {
            font-size: 13px;
        }
    </style>

</head>

<body>

    <!-- Header -->
    <?php
    include_once "../includes/header.php";
    ?>
    <!-- End Header -->


    <!-- Sidebar -->
    <?php
    include_once "includes/sidebar.php";
    ?>
    <!-- End Sidebar -->

    <!-- Main -->
    <?php
    $page_path = "pages/$page_name.php";

    if (file_exists($page_path)) {
        include_once $page_path;
    } else {
        $page_name = "dashboard";
        include_once "pages/dashboard.php";
    }

    ?>
    <!-- End Main -->

    <!-- Footer -->
    <?php
    include_once "../includes/footer.php";
    ?>
    <!-- End Footer -->


    <!-- Script Files -->
    <?php
    include_once "../includes/script-files.php"
    ?>
    <!-- End Script Files -->

</body>

</html>

<?php ob_end_flush(); ?>
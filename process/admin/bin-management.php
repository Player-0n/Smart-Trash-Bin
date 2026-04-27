<?php
    require_once "../../config/conn.config.php";

    // Bin Management
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-new-bin"])) {
        $unique_bin_id = htmlspecialchars($_POST["unique-bin-id"]);
        $bin_name = htmlspecialchars($_POST["bin-name"]);
        $bin_location = htmlspecialchars($_POST["bin-location"]);

        if(empty($unique_bin_id) || empty($bin_name) || empty($bin_location)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=bin-monitoring");
            exit();
        }

        else {
            try {
                $check_existing_bin = $conn->prepare("SELECT * FROM bins_tbl WHERE unique_bin_id = :unique_bin_id OR bin_name = :bin_name");
                $check_existing_bin->execute([
                    ":unique_bin_id" => $unique_bin_id,
                    ":bin_name" => $bin_name
                ]);

                if($check_existing_bin->rowCount() > 0) {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Existing Bin!",
                        "text" => "This bin is already exists! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                    exit();
                }

                else {
                    $insert_bin = $conn->prepare("INSERT INTO bins_tbl(unique_bin_id, bin_name, bin_location)        
                                                VALUES(:unique_bin_id, :bin_name, :bin_location)
                                                ");
                    $insert_bin->execute([
                        ":unique_bin_id" => $unique_bin_id,
                        ":bin_name" => $bin_name,
                        ":bin_location" => $bin_location
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Bin Added!",
                        "text" => "A new bin has been added successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-bin"])) {
        $bin_id = htmlspecialchars(trim(base64_decode($_POST["bin-id"])));
        $unique_bin_id = htmlspecialchars($_POST["unique-bin-id"]);
        $bin_name = htmlspecialchars($_POST["bin-name"]);
        $bin_location = htmlspecialchars($_POST["bin-location"]);

        if(empty($bin_id) || empty($unique_bin_id) || empty($bin_name) || empty($bin_location)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=bin-monitoring");
            exit();
        }

        else {
            try {
                $check_existing_bin = $conn->prepare("SELECT * FROM bins_tbl WHERE (unique_bin_id = :unique_bin_id OR bin_name = :bin_name) AND bin_id != :bin_id");
                $check_existing_bin->execute([
                    ":unique_bin_id" => $unique_bin_id,
                    ":bin_name" => $bin_name,
                    ":bin_id" => $bin_id
                ]);

                if($check_existing_bin->rowCount() > 0) {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Existing Bin!",
                        "text" => "This bin is already exists! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                    exit();
                }

                else {
                    $update_bin = $conn->prepare("UPDATE bins_tbl
                                                SET unique_bin_id = :unique_bin_id,
                                                bin_name = :bin_name,
                                                bin_location = :bin_location
                                                WHERE bin_id = :bin_id
                                                ");
                    $update_bin->execute([
                        ":unique_bin_id" => $unique_bin_id,
                        ":bin_name" => $bin_name,
                        ":bin_location" => $bin_location,
                        ":bin_id" => $bin_id
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Bin Updated!",
                        "text" => "Trash bin has been updated successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-bin"])) {
        $bin_id = htmlspecialchars(trim(base64_decode($_POST["bin-id"])));

        if(empty($bin_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=bin-monitoring");
            exit();
        }

        else {
            try {
                $delete_bin = $conn->prepare("DELETE FROM bins_tbl WHERE bin_id = :bin_id");
                $delete_bin->execute([":bin_id" => $bin_id]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Bin Deleted!",
                    "text" => "Trash bin has been deleted successfully!"
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-monitoring");
                exit();
            }
        }
    }

    // Maitenance
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-new-maintenance"])) {
        
        $maintenance_title = htmlspecialchars($_POST["maintenance-title"]);
        $bin_id = htmlspecialchars(trim($_POST["bin-id"]));
        $personnel_id = htmlspecialchars(trim($_POST["personnel-id"]));

        if(empty($maintenance_title) || empty($bin_id) || empty($personnel_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=maintenance-alerts");
            exit();
        }

        else {
            try {
                $insert_maintenance = $conn->prepare("INSERT INTO maintenance_tbl(maintenance_title, maintenance_bin, assigned_personnel)
                                                    VALUES(:maintenance_title, :bin_id, :personnel_id) 
                                                    ");
                $insert_maintenance->execute([
                    ":maintenance_title" => $maintenance_title,
                    ":bin_id" => $bin_id,
                    ":personnel_id" => $personnel_id
                ]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Maintenance Added!",
                    "text" => "A new maintenance has been added successfully!"
                ]);

                header("Location: ../../pages/admin/home.php?page=maintenance-alerts");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=maintenance-alerts");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-maintenance"])) {
        
        $maintenance_id = htmlspecialchars(trim(base64_decode($_POST["maintenance-id"])));
        
        if(empty($maintenance_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=maintenance-alerts");
            exit();
        }

        else {
            try {
                $delete_maintenance = $conn->prepare("DELETE FROM maintenance_tbl WHERE maintenance_id = :maintenance_id");
                $delete_maintenance->execute([":maintenance_id" => $maintenance_id]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Maintenance Deleted!",
                    "text" => "Maintenance has been deleted successfully!"
                ]);

                header("Location: ../../pages/admin/home.php?page=maintenance-alerts");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=maintenance-alerts");
                exit();
            }
        }
    }
?>
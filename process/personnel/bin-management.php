<?php
    require_once "../../config/conn.config.php";
    require_once "../../config/dropdowns.config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["empty-bin"])) {
        $personnel_id = htmlspecialchars(trim($_SESSION["personnel-id"]));
        $bin_id = htmlspecialchars(trim(base64_decode($_POST["bin-id"])));
        $trash_item_type = htmlspecialchars(trim($_POST["trash-item-type"]));
        $weight = htmlspecialchars($_POST["trash-weight"]);

        if(empty($personnel_id) || empty($bin_id) || empty($trash_item_type) || empty($weight)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/personnel/home.php?page=bin-monitoring");
            exit();
        }

        else if (!filter_var($weight, FILTER_VALIDATE_FLOAT) || $weight < 1) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Weight!",
                "text" => "Invalid trash weight count! Please try again."
            ]);

            header("Location: ../../pages/personnel/home.php?page=bin-monitoring");
            exit();
        }

        else if(!in_array($trash_item_type, $allowed_trash_type)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Type!",
                "text" => "Invalid trash type! Please try again."
            ]);

            header("Location: ../../pages/personnel/home.php?page=bin-monitoring");
            exit();
        }

        else {
            try {
                $conn->beginTransaction();

                $bin_to_empty = $trash_item_type === "Plastic" ? "plastic_fill_level" : "metal_fill_level";

                $empty_bin = $conn->prepare("UPDATE bins_tbl
                                            SET $bin_to_empty = :fill_level,
                                            last_emptied = CURRENT_TIMESTAMP()
                                            WHERE bin_id = :bin_id
                                            ");
                $empty_bin->execute([
                    ":fill_level" => 0,
                    ":bin_id" => $bin_id
                ]);

                $insert_dispose_log = $conn->prepare("INSERT INTO disposal_logs_tbl(disposed_by, bin_disposed, disposed_item_type, weight)
                                                    VALUES(:personnel_id, :bin_id, :trash_type, :weight)");
                $insert_dispose_log->execute([
                    ":personnel_id" => $personnel_id,
                    ":bin_id" => $bin_id,
                    ":trash_type" => $trash_item_type,
                    ":weight" => $weight
                ]);

                $conn->commit();

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Bin Emptied!",
                    "text" => "Bin emptied successfully!"
                ]);

                header("Location: ../../pages/personnel/home.php?page=bin-monitoring");
                exit();
            }

            catch (PDOException $e) {
                $conn->rollBack();

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occurred! Please try again.1"
                ]);

                header("Location: ../../pages/personnel/home.php?page=bin-monitoring");
                exit();
                
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-maintenance-status"])) {
        $maintenance_id = htmlspecialchars(trim(base64_decode($_POST["maintenance-id"])));
        $maintenance_status = htmlspecialchars($_POST["maintenance-status"]);
        $maintenance_description = htmlspecialchars($_POST["maintenance-description"]);

        if(empty($maintenance_id) || empty($maintenance_status) || empty($maintenance_description)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/personnel/home.php?page=maintenance-alerts");
            exit();
        }

        else if(!in_array($maintenance_status, $allowed_maintenance_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Type!",
                "text" => "Invalid status type! Please try again."
            ]);

            header("Location: ../../pages/personnel/home.php?page=maintenance-alerts");
            exit();
        }

        else {
            try {
                $update_maintenance_status = $conn->prepare("UPDATE maintenance_tbl
                                                            SET maintenance_description = :maintenance_description,
                                                            maintenance_status = :maintenance_status
                                                            WHERE maintenance_id = :maintenance_id
                                                            ");
                $update_maintenance_status->execute([
                    ":maintenance_description" => $maintenance_description,
                    ":maintenance_status" => $maintenance_status,
                    ":maintenance_id" => $maintenance_id
                ]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Maintenance Updated!",
                    "text" => "Maintenance status and description updated successfully!"
                ]);

                header("Location: ../../pages/personnel/home.php?page=maintenance-alerts");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/personnel/home.php?page=maintenance-alerts");
            exit();
            }
        }

    }
?>
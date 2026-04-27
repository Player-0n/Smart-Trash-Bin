<?php
    require_once "../../config/conn.config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-new-location"])) {
        $target_location = htmlspecialchars($_POST["target-location"]);

        if(empty($target_location)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=bin-locations");
            exit();
        }

        else {
            try {
                $check_location = $conn->prepare("SELECT * FROM bin_locations_tbl WHERE target_location = :target_location LIMIT 1");
                $check_location->execute([":target_location" => $target_location]);

                if($check_location->rowCount() === 1) {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Location Exists!",
                        "text" => "This location already exists! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-locations");
                    exit();
                }

                else {
                    $insert_location = $conn->prepare("INSERT INTO bin_locations_tbl(target_location) VALUES(:target_location)");
                    $insert_location->execute([":target_location" => $target_location]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Location Added!",
                        "text" => "New bin location has been added successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-locations");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-locations");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-location"])) {
        $location_id = htmlspecialchars(trim(base64_decode($_POST["location-id"])));
        $target_location = htmlspecialchars($_POST["target-location"]);

        if(empty($location_id) || empty($target_location)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=bin-locations");
            exit();
        }

        else {
            try {
                $check_location = $conn->prepare("SELECT * FROM bin_locations_tbl WHERE target_location = :target_location AND location_id != :location_id LIMIT 1");
                $check_location->execute([":target_location" => $target_location, ":location_id" => $location_id]);

                if($check_location->rowCount() === 1) {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Location Exists!",
                        "text" => "This location already exists! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-locations");
                    exit();
                }

                else {
                    $update_location = $conn->prepare("UPDATE bin_locations_tbl SET target_location = :target_location WHERE location_id = :location_id");
                    $update_location->execute([
                        ":target_location" => $target_location,
                        ":location_id" => $location_id
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Location Updated!",
                        "text" => "Bin location has been updated successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=bin-locations");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-locations");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-location"])) {
        $location_id = htmlspecialchars(trim(base64_decode($_POST["location-id"])));

        if(empty($location_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=bin-locations");
            exit();
        }

        else {
            try {
                $delete_location = $conn->prepare("DELETE FROM bin_locations_tbl WHERE location_id = :location_id");
                $delete_location->execute([":location_id" => $location_id]);
                
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Location Deleted!",
                    "text" => "Bin location has been deleted successfully!"
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-locations");
                exit();
                
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=bin-locations");
                exit();
            }
        }
    }
?>
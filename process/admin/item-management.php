<?php
    require_once "../../config/conn.config.php";
    require_once "../../config/dropdowns.config.php";

    // Insert Item
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-new-item"])) {

        $file_location = "../../uploads/items/";
        
        $item_image = $_FILES["item-image"]["name"];
        $item_tmp_name = $_FILES["item-image"]["tmp_name"];

        $file_extension = strtolower(pathinfo($item_image, PATHINFO_EXTENSION));

        $item_name = htmlspecialchars($_POST["item-name"]);
        $item_description = htmlspecialchars($_POST["item-description"]);
        $points_required = htmlspecialchars($_POST["points-required"]);
        $item_stocks = htmlspecialchars($_POST["item-stocks"]);

        if(empty($item_name) || empty($points_required) || empty($item_stocks)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else if(!in_array($file_extension, $allowed_img_format)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid File!",
                "text" => "Invalid file type! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else if(!filter_var($points_required, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Points!",
                "text" => "Points must be a number and greater than 0!"
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else if(!filter_var($item_stocks, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Stocks!",
                "text" => "Stocks must be a number and greater than 0!"
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else {

            try {
                $check_item = $conn->prepare("SELECT * FROM reward_items_tbl WHERE item_name = :item_name");
                $check_item->execute([":item_name" => $item_name]);

                if($check_item->rowCount() === 0) {

                    $unique_filename = 'item_' . $item_name . '_' . uniqid() . '.' . $file_extension;

                    $add_new_item = $conn->prepare("INSERT INTO reward_items_tbl(item_image, item_name, item_description, item_points, item_stocks)
                                                    VALUES(:item_image, :item_name, :item_description, :item_points, :item_stocks)
                                                ");
                    $add_new_item->execute([
                        ":item_image" => $unique_filename,
                        ":item_name" => $item_name,
                        ":item_description" => $item_description,
                        ":item_points" => $points_required,
                        ":item_stocks" => $item_stocks
                    ]);

                    $file_path = $file_location . $unique_filename;
                    move_uploaded_file($item_tmp_name, $file_path);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Item Added!",
                        "text" => "Item added successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=store-inventory");
                    exit();
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Item Exists!",
                        "text" => "This item is already exists! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=store-inventory");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=store-inventory");
                exit();
            }
        }
    }

    // Update Item
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-item"])) {
        $item_id = htmlspecialchars(base64_decode($_POST["item-id"]));
        $item_name = htmlspecialchars($_POST["item-name"]);
        $item_description = htmlspecialchars($_POST["item-description"]);
        $points_required = htmlspecialchars($_POST["points-required"]);
        $item_stocks = htmlspecialchars($_POST["item-stocks"]);

        if(empty($item_id) || empty($item_name) || empty($points_required) || empty($item_stocks)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else if(!filter_var($points_required, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Points!",
                "text" => "Points must be a number and greater than 0!"
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else if(!filter_var($item_stocks, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Stocks!",
                "text" => "Stocks must be a number and greater than 0!"
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else {
            try {
                $check_item = $conn->prepare("SELECT * FROM reward_items_tbl WHERE item_name = :item_name AND item_id != :item_id");
                $check_item->execute([":item_name" => $item_name, ":item_id" => $item_id]);

                if($check_item->rowCount() === 0) {
                    $update_item = $conn->prepare("UPDATE reward_items_tbl
                                                    SET item_name = :item_name,
                                                    item_description = :item_description,
                                                    item_points = :item_points,
                                                    item_stocks = :item_stocks
                                                    WHERE item_id = :item_id
                                                ");
                    $update_item->execute([
                        ":item_name" => $item_name,
                        ":item_description" => $item_description,
                        ":item_points" => $points_required,
                        ":item_stocks" => $item_stocks,
                        ":item_id" => $item_id
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Item Updated!",
                        "text" => "Item updated successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=store-inventory");
                    exit();
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Item Exists!",
                        "text" => "This item is already exists! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=store-inventory");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=store-inventory");
                exit();
            }
        }
    }

    // Delete Item
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-item"])) {
        $item_id = htmlspecialchars(base64_decode($_POST["item-id"]));

        if(empty($item_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=store-inventory");
            exit();
        }

        else {
            try {
                $delete_item = $conn->prepare("DELETE FROM reward_items_tbl WHERE item_id = :item_id");
                $delete_item->execute([":item_id" => $item_id]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Item Deleted",
                    "text" => "Item deleted successfully!"
                ]);

                header("Location: ../../pages/admin/home.php?page=store-inventory");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=store-inventory");
                exit();
            }
        }
    }

    // Approve Redemption
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["approve-request"])) {
        
        $transaction_id = htmlspecialchars(trim(base64_decode($_POST["transaction-id"])));
        
        if(empty($transaction_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=student-redemptions");
            exit();
        }

        else {
            try {
               $update_transaction_status = $conn->prepare("UPDATE transactions_tbl
                                                        SET redeem_status = :redeem_status,
                                                        redeemed_at = CURRENT_TIMESTAMP()
                                                        WHERE transaction_id = :transaction_id
                                                        ");
                $update_transaction_status->execute([
                    ":redeem_status" => "Approved",
                    ":transaction_id" => $transaction_id
                ]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Request Approved!",
                    "text" => "This request has been approved!"
                ]);

                header("Location: ../../pages/admin/home.php?page=student-redemptions");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=student-redemptions");
                exit();
            }
        }
    }

    // Reject Redemption
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["decline-request"])) {
        $transaction_id = htmlspecialchars(trim(base64_decode($_POST["transaction-id"])));
        
        if(empty($transaction_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=student-redemptions");
            exit();
        }

        else {
            try {
                $conn->beginTransaction();

                $check_transaction = $conn->prepare("SELECT
                                                        tt.*,
                                                        rt.item_stocks,
                                                        sa.student_points
                                                    FROM transactions_tbl tt
                                                    LEFT JOIN reward_items_tbl rt
                                                    ON tt.item_redeemed = rt.item_id
                                                    LEFT JOIN student_accounts_tbl sa
                                                    ON tt.redeemed_by = sa.student_lrn
                                                    WHERE tt.transaction_id = :transaction_id
                                                    ");
                $check_transaction->execute([":transaction_id" => $transaction_id]);

                if($check_transaction->rowCount() === 1) {
                    $transaction_data = $check_transaction->fetch(PDO::FETCH_OBJ);

                    // Student and Points
                    $redeemed_by = $transaction_data->redeemed_by;       
                    $current_student_points = $transaction_data->student_points;

                    // Item and Stocks
                    $item_id = $transaction_data->item_redeemed;
                    $current_item_stocks = $transaction_data->item_stocks;

                    // Item Quantity and Total Points
                    $item_quantity = $transaction_data->item_quantity;
                    $total_points = $transaction_data->total_points;

                    // Update Stocks
                    $updated_item_stocks = $current_item_stocks + $item_quantity;
                    $updated_student_points = $current_student_points + $total_points;

                    $update_stocks = $conn->prepare("UPDATE reward_items_tbl SET item_stocks = :new_stocks WHERE item_id = :item_id");
                    $update_stocks->execute([
                        ":new_stocks" => $updated_item_stocks,
                        ":item_id" => $item_id
                    ]);

                    // Updated Points
                    $update_points = $conn->prepare("UPDATE student_accounts_tbl SET student_points = :new_points WHERE student_lrn = :student_lrn");
                    $update_points->execute([
                        ":new_points" => $updated_student_points,
                        ":student_lrn" => $redeemed_by
                    ]);

                    // Update Transaction Status
                    $update_transaction_status = $conn->prepare("UPDATE transactions_tbl
                                                        SET redeem_status = :redeem_status,
                                                        redeemed_at = :redeemed_at
                                                        WHERE transaction_id = :transaction_id
                                                        ");
                    $update_transaction_status->execute([
                        ":redeem_status" => "Declined",
                        ":redeemed_at" => "Declined",
                        ":transaction_id" => $transaction_id
                    ]);

                    $conn->commit();

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Request Declined!",
                        "text" => "This request has been declined!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=student-redemptions");
                    exit();

                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Invalid Transaction!",
                        "text" => "Transaction not found! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=student-redemptions");
                    exit();
                }
            }

            catch(PDOException $e) {
                $conn->rollBack();

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=student-redemptions");
                exit();
            }
        }
    }

?>

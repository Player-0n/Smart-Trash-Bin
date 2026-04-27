<?php
    require_once "../../config/conn.config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["redeem-item"])) {
        $student_lrn = htmlspecialchars(trim($_SESSION["student-lrn"]));
        $item_id = htmlspecialchars(trim(base64_decode($_POST["item-id"])));
        $quantity = (int)htmlspecialchars(trim($_POST["quantity"]));

        if(empty($student_lrn) || empty($item_id) || empty($quantity)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=redemption-store");
            exit();
        }

        else if(!filter_var($quantity, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid Quantity!",
                "text" => "Quantity must be a number and minimum of 1! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=redemption-store");
            exit();
        }

        else {
            try {
                $conn->beginTransaction();

                $check_account = $conn->prepare("SELECT student_lrn, student_points FROM student_accounts_tbl WHERE student_lrn = :student_lrn LIMIT 1");
                $check_account->execute([":student_lrn" => $student_lrn]);
                
                if($check_account->rowCount() === 1) {
                    $student_data = $check_account->fetch(PDO::FETCH_OBJ);

                    $check_item = $conn->prepare("SELECT * FROM reward_items_tbl WHERE item_id = :item_id LIMIT 1");
                    $check_item->execute([":item_id" => $item_id]);

                    if($check_item->rowCount() === 1) {
                        $item_data = $check_item->fetch(PDO::FETCH_OBJ);

                        $student_points = (int)$student_data->student_points;
                        $required_points = (int)$item_data->item_points;
                        $item_stocks = (int)$item_data->item_stocks;

                        $total_points = $quantity * $required_points;

                        if($quantity > $item_stocks) {
                            $_SESSION["alert-status"] = json_encode([
                                "icon" => "error",
                                "title" => "Invalid Quantity!",
                                "text" => "The quantity you entered exceeds the available stock. Please adjust and try again."
                            ]);

                            header("Location: ../../pages/student/home.php?page=redemption-store");
                            exit();
                        }

                        else if($total_points > $student_points) {
                            $_SESSION["alert-status"] = json_encode([
                                "icon" => "error",
                                "title" => "Insufficient Points!",
                                "text" => "You don’t have enough points to redeem this item. Please check your balance and try again."
                            ]);

                            header("Location: ../../pages/student/home.php?page=redemption-store");
                            exit();
                        }

                        else {
                            $insert_transaction = $conn->prepare("INSERT INTO transactions_tbl(redeemed_by, item_redeemed, item_quantity, points_per_item, total_points)
                                                                VALUES(:student_lrn, :item_id, :quantity, :points_per_item, :total_points)
                                                                ");
                            $insert_transaction->execute([
                                ":student_lrn" => $student_lrn,
                                ":item_id" => $item_id,
                                ":quantity" => $quantity,
                                ":points_per_item" => $required_points,
                                ":total_points" => $total_points
                            ]);

                            $new_stocks = $item_stocks - $quantity;
                            $new_points = $student_points - $total_points;

                            $update_stock = $conn->prepare("UPDATE reward_items_tbl SET item_stocks = :new_stocks WHERE item_id = :item_id");
                            $update_stock->execute([
                                ":new_stocks" => $new_stocks,
                                ":item_id" => $item_id
                            ]);

                            $update_points = $conn->prepare("UPDATE student_accounts_tbl SET student_points = :new_points WHERE student_lrn = :student_lrn");
                            $update_points->execute([
                                ":new_points" => $new_points,
                                ":student_lrn" => $student_lrn
                            ]);

                            $conn->commit();

                            $_SESSION["alert-status"] = json_encode([
                                "icon" => "info",
                                "title" => "Redemption Pending",
                                "text" => "You’ve already requested to redeem this item. Please visit the school’s faculty room to get it approved and claim your item."
                            ]);

                            header("Location: ../../pages/student/home.php?page=redemption-store");
                            exit();

                        }
                    }

                    else {
                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "error",
                            "title" => "Invalid Item!",
                            "text" => "Item not found! Please try again."
                        ]);

                        header("Location: ../../pages/student/home.php?page=redemption-store");
                        exit();
                    }
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Invalid Account!",
                        "text" => "Account not found! Please try again."
                    ]);

                    header("Location: ../../pages/student/home.php?page=redemption-store");
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

                header("Location: ../../pages/student/home.php?page=redemption-store");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancel-request"])) {
        $transaction_id = htmlspecialchars(trim(base64_decode($_POST["transaction-id"])));
        $student_lrn = htmlspecialchars(trim($_SESSION["student-lrn"]));

        if(empty($student_lrn) || empty($transaction_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/student/home.php?page=pending-requests");
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

                    $redeemed_by = $transaction_data->redeemed_by;       
                    $current_student_points = $transaction_data->student_points;

                    $item_id = $transaction_data->item_redeemed;
                    $current_item_stocks = $transaction_data->item_stocks;

                    $item_quantity = $transaction_data->item_quantity;
                    $total_points = $transaction_data->total_points;

                    if($redeemed_by === $student_lrn) {
                        $updated_item_stocks = $current_item_stocks + $item_quantity;
                        $updated_student_points = $current_student_points + $total_points;

                        $update_stocks = $conn->prepare("UPDATE reward_items_tbl SET item_stocks = :new_stocks WHERE item_id = :item_id");
                        $update_stocks->execute([
                            ":new_stocks" => $updated_item_stocks,
                            ":item_id" => $item_id
                        ]);

                        $update_points = $conn->prepare("UPDATE student_accounts_tbl SET student_points = :new_points WHERE student_lrn = :student_lrn");
                        $update_points->execute([
                            ":new_points" => $updated_student_points,
                            ":student_lrn" => $redeemed_by
                        ]);

                        $delete_transaction = $conn->prepare("DELETE FROM transactions_tbl WHERE transaction_id = :transaction_id");
                        $delete_transaction->execute([":transaction_id" => $transaction_id]);

                        $conn->commit();

                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "success",
                            "title" => "Request Cancelled!",
                            "text" => "The request of redeeming item cancelled!"
                        ]);

                        header("Location: ../../pages/student/home.php?page=pending-requests");
                        exit();
                    }

                    else {
                        $_SESSION["alert-status"] = json_encode([
                            "icon" => "error",
                            "title" => "Invalid Student!",
                            "text" => "Invalid student! Please try again."
                        ]);

                        header("Location: ../../pages/student/home.php?page=pending-requests");
                        exit();
                    }
                }

                else {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Invalid Transaction!",
                        "text" => "Transaction not found! Please try again."
                    ]);

                    header("Location: ../../pages/student/home.php?page=pending-requests");
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

                header("Location: ../../pages/student/home.php?page=pending-requests");
                exit();
            }
        }
    }
?>
<?php
    require_once "../../config/conn.config.php";
    require_once "../../config/dropdowns.config.php";
    require_once "../../config/file-upload/vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["upload-student-files"])) {
        $file_location = "../../uploads/files/";

        $file_name = $_FILES["student-files"]["name"];
        $file_tmp_name = $_FILES["student-files"]["tmp_name"];
        $file_size = $_FILES["student-files"]["size"];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (empty($file_name)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
        } 
        
        else if (!in_array($file_extension, $allowed_file_extension)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Invalid File!",
                "text" => "Invalid file type! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
        } 
        
        else {

            $unique_filename = $file_extension . '_' . $file_name . '_' . uniqid() . '.' . $file_extension;

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp_name);
            $data = $spreadsheet->getActiveSheet()->toArray();

            if (count($data) < 1) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Empty File!",
                    "text" => "This file has no data inserted! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=enrolled-students");
                exit();
            }

            $headers = array_map('strtolower', array_map('trim', $data[0]));
            $allowed_headers = ['student lrn', 'first name', 'last name'];

            if (count($headers) !== 3 || $headers !== $allowed_headers) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Invalid File!",
                    "text" => "Invalid file format! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=enrolled-students");
                exit();
            }

            try {

                for ($i = 1; $i < count($data); $i++) {
                    $row = $data[$i];

                    $student_lrn = trim($row[0]);
                    $first_name = $row[1];
                    $last_name = $row[2];

                    if (empty($student_lrn) || empty($first_name) || empty($last_name)) {
                        continue;
                    }


                    $check_student = $conn->prepare("SELECT * FROM enrolled_students_tbl WHERE student_lrn = :student_lrn");
                    $check_student->execute([":student_lrn" => $student_lrn]);

                    if ($check_student->rowCount() > 0) {
                        $update_student = $conn->prepare("UPDATE enrolled_students_tbl
                                                            SET first_name = :first_name,
                                                            last_name = :last_name
                                                            WHERE student_lrn = :student_lrn      
                                                        ");
                        $update_student->execute([
                            ":first_name" => $first_name,
                            ":last_name" => $last_name,
                            ":student_lrn" => $student_lrn
                        ]);
                    } else {
                        $insert_new_student = $conn->prepare("INSERT INTO enrolled_students_tbl(student_lrn, first_name, last_name)
                                                                VALUES(:student_lrn, :first_name, :last_name)    
                                                                ");
                        $insert_new_student->execute([
                            ":student_lrn" => $student_lrn,
                            ":first_name" => $first_name,
                            ":last_name" => $last_name
                        ]);
                    }
                }

                $unique_filename = $file_extension . "_" . $file_name . "_" . uniqid() . "." . $file_extension;
                $format_file_size = number_format($file_size / 1048576, 2);

                $insert_uploaded_file = $conn->prepare("INSERT INTO uploaded_files_tbl(file_type, file_name, file_size, uploaded_by)      
                                                        VALUES(:file_type, :file_name, :file_size, :uploaded_by)");
                $insert_uploaded_file->execute([
                    ":file_type" => $file_extension,
                    ":file_name" => $unique_filename,
                    ":file_size" => $format_file_size,
                    ":uploaded_by" => $_SESSION["admin-id"]
                ]);

                $upload_file_path = $file_location . $unique_filename;
                move_uploaded_file($file_tmp_name, $upload_file_path);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Import Success!",
                    "text" => "Student data imported successfully!"
                ]);
            } 
            
            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Error Importing!",
                    "text" => "Error importing file! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=enrolled-students");
                exit();
            }

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-student"])) {
        $student_lrn = htmlspecialchars(trim($_POST["student-lrn"]));
        $first_name = htmlspecialchars($_POST["first-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);

        if(empty($student_lrn) || empty($first_name) || empty($last_name)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
        } 

        else {
            try {
                $check_student = $conn->prepare("SELECT * FROM enrolled_students_tbl WHERE student_lrn = :student_lrn");
                $check_student->execute([":student_lrn" => $student_lrn]);

                if($check_student->rowCount() > 0) {
                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "error",
                        "title" => "Student Exist!",
                        "text" => "This student is already exists! Please try again."
                    ]);

                    header("Location: ../../pages/admin/home.php?page=enrolled-students");
                    exit();
                }

                else {
                    $insert_student = $conn->prepare("INSERT INTO enrolled_students_tbl(student_lrn, first_name, last_name)
                                                    VALUES(:student_lrn, :first_name, :last_name)
                                                    ");
                    $insert_student->execute([
                        ":student_lrn" => $student_lrn,
                        ":first_name" => $first_name,
                        ":last_name" => $last_name
                    ]);

                    $_SESSION["alert-status"] = json_encode([
                        "icon" => "success",
                        "title" => "Student Added!",
                        "text" => "Student Added Successfully!"
                    ]);

                    header("Location: ../../pages/admin/home.php?page=enrolled-students");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=enrolled-students");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-student"])) {
        $student_lrn = htmlspecialchars(trim(base64_decode($_POST["student-lrn"])));

        if(empty($student_lrn)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
        }

        else {
            try {
                $delete_student = $conn->prepare("DELETE FROM enrolled_students_tbl WHERE student_lrn = :student_lrn");
                $delete_student->execute([":student_lrn" => $student_lrn]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Student Deleted!",
                    "text" => "Student deleted successfully!"
                ]);

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=enrolled-students");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-file"])) {
        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));

        if(empty($file_id)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
        }

        else {
            try {
                $delete_file = $conn->prepare("DELETE FROM uploaded_files_tbl WHERE file_id = :file_id");
                $delete_file->execute([":file_id" => $file_id]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "File Deleted!",
                    "text" => "File deleted successfully!"
                ]);

            header("Location: ../../pages/admin/home.php?page=enrolled-students");
            exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                    "icon" => "error",
                    "title" => "Unknown Error!",
                    "text" => "An unknown error occured! Please try again."
                ]);

                header("Location: ../../pages/admin/home.php?page=enrolled-students");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["set-student-account-status"])) {
        $student_lrn = htmlspecialchars(trim(base64_decode($_POST["student-lrn"])));
        $account_status = htmlspecialchars(trim($_POST["account-status"]));

        if(empty($student_lrn) || empty($account_status)) {
            $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=student-accounts");
            exit();
        }

        else {
            try {
                $locked_account = $account_status === "Yes" ? "Locked" : "Unlocked";
                $text = $account_status === "Yes"
                    ? "This account has been locked!"
                    : "This account has been unlocked!";

                $set_account_status = $conn->prepare("UPDATE student_accounts_tbl SET locked_account = :locked_account WHERE student_lrn = :student_lrn");
                $set_account_status->execute([
                    ":locked_account" => $account_status,
                    ":student_lrn" => $student_lrn
                ]);

                $_SESSION["alert-status"] = json_encode([
                    "icon" => "success",
                    "title" => "Account " . $locked_account . "!",
                    "text" => $text
                ]);

                header("Location: ../../pages/admin/home.php?page=student-accounts");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["alert-status"] = json_encode([
                "icon" => "error",
                "title" => "Unknown Error!",
                "text" => "An unknown error occured! Please try again."
            ]);

            header("Location: ../../pages/admin/home.php?page=student-accounts");
            exit();
            }
        }
    }

?>

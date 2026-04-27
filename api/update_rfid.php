<?php
// Include database connection
require_once("../config/conn.config.php"); // corrected path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_lrn'], $_POST['student_rfid'])) {

        $student_lrn = trim($_POST['student_lrn']);
        $student_rfid = trim($_POST['student_rfid']);

        $stmt = $conn->prepare("UPDATE student_accounts_tbl SET student_rfid = :rfid WHERE student_lrn = :lrn");
        $stmt->bindParam(':rfid', $student_rfid, PDO::PARAM_STR);
        $stmt->bindParam(':lrn', $student_lrn, PDO::PARAM_STR);
        $stmt->execute();

       header("Location: ../pages/admin/home.php?page=student-accounts");
exit();
    }
}
?>

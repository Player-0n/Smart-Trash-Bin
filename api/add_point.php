<?php
// ===== Database Connection =====
require_once("../config/conn.config.php"); // your conn.php

// ===== Headers =====
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ===== Read JSON Input =====
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON format"]);
    exit;
}

// ===== Required Fields =====
$required = ["unique_bin_id", "plastic_fill_level", "metal_fill_level", "student_rfid", "motion1", "motion2"];
foreach ($required as $field) {
    if (!isset($data[$field])) {
        echo json_encode(["status" => "error", "message" => "Missing field: $field"]);
        exit;
    }
}

$unique_id = $data["unique_bin_id"];
$plastic = $data["plastic_fill_level"];
$metal = $data["metal_fill_level"];
$student_rfid = $data["student_rfid"];
$motion1 = $data["motion1"] ? 0.5 : 0; // points for plastic
$motion2 = $data["motion2"] ? 1.0 : 0; // points for metal

try {
    // ===== Update or Insert Bin =====
    $stmt = $conn->prepare("SELECT COUNT(*) FROM bins_tbl WHERE unique_bin_id=?");
    $stmt->execute([$unique_id]);
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        $updateBin = $conn->prepare("UPDATE bins_tbl SET plastic_fill_level=?, metal_fill_level=?, bin_status='Active', updated_at=NOW() WHERE unique_bin_id=?");
        $updateBin->execute([$plastic, $metal, $unique_id]);
    } else {
        $insertBin = $conn->prepare("INSERT INTO bins_tbl (unique_bin_id, plastic_fill_level, metal_fill_level, bin_status, added_at, updated_at) VALUES (?, ?, ?, 'Active', NOW(), NOW())");
        $insertBin->execute([$unique_id, $plastic, $metal]);
    }

    // ===== If RFID is detected =====
    if ($student_rfid !== "--") {
        // Update student points
        $updatePoints = $conn->prepare("UPDATE student_accounts_tbl SET student_points = student_points + ? WHERE student_rfid = ?");
        $updatePoints->execute([$motion1 + $motion2, $student_rfid]);

        // Get student_lrn
        $getStudent = $conn->prepare("SELECT student_lrn FROM student_accounts_tbl WHERE student_rfid = ?");
        $getStudent->execute([$student_rfid]);
        $student_lrn = $getStudent->fetchColumn();

        // Get bin_id
        $getBinId = $conn->prepare("SELECT bin_id FROM bins_tbl WHERE unique_bin_id = ?");
        $getBinId->execute([$unique_id]);
        $bin_id = $getBinId->fetchColumn();

        if ($student_lrn && $bin_id) {
            // Insert logs
            if ($motion1 > 0) {
                $insertPlasticLog = $conn->prepare("INSERT INTO student_disposal_log_tbl (disposed_by, bin_used, disposed_item_type, points_gained) VALUES (?, ?, 'plastic', ?)");
                $insertPlasticLog->execute([$student_lrn, $bin_id, $motion1]);
            }
            if ($motion2 > 0) {
                $insertMetalLog = $conn->prepare("INSERT INTO student_disposal_log_tbl (disposed_by, bin_used, disposed_item_type, points_gained) VALUES (?, ?, 'metal', ?)");
                $insertMetalLog->execute([$student_lrn, $bin_id, $motion2]);
            }
        }
    }

    echo json_encode(["status" => "success", "message" => "Data updated successfully"]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>

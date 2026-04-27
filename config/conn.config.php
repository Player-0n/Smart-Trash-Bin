<?php
    //require_once "session.config.php";
    session_start();
    
    $db_host = "localhost";
    $db_username = "marbcsah_smarttbin";
    $db_password = "Sm4rtSm4rt?";
    $db_name = "marbcsah_stb_final";

    try {
        $data_source = "mysql:host=" . $db_host . ";dbname=" . $db_name . ";charset=utf8mb4";
        $conn = new PDO($data_source, $db_username, $db_password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    catch(PDOException $e) {
        error_log("Error Occured: " . $e->getMessage());
        die("Error Connecting to Database: " . $e->getMessage());
    }
?>
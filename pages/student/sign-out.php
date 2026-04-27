<?php
    session_start();

    unset($_SESSION["student-lrn"]);

    header("Location: ../index.php");
?>
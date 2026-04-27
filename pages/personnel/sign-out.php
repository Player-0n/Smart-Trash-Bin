<?php
    session_start();

    unset($_SESSION["personnel-id"]);

    header("Location: ../index.php");
?>
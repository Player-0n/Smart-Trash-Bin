<?php
    date_default_timezone_set("Asia/Manila");

    ini_set("session.use_only_cookies", 1);
    ini_set("session.use_strict_mode", 1);
    ini_set("session.cookie_lifetime", 0);

    session_set_cookie_params([
        "lifetime" => 60 * 300,
        "domain" => "localhost",
        "path" => "/",
        "secure" => true,
        "httponly" => true
    ]);

    session_start();

    if(!isset($_SESSION["regenerate_id"])) {
        session_regenerate_id(true);
        $_SESSION["regenerate_id"] = time();
    }

    else {
        $interval = 60 * 300;

        if(time() - $_SESSION["regenerate_id"] >= $interval) {
            session_regenerate_id(true);
            $_SESSION["regenerate_id"] = time();
        }
    }
?>
<?php
    session_start();

    if (empty($_SESSION["locked-account"]) || !isset($_SESSION["locked-account"])) {
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Account Locked </title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/global/images/stb-logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
            color: #333;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 2.5rem 2rem;
            max-width: 420px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .icon-wrapper {
            font-size: 4rem;
            color: #7cbf42;
        }
    </style>
</head>

<body>

    <div class="container text-center">

        <div class="card mx-auto">

            <div class="icon-wrapper mb-4">
                <i class="bi bi-lock-fill"></i>
            </div>

            <h3 class="mb-3">Account Locked</h3>

            <p>
                Your account has been locked for security reasons.<br>
                Please contact support to resolve the issue.
            </p>

            <a href="back-to-main.php" class="btn btn-primary">
                Back to Home
            </a>

        </div>
    </div>

</body>

</html>

<script>
    window.addEventListener("unload", function() {
        navigator.sendBeacon("unset-session.php");
    });
</script>
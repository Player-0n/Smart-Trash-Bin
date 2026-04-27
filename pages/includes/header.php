<!-- Header -->

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">

        <a href="home.php" class="logo d-flex align-items-center gap-0">
            <img src="../../assets/global/images/stb-logo.png" alt="Website Logo">
            <img src="../../assets/global/images/stb-name.png" alt="Website Logo">
        </a>

        <i class="bi bi-list toggle-sidebar-btn"></i>

    </div>

    <nav class="header-nav ms-auto">

        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="<?php echo htmlspecialchars($file_path . $profile_picture); ?>" alt="Profile Picture" class="rounded-circle" width="40" height="40">
                    <span class="d-none d-md-block dropdown-toggle ps-2"> <?php echo htmlspecialchars($first_name . " " . $last_name); ?> </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li class="dropdown-header">

                        <h6> <?php echo htmlspecialchars($first_name . " " . $last_name); ?> </h6>
                        <span> <?php echo htmlspecialchars($role); ?> </span>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="home.php?page=user-profile">
                            <i class="bi bi-person"></i>
                            <span> My Profile </span>
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <a class="dropdown-item d-flex align-items-center" href="sign-out.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span> Sign Out </span>
                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </nav>

</header>

<!-- End Header -->
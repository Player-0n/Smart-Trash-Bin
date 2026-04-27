<!-- Sidebar -->

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="home.php?page=dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Accounts Management -->
        <li class="nav-item">

            <?php
                $accounts_management_pages = [
                    "admin-accounts",
                    "personnel-accounts"
                ];

                $account_active_page = in_array($page_name, $accounts_management_pages);
            ?>

            <a class="nav-link <?php echo htmlspecialchars($account_active_page ? "" : "collapsed"); ?>" data-bs-target="#accounts-management-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i>
                <span> Accounts Management </span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="accounts-management-nav" class="nav-content collapse <?php echo htmlspecialchars($account_active_page ? "show" : ""); ?>" data-bs-parent="#sidebar-nav">

                <li class="<?php echo htmlspecialchars($page_name === "admin-accounts" ? "active-page" : ""); ?>">
                    <a href="home.php?page=admin-accounts">
                        <i class="bi bi-person-gear"></i><span> Administrator Accounts </span>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "personnel-accounts" ? "active-page" : ""); ?>">
                    <a href="home.php?page=personnel-accounts">
                        <i class="bi bi-person-badge"></i><span> Personnel Accounts </span>
                    </a>
                </li>

            </ul>

        </li>

        <!-- Student Management -->
        <li class="nav-item">

            <?php
                $student_management_pages = [
                    "enrolled-students",
                    "student-accounts",
                    "student-redemptions",
                    "redemption-log"
                ];

                $student_page_active = in_array($page_name, $student_management_pages);
            ?>

            <a class="nav-link <?php echo htmlspecialchars($student_page_active ? "" : "collapsed"); ?>" data-bs-target="#students-management-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-mortarboard"></i>
                <span> Student Management </span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="students-management-nav" class="nav-content collapse <?php echo htmlspecialchars($student_page_active ? "show" : ""); ?>" data-bs-parent="#sidebar-nav">

                <li class="<?php echo htmlspecialchars($page_name === "enrolled-students" ? "active-page" : ""); ?>">
                    <a href="home.php?page=enrolled-students">
                        <i class="bi bi-person-lines-fill"></i><span> Enrolled Students </span>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "student-accounts" ? "active-page" : ""); ?>">
                    <a href="home.php?page=student-accounts">
                        <i class="bi bi-person-vcard"></i><span> Student Accounts </span>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "student-redemptions" ? "active-page" : ""); ?> position-relative">
                    <a href="home.php?page=student-redemptions">
                        <i class="bi bi-gift"></i>
                        <span> Student Redemptions </span>
                        
                        <?php
                            if($pending_redeem_count > 0):
                        ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                <?php echo htmlspecialchars($pending_redeem_count); ?>
                                <span class="visually-hidden">pending redemptions</span>
                            </span>
                        <?php
                            endif;
                        ?>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "redemption-log" ? "active-page" : ""); ?>">
                    <a href="home.php?page=redemption-log">
                        <i class="bi bi-hourglass-split"></i><span> Redemption Logs </span>
                    </a>
                </li>

            </ul>

        </li>

        <!-- Trash Management -->
        <li class="nav-item">

            <?php
                $trash_management_pages = [
                    "bin-monitoring",
                    "bin-locations",
                    "maintenance-alerts",
                    "disposal-logs",
                    "student-disposal-logs"
                ];

                $trash_mangement_page_active = in_array($page_name, $trash_management_pages);
            ?>

            <a class="nav-link <?php echo htmlspecialchars($trash_mangement_page_active ? "" : "collapsed"); ?>" data-bs-target="#trash-management-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-trash"></i><span> Trash Management </span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="trash-management-nav" class="nav-content collapse <?php echo htmlspecialchars($trash_mangement_page_active ? "show" : ""); ?>" data-bs-parent="#sidebar-nav">

                <li class="<?php echo htmlspecialchars($page_name === "bin-monitoring" ? "active-page" : ""); ?>">
                    <a href="home.php?page=bin-monitoring">
                        <i class="bi bi-graph-up-arrow"></i><span> Trash Bin Monitoring </span>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "bin-locations" ? "active-page" : ""); ?>">
                    <a href="home.php?page=bin-locations">
                        <i class="bi bi-geo-alt"></i><span> Location Management </span>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "maintenance-alerts" ? "active-page" : ""); ?>">
                    <a href="home.php?page=maintenance-alerts">
                        <i class="bi bi-tools"></i><span> Maintenance & Alerts </span>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "disposal-logs" ? "active-page" : ""); ?>">
                    <a href="home.php?page=disposal-logs">
                        <i class="bi bi-journal-text"></i><span> Disposal Logs </span>
                    </a>
                </li>

                <li class="<?php echo htmlspecialchars($page_name === "student-disposal-logs" ? "active-page" : ""); ?>">
                    <a href="home.php?page=student-disposal-logs">
                        <i class="bi bi-recycle"></i><span> Student Disposal Logs </span>
                    </a>
                </li>

            </ul>

        </li>

        <!-- Inventory -->
        <li class="nav-item">

            <a class="nav-link <?php echo htmlspecialchars($page_name === "store-inventory" ? "" : "collapsed"); ?>" href="home.php?page=store-inventory">
                <i class="bi bi-box-seam"></i><span> Redeem Store Inventory </span>
            </a>

        </li>

        <li class="nav-heading" style="color:black"> My Profile </li>

        <!-- Profile -->
        <li class="nav-item">

            <a class="nav-link <?php echo htmlspecialchars($page_name === "user-profile" ? "" : "collapsed"); ?>" href="home.php?page=user-profile">
                <i class="bi bi-person"></i><span> Profile </span>
            </a>

        </li>

        <!-- Signout -->
        <li class="nav-item">

            <a class="nav-link collapsed" href="sign-out.php">
                <i class="bi bi-box-arrow-left"></i><span> Sign Out </span>
            </a>

        </li>

    </ul>

</aside>

<!-- End Sidebar -->
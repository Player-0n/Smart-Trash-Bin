<!-- Sidebar -->

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="home.php?page=dashboard">
                <i class="bi bi-grid"></i>
                <span> Dashboard </span>
            </a>
        </li>

        <!-- Bin Monitoring -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "bin-monitoring" ? "" : "collapsed"); ?>" href="home.php?page=bin-monitoring">
                <i class="bi bi-graph-up-arrow"></i>
                <span> Trash Bin Monitoring </span>
            </a>
        </li>

        <!-- Maintenance Alert -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "maintenance-alerts" ? "" : "collapsed"); ?> position-relative" href="home.php?page=maintenance-alerts">
                <i class="bi bi-tools"></i>
                <span> Maintenance and Alert </span>

                <?php
                    if($maintenance_count > 0):
                ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                        <?php echo htmlspecialchars($maintenance_count); ?>
                        <span class="visually-hidden">pending maintenance</span>
                    </span>
                <?php
                    endif;
                ?>
            </a>
        </li>

        <!-- Disposal Logs -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "disposal-logs" ? "" : "collapsed"); ?>" href="home.php?page=disposal-logs">
                <i class="bi bi-recycle"></i>
                <span> Disposal Logs </span>
            </a>
        </li>

        <li class="nav-heading" style="color:black"> My Profile </li>

        <!-- Profile -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "user-profile" ? "" : "collapsed"); ?>" href="home.php?page=user-profile">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

        <!-- Sign Out -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="sign-out.php">
                <i class="bi bi-box-arrow-left"></i>
                <span>Sign Out</span>
            </a>
        </li>

    </ul>

</aside>

<!-- End Sidebar -->
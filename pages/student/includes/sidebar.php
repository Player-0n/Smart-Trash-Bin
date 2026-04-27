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

        <!-- Redemption Store -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "redemption-store" ? "" : "collapsed"); ?>" href="home.php?page=redemption-store">
                <i class="bi bi-shop-window"></i>
                <span>Redemption Store</span>
            </a>
        </li>

        <!-- Redemption History -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "redemption-log" ? "" : "collapsed"); ?>" href="home.php?page=redemption-log">
                <i class="bi bi-clock-history"></i>
                <span> My Redemption Logs </span>
            </a>
        </li>

        <!-- Pending Requests -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "pending-requests" ? "" : "collapsed"); ?>" href="home.php?page=pending-requests">
                <i class="bi bi-hourglass-split"></i>
                <span> Pending Requests </span>
            </a>
        </li>

        <!-- Bin Log -->
        <li class="nav-item">
            <a class="nav-link <?php echo htmlspecialchars($page_name === "disposal-logs" ? "" : "collapsed"); ?>" href="home.php?page=disposal-logs">
                <i class="bi bi-recycle"></i>
                <span> Trash Disposal Logs </span>
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
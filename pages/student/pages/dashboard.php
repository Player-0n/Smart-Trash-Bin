<?php
// Redeemed Items
$get_redeem_count = $conn->prepare("SELECT 
                                        COUNT(*) AS 'redeem_count'
                                    FROM transactions_tbl
                                    WHERE redeem_status = :redeem_status
                                    AND redeemed_by = :student_lrn
                                    ");
$get_redeem_count->execute([
    ":redeem_status" => "Approved",
    ":student_lrn" => $student_lrn
]);

$redeem_count = $get_redeem_count->fetch()["redeem_count"];

// Pending Items
$get_pending_count = $conn->prepare("SELECT 
                                        COUNT(*) AS 'pending_count'
                                    FROM transactions_tbl
                                    WHERE redeem_status = :redeem_status
                                    AND redeemed_by = :student_lrn
                                    ");
$get_pending_count->execute([
    ":redeem_status" => "Pending",
    ":student_lrn" => $student_lrn
]);

$pending_count = $get_pending_count->fetch()["pending_count"];
?>

<!-- Main -->
<main id="main" class="main">

    <!-- Pagetitle -->
    <?php
    include_once "includes/pagetitle.php";
    ?>
    <!-- End Pagetitle -->

    <section class="section dashboard">

        <div class="row">

            <!-- Welcome Message -->
            <div class="col-12">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body py-4 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Left: Welcome Message -->
                            <div>
                                <h4 class="fw-bold mb-2" style="color: #7CBF42;">
                                    <i class="bi bi-person-circle me-2"></i>Welcome, <?php echo htmlspecialchars($first_name); ?>!
                                </h4>
                                <p class="mb-0 fs-6 text-secondary">
                                    Here’s an overview of the <strong>Smart Trash Bin System</strong>.
                                </p>
                            </div>

                            <div class="text-end">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <?php echo $student_points ?? '0'; ?> Points
                                </h5>
                                <small class="text-muted">My Points</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redeemed Items -->
            <div class="col-xxl-3 col-md-6 mb-4">

                <a href="home.php?page=redemption-log">
                    <div class="card info-card shadow-sm border-0 hover-shadow">
                        <div class="card-body">
                            <h5 class="card-title text-dark mb-3">
                                <i class="bi bi-gift-fill me-2 text-success"></i>Redeemed Items
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-gift-fill fs-4"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 class="mb-0 text-dark"> <?php echo htmlspecialchars($redeem_count); ?> </h6>
                                    <span class="text-muted small"> Items You Claimed </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
            <!-- End Redeemed Items -->

            <!-- Pending Requests -->
            <div class="col-xxl-3 col-md-6 mb-4">

                <a href="home.php?page=pending-requests">
                    <div class="card info-card shadow-sm border-0 hover-shadow">
                        <div class="card-body">
                            <h5 class="card-title text-dark mb-3">
                                <i class="bi bi-hourglass-split me-2 text-primary"></i>Pending Requests
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-hourglass-split fs-4"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 class="mb-0 text-dark"> <?php echo htmlspecialchars($pending_count); ?> </h6>
                                    <span class="text-muted small"> Awaiting Approval </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
            <!-- End Pending Requests -->

            <!-- Redeemed Items -->
            <div class="col-12 mt-2">

                <a href="home.php?page=redemption-log">
                    <div class="card shadow">

                        <div class="d-flex justify-content-between align-items-center px-4 py-2 bg-secondary mb-2 mt-1">
                            <h5 class="card-title text-white custom-card-title fw-bold">
                                <i class="bi bi-clock-history fs-3"></i>
                                Recently Redeemed Items
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped table-hover datatable custom-table">
                                    <thead>
                                        <tr>
                                            <th scope="col"> Transaction ID </th>
                                            <th scope="col"> Item Image </th>
                                            <th scope="col"> Item Name </th>
                                            <th scope="col"> Quantity </th>
                                            <th scope="col"> Points Deducted </th>
                                            <th scope="col"> Requested By </th>
                                            <th scope="col"> Request Status </th>
                                            <th scope="col"> Redeemed At </th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $fetch_pending_requests = $conn->prepare("SELECT
                                                                                tt.*,
                                                                                CONCAT(sa.first_name, ' ', sa.last_name) AS 'redeemed_by',
                                                                                rt.item_image,
                                                                                rt.item_name
                                                                            FROM transactions_tbl tt
                                                                            LEFT JOIN reward_items_tbl rt
                                                                            ON tt.item_redeemed = rt.item_id
                                                                            LEFT JOIN student_accounts_tbl sa
                                                                            ON tt.redeemed_by = sa.student_lrn
                                                                            WHERE tt.redeemed_by = :student_lrn AND tt.redeem_status = :redeem_status
                                                                            ORDER BY tt.redeemed_at DESC LIMIT 10 
                                                                            ");
                                        $fetch_pending_requests->execute(["student_lrn" => $student_lrn, ":redeem_status" => "Approved"]);

                                        if ($fetch_pending_requests->rowCount() > 0) {
                                            while ($transaction_data = $fetch_pending_requests->fetch()) {
                                                $item_image = $transaction_data["item_image"];

                                                if (empty($item_image) || !file_exists($item_file_path . $item_image)) {
                                                    $item_image = "default-item.png";
                                                }
                                        ?>
                                                <tr>
                                                    <td class="fw-bold">
                                                        <?php echo htmlspecialchars($transaction_data["transaction_id"]); ?>
                                                    </td>

                                                    <td>
                                                        <img
                                                            src="<?php echo htmlspecialchars($item_file_path . $item_image); ?>"
                                                            alt="Item Image"
                                                            class="rounded-circle"
                                                            width="40" height="40">
                                                    </td>

                                                    <td class="fw-bold">
                                                        <?php echo htmlspecialchars($transaction_data["item_name"]); ?>
                                                    </td>

                                                    <td class="fw-bold">
                                                        <?php echo htmlspecialchars($transaction_data["item_quantity"]); ?>
                                                    </td>

                                                    <td class="fw-bold">
                                                        <?php echo htmlspecialchars($transaction_data["total_points"]); ?>
                                                    </td>

                                                    <td class="fw-bold">
                                                        <?php echo htmlspecialchars($transaction_data["redeemed_by"]); ?>
                                                    </td>

                                                    <td class="fw-bold">
                                                        <span class="badge bg-<?php echo htmlspecialchars($transaction_data["redeem_status"] === "Approved" ? "success" : "danger"); ?> p-2">
                                                            <?php echo htmlspecialchars($transaction_data["redeem_status"]); ?>
                                                        </span>
                                                    </td>

                                                    <td class="fw-bold">
                                                        <?php echo htmlspecialchars(format_timestamp($transaction_data["redeemed_at"])); ?>
                                                    </td>


                                                </tr>

                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">
                                                    No redemption data found.
                                                </td>
                                            </tr>

                                        <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </a>

            </div>

        </div>

    </section>

</main>

<!-- End Main -->
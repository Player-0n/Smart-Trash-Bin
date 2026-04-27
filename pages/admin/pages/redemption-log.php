<!-- Main -->
<main id="main" class="main">

    <!-- Pagetitle -->
    <?php
        include_once "includes/pagetitle.php";
    ?>
    <!-- End Pagetitle -->

    <!-- Table -->
    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card shadow">

                    <div class="d-flex justify-content-between align-items-center px-4 py-2 bg-secondary mb-2 mt-1">
                        <h5 class="card-title text-white custom-card-title fw-bold">
                            <i class="bi bi-check-lg fs-3"></i>
                            Approved Redemptions
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
                                        <th scope="col"> Total Points </th>
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
                                                                            WHERE tt.redeem_status = :redeem_status
                                                                            ORDER BY tt.redeemed_at DESC
                                                                            ");
                                    $fetch_pending_requests->execute([":redeem_status" => "Approved"]);

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
                                                No transaction data found.
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

            </div>

            <div class="col-lg-12">

                <div class="card shadow">

                    <div class="d-flex justify-content-between align-items-center px-4 py-2 bg-secondary mb-2 mt-1">
                        <h5 class="card-title text-white custom-card-title fw-bold">
                            <i class="bi bi-x-lg fs-3"></i>
                            Rejected Requests
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
                                        <th scope="col"> Points Returned </th>
                                        <th scope="col"> Requested By </th>
                                        <th scope="col"> Request Status </th>
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
                                                                            WHERE tt.redeem_status = :redeem_status
                                                                            ORDER BY tt.transaction_id DESC
                                                                            ");
                                    $fetch_pending_requests->execute([":redeem_status" => "Declined"]);

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
                                                
                                            </tr>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                No rejected requests found.
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

            </div>

        </div>

    </section>
    <!-- End Table -->

</main>
<!-- End #main -->

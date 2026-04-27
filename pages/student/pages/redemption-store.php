<!-- Main -->

<main class="main" id="main">

    <!-- Pagetitle -->
    <?php
    include_once "includes/pagetitle.php";
    ?>
    <!-- End Pagetitle -->

    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card shadow">

                    <div class="d-flex justify-content-between align-items-center px-4 py-2 bg-secondary mb-2 mt-1">
                        <h5 class="card-title text-white custom-card-title fw-bold">
                            <i class="bi bi-shop-window fs-3"></i>
                            Redemption Store
                        </h5>

                        <div class="text-end text-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <?php echo $student_points ?? '0'; ?> Points
                            </h5>
                            <small class="text-white"> My Points </small>
                        </div>

                    </div>

                    <div class="container-fluid my-4">

                        <div class="row g-4">

                            <?php
                            $fetch_items = $conn->prepare("SELECT
                                                                    rt.*,
                                                                    COALESCE(SUM(tt.item_quantity), 0) AS 'item_redeems'
                                                                FROM reward_items_tbl rt
                                                                LEFT JOIN transactions_tbl tt
                                                                ON rt.item_id = tt.item_redeemed AND tt.redeem_status = :redeem_status
                                                                WHERE rt.item_stocks > 0
                                                                GROUP BY rt.item_id
                                                                ORDER BY item_redeems DESC, rt.item_stocks DESC
                                                            ");
                            $fetch_items->execute([":redeem_status" => "Approved"]);
                            if ($fetch_items->rowCount() > 0) {
                                while ($item_data = $fetch_items->fetch()) {
                                    $item_image = $item_data["item_image"];

                                    if (empty($item_image) || !file_exists($item_file_path . $item_image)) {
                                        $item_image = "default-item.png";
                                    }
                            ?>

                                    <!-- Start Card -->
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card shadow-sm h-100 border-0 rounded-4 overflow-hidden">

                                            <!-- Item Image -->
                                            <div class="bg-light d-flex align-items-center justify-content-center mb-2" style="height: 160px; overflow: hidden;">
                                                <img src="<?php echo htmlspecialchars($item_file_path . $item_image); ?>" alt="Item Image" class="img-fluid object-fit-cover" style="max-height: 100%; width: auto;">
                                            </div>

                                            <!-- Card Body -->
                                            <div class="card-body d-flex flex-column justify-content-between">

                                                <!-- Item Title -->
                                                <h6 class="fw-bold text-dark text-truncate mb-1">
                                                    <?php echo htmlspecialchars($item_data["item_name"]); ?>
                                                </h6>

                                                <!-- Description -->
                                                <p class="text-muted small mb-3" style="min-height: 40px;">
                                                    <?php echo htmlspecialchars($item_data["item_description"]); ?>
                                                </p>

                                                <!-- Item Info -->
                                                <ul class="list-unstyled small mb-3">
                                                    <li>
                                                        <i class="bi bi-star-fill text-warning me-1"></i>
                                                        <strong><?php echo htmlspecialchars($item_data["item_points"]); ?></strong> Points
                                                    </li>

                                                    <li>
                                                        <i class="bi bi-box-seam text-primary me-1"></i>
                                                        <strong><?php echo htmlspecialchars($item_data["item_stocks"]); ?></strong> In Stock
                                                    </li>

                                                    <li>
                                                        <i class="bi bi-repeat text-success me-1"></i>
                                                        <strong><?php echo htmlspecialchars($item_data["item_redeems"]); ?></strong> Total Redemptions
                                                    </li>
                                                </ul>

                                                <!-- Redeem Button -->
                                                <div class="mt-auto text-end">
                                                    <button class="btn btn-sm custom-add-btn w-100 rounded-pill text-white" data-bs-toggle="modal" data-bs-target="#redeem-item-<?php echo htmlspecialchars($item_data["item_id"]); ?>">
                                                        <i class="bi bi-cart-check me-1"></i> Redeem Item
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Card -->

                                    <!-- Redeem Modal -->
                                    <div class="modal fade" id="redeem-item-<?php echo htmlspecialchars($item_data["item_id"]); ?>" tabindex="-1" aria-labelledby="redeemItemLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md modal-dialog-centered">
                                            <div class="modal-content rounded-4 shadow-sm">

                                                <!-- Header -->
                                                <div class="modal-header bg-success text-white rounded-top-4">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="bi bi-bag-check-fill me-2"></i>Redeem: <?php echo htmlspecialchars($item_data["item_name"]); ?>
                                                    </h5>
                                                    <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <!-- Form Start -->
                                                <form action="../../process/student/redeem-item.php" method="POST">

                                                    <input type="hidden" name="item-id" value="<?php echo htmlspecialchars(base64_encode($item_data["item_id"])); ?>">

                                                    <!-- Body -->
                                                    <div class="modal-body">

                                                        <!-- Image -->
                                                        <div class="mb-3 text-center">
                                                            <img src="<?php echo htmlspecialchars($item_file_path . $item_image); ?>" class="img-fluid rounded border shadow-sm" style="max-height: 180px; object-fit: cover;" alt="Item Image">
                                                        </div>

                                                        <!-- Info Summary -->
                                                        <ul class="list-group list-group-flush mb-3 small">

                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <strong> Points Required:</strong> <span><?php echo htmlspecialchars($item_data["item_points"]); ?></span>
                                                            </li>

                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <strong> Available Stocks:</strong> <span><?php echo htmlspecialchars($item_data["item_stocks"]); ?></span>
                                                            </li>

                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <strong> Already Redeemed:</strong> <span><?php echo htmlspecialchars($item_data["item_redeems"]); ?></span>
                                                            </li>

                                                        </ul>

                                                        <!-- Quantity Input -->
                                                        <div class="mb-2">

                                                            <label for="quantity" class="form-label fw-semibold"> Quantity to Redeem: </label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                                                <input
                                                                    type="number"
                                                                    class="form-control"
                                                                    id="quantity"
                                                                    name="quantity"
                                                                    min="1"
                                                                    max="<?php echo htmlspecialchars($item_data["item_stocks"]); ?>"
                                                                    required
                                                                    placeholder="Enter Quantity(Max: <?php echo htmlspecialchars($item_data["item_stocks"]); ?>)">
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Footer -->
                                                    <div class="modal-footer bg-light rounded-bottom-4">
                                                        <button type="submit" name="redeem-item" class="btn btn-primary custom-add-btn btn-sm w-100">
                                                            <i class="bi bi-stars me-1"></i> Confirm Redeem
                                                        </button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Redeem -->

                                <?php
                                }
                            } else {
                                ?>
                                <div class="col-12 col-sm-12 col-md-12">

                                    <div class="d-flex flex-column align-items-center">
                                        <h3> No Items Found. </h3>
                                    </div>

                                </div>
                            <?php
                            }
                            ?>

                        </div>

                    </div>

                </div>

            </div>
            
        </div>

    </section>

</main>

<!-- End Main -->
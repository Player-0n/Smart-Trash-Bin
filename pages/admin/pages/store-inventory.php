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
                            <i class="bi bi-box-seam fs-3"></i>
                            Redeem Store Inventory
                        </h5>

                        <a href="#" class="btn btn-primary custom-add-btn" data-bs-toggle="modal" data-bs-target="#addItemModal">
                            Add New Item
                        </a>
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
                                                                GROUP BY rt.item_id
                                                                ORDER BY rt.item_stocks DESC
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
                                        <div class="card shadow-sm h-100 border-0 rounded-4">

                                            <!-- Item Image -->
                                            <div class="bg-light d-flex align-items-center justify-content-center mb-2" style="height: 160px; overflow: hidden;">
                                                <img src="<?php echo htmlspecialchars($item_file_path . $item_image); ?>" alt="Item Image" class="img-fluid object-fit-cover" style="max-height: 100%; width: auto;">
                                            </div>

                                            <!-- Card Body -->
                                            <div class="card-body d-flex flex-column justify-content-between">

                                                <!-- Item Name & Description -->
                                                <div>
                                                    <h6 class="fw-bold text-truncate mb-1">
                                                        <?php echo htmlspecialchars($item_data["item_name"]); ?>
                                                    </h6>
                                                    <p class="text-muted small fst-italic mb-3" style="height: 40px; overflow: hidden;">
                                                        <?php echo htmlspecialchars($item_data["item_description"]); ?>
                                                    </p>

                                                    <!-- Points & Stocks -->
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-primary small">
                                                            <i class="bi bi-star-fill me-1"></i>
                                                            <strong><?php echo htmlspecialchars($item_data["item_points"]); ?></strong> pts
                                                        </span>
                                                        <span class="text-secondary small">
                                                            <i class="bi bi-box-seam me-1"></i>
                                                            Stock: <strong><?php echo htmlspecialchars($item_data["item_stocks"]); ?></strong>
                                                        </span>
                                                    </div>

                                                    <!-- Timestamps -->
                                                    <div class="text-muted small fst-italic mt-3" style="font-size: 10px;">
                                                        <div><i class="bi bi-clock me-1"></i>Added: <strong><?php echo htmlspecialchars(format_timestamp($item_data["added_at"])); ?></strong></div>
                                                        <div><i class="bi bi-arrow-clockwise me-1"></i>Updated: <strong><?php echo htmlspecialchars(format_timestamp($item_data["updated_at"])); ?></strong></div>
                                                    </div>

                                                </div>

                                                <hr class="my-3">

                                                <!-- Footer Actions -->
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <!-- Redeem Count -->
                                                    <span class="text-muted small">
                                                        <i class="bi bi-check2-circle me-1"></i>Redemptions:
                                                        <strong><?php echo htmlspecialchars($item_data["item_redeems"]); ?></strong>
                                                    </span>

                                                    <!-- Action Buttons -->
                                                    <div class="d-flex gap-2">
                                                        <!-- Delete Button -->
                                                        <form action="../../process/admin/item-management.php" method="POST">
                                                            <input type="hidden" name="item-id" value="<?php echo htmlspecialchars(base64_encode($item_data["item_id"])); ?>">
                                                            <input type="hidden" name="delete-item" value="1">
                                                            <button
                                                                type="submit"
                                                                class="btn btn-outline-danger btn-sm"
                                                                title="Delete"
                                                                onclick="return confirmDelete(event, this.form, '<?php echo htmlspecialchars($item_data['item_name']); ?>')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Update Button -->
                                                        <a href="#"
                                                            title="Update"
                                                            class="btn btn-outline-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#update-item-<?php echo htmlspecialchars($item_data["item_id"]); ?>">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- End Card -->

                                    <!-- Update Modal -->
                                    <div class="modal fade" id="update-item-<?php echo htmlspecialchars($item_data["item_id"]); ?>" tabindex="-1" aria-labelledby="updateItemLabel">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content rounded-4 shadow">

                                                <!-- Modal Header -->
                                                <div class="modal-header bg-primary text-white rounded-top-4">
                                                    <h5 class="modal-title fw-bold" id="updateItemLabel">
                                                        <i class="bi bi-pencil-square me-2"></i>Update Item: <?php echo htmlspecialchars($item_data["item_name"]); ?>
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <!-- Form Start -->
                                                <form action="../../process/admin/item-management.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="item-id" value="<?php echo htmlspecialchars(base64_encode($item_data["item_id"])); ?>">

                                                    <div class="modal-body">
                                                        <div class="container-fluid">

                                                            <!-- Image -->
                                                            <div class="mb-3 text-center">
                                                                <img src="<?php echo htmlspecialchars($item_file_path . $item_image); ?>" class="img-fluid rounded border shadow-sm" style="max-height: 180px; object-fit: cover;" alt="Item Image">
                                                            </div>

                                                            <!-- Product Name & Description -->
                                                            <div class="row g-3 mb-4">
                                                                <div class="col-md-6">
                                                                    <label for="productName" class="form-label fw-semibold">Product Name:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                                                        <input type="text" class="form-control" id="productName" name="item-name"
                                                                            placeholder="Enter product name"
                                                                            value="<?php echo htmlspecialchars($item_data["item_name"]); ?>" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="productDesc" class="form-label fw-semibold">Description <small>(Optional)</small>:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                                                        <textarea class="form-control" id="productDesc" name="item-description" rows="1"
                                                                            placeholder="Enter short description"><?php echo htmlspecialchars($item_data["item_description"]); ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Points and Stocks -->
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label for="points" class="form-label fw-semibold">Points Required:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="bi bi-star-fill"></i></span>
                                                                        <input type="number" class="form-control" id="points" name="points-required"
                                                                            placeholder="e.g. 100"
                                                                            value="<?php echo htmlspecialchars($item_data["item_points"]); ?>" required min="1">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="stocks" class="form-label fw-semibold">Stocks Available:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="bi bi-stack"></i></span>
                                                                        <input type="number" class="form-control" id="stocks" name="item-stocks"
                                                                            placeholder="e.g. 20"
                                                                            value="<?php echo htmlspecialchars($item_data["item_stocks"]); ?>" required min="1">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer d-flex justify-content-between">
                                                        <button type="submit" class="btn btn-success" name="update-item">
                                                            <i class="bi bi-arrow-repeat me-1"></i>Update Item
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </form>
                                                <!-- Form End -->

                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->

                                <?php
                                }
                            } else {
                                ?>
                                <div class="col-12 col-sm-12 col-md-12">

                                    <div class="d-flex flex-column align-items-center">
                                        <h3> No Items Found. </h3>

                                        <a href="#" class="btn btn-primary custom-add-btn" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                            Add New Item
                                        </a>
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

<!-- Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addAdminLabel">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold" id="addItemLabel"> Add New Item </h5>
                <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form action="../../process/admin/item-management.php" method="POST" enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="container-fluid">

                        <!-- Image Preview -->
                        <div class="mb-3 text-center">
                            <img id="item-preview" src="../../uploads/items/default-item.png" alt="Image Preview"
                                class="img-fluid rounded border" style="max-height: 180px; object-fit: cover;">
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="upload-pic" class="form-label"> Upload Product Image(Optional): </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input class="form-control" type="file" id="upload-item-pic" name="item-image" accept="image/*">
                            </div>
                        </div>

                        <!-- Name & Description -->
                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="productName" class="form-label"> Product Name: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                    <input type="text" class="form-control" id="productName" name="item-name" placeholder="Enter product name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="productDesc" class="form-label"> Description(Optional): </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <textarea class="form-control fst-italic small" id="productDesc" name="item-description" rows="1" placeholder="Enter a short product description"></textarea>
                                </div>
                            </div>

                        </div>

                        <!-- Points & Stocks -->
                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="points" class="form-label"> Points Required: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-star-fill"></i></span>
                                    <input type="number" class="form-control" id="points" name="points-required" placeholder="Enter points required" required min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="stocks" class="form-label"> Stocks: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-stack"></i></span>
                                    <input type="number" class="form-control" id="stocks" name="item-stocks" placeholder="Enter stock quantity" required min="0">
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success custom-add-btn" name="add-new-item">
                        <i class="bi bi-plus-circle me-1"></i> Add Item
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
                </div>

            </form>
            <!-- Form End -->

        </div>

    </div>

</div>
<!-- End Modal -->


<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('upload-item-pic').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('item-preview');
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        });
    })
</script>

<!-- Confirm Delete -->
<script>
    function confirmDelete(event, formElement, item) {
        event.preventDefault();

        Swal.fire({
            title: `Delete ${item}?`,
            text: "Are you sure you want to delete this item?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                formElement.submit();
            }
        });

        return false;
    }
</script>
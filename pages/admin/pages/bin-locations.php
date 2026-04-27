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
                            <i class="bi bi-geo-alt fs-3"></i>
                            Bin Location Management
                        </h5>

                        <a href="#" class="btn btn-primary custom-add-btn" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                            Add Location
                        </a>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Target Location </th>
                                        <th scope="col"> No. of Bins </th>
                                        <th scope="col"> Added At </th>
                                        <th scope="col"> Updated At </th>
                                        <th scope="col"> Actions </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_locations = $conn->prepare("SELECT
                                                                                lt.*,
                                                                                COALESCE(COUNT(bt.bin_id), 0) AS 'bin_count'
                                                                            FROM bin_locations_tbl lt
                                                                            LEFT JOIN bins_tbl bt
                                                                            ON lt.location_id = bt.bin_location
                                                                            GROUP BY lt.location_id
                                                                            ORDER BY bin_count DESC
                                                                            ");
                                    $fetch_locations->execute();

                                    if ($fetch_locations->rowCount() > 0) {
                                        while ($location_data = $fetch_locations->fetch()) {
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($location_data["target_location"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($location_data["bin_count"]); ?>
                                                </td>  

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($location_data["created_at"])); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($location_data["updated_at"])); ?>
                                                </td>

                                                <td class="d-flex gap-2 justify-content-center">

                                                    <button class="btn btn-primary custom-add-btn btn-sm" title="Edit Location" data-bs-toggle="modal" data-bs-target="#update-location-<?php echo htmlspecialchars($location_data["location_id"]); ?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="../../process/admin/location-management.php" method="POST">

                                                        <input type="hidden" name="location-id" value="<?php echo htmlspecialchars(base64_encode($location_data["location_id"])); ?>">
                                                        <input type="hidden" name="delete-location" value="1">

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete Location"
                                                            onclick="return confirmAction(event, this.form, 'Delete Location?', 'warning', 'Are you sure you want to delete this location: <?php echo htmlspecialchars($location_data['target_location']); ?>?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>

                                                    </form>

                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="update-location-<?php echo htmlspecialchars($location_data["location_id"]); ?>" tabindex="-1" aria-labelledby="updateLocationLabel">

                                                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-secondary text-white">
                                                            <h5 class="modal-title fw-bold" id="updateLocationLabel"> Update Location: <?php echo htmlspecialchars($location_data["target_location"]); ?> </h5>
                                                            <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <!-- Form Start -->
                                                        <form action="../../process/admin/location-management.php" method="POST">

                                                            <input type="hidden" name="location-id" value="<?php echo htmlspecialchars(base64_encode($location_data["location_id"])); ?>">

                                                            <div class="modal-body">

                                                                <div class="container-fluid">

                                                                    <!-- Name & Description -->
                                                                    <div class="row">

                                                                        <div class="col-md-12">
                                                                            <label for="location" class="form-label"> Target Location: </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                                                <input type="text" class="form-control" id="location" name="target-location" placeholder="Enter target location" value="<?php echo htmlspecialchars($location_data["target_location"]); ?>" required>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <!-- Footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success custom-add-btn" name="update-location">
                                                                    <i class="bi bi-pencil-square me-1"></i> Update Location
                                                                </button>

                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
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
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                No location found. <br>

                                                <a class="btn btn-primary custom-add-btn btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                                                    Add Location
                                                </a>
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

<!-- Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationLabel">

    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold" id="addLocationLabel"> Add New Location </h5>
                <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form action="../../process/admin/location-management.php" method="POST">

                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-md-12">
                                <label for="location" class="form-label"> Target Location: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" id="location" name="target-location" placeholder="Enter target location" required>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success custom-add-btn" name="add-new-location">
                        <i class="bi bi-plus-circle me-1"></i> Add Location
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
                </div>

            </form>
            <!-- Form End -->

        </div>

    </div>

</div>
<!-- End Modal -->

<!-- Confirm Delete -->
<script>
    function confirmAction(event, formElement, title, icon, message) {
        event.preventDefault();

        Swal.fire({
            title: title,
            text: message,
            icon: icon,
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
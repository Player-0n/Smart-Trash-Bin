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
                            <i class="bi bi-tools fs-3"></i>
                            Maintenance and Alerts
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Maintenance Title </th>
                                        <th scope="col"> Maintenance Bin </th>
                                        <th scope="col"> Maintenance Description </th>
                                        <th scope="col"> Assigned Personnel </th>
                                        <th scope="col"> Maintenance Status </th>
                                        <th scope="col"> Reported At </th>
                                        <th scope="col"> Update </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_maintenance_list = $conn->prepare("SELECT
                                                                                mt.*,
                                                                                bt.bin_name                                                                            FROM maintenance_tbl mt
                                                                            LEFT JOIN bins_tbl bt
                                                                            ON mt.maintenance_bin = bt.bin_id
                                                                            WHERE mt.maintenance_status != :maintenance_status AND mt.assigned_personnel = :personnel_id
                                                                            ORDER BY
                                                                            FIELD(mt.maintenance_status, 'In Progress', 'Pending'),
                                                                            mt.reported_at DESC
                                                                            ");
                                    $fetch_maintenance_list->execute([":maintenance_status" => "Completed", ":personnel_id" => $personnel_id]);

                                    if ($fetch_maintenance_list->rowCount() > 0) {
                                        while ($maintenance_data = $fetch_maintenance_list->fetch()) {
                                    ?>
                                            <tr>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($maintenance_data["maintenance_title"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($maintenance_data["bin_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($maintenance_data["maintenance_description"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    You
                                                </td>

                                                <td class="fw-bold">
                                                    <span class="badge bg-<?php echo htmlspecialchars($maintenance_data["maintenance_status"] === "Pending" ? "warning" : "primary"); ?> px-2 py-1">
                                                        <?php echo htmlspecialchars($maintenance_data["maintenance_status"]); ?>
                                                    </span>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($maintenance_data["reported_at"])); ?>
                                                </td>

                                                <td class="d-flex gap-2 justify-content-center">

                                                    <a href="#" class="btn btn-primary custom-add-btn btn-sm" data-bs-toggle="modal" data-bs-target="#update-status-<?php echo htmlspecialchars($maintenance_data["maintenance_id"]); ?>">
                                                        Update
                                                    </a>

                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="update-status-<?php echo htmlspecialchars($maintenance_data["maintenance_id"]); ?>" tabindex="-1" aria-labelledby="updateMaintenanceModal">

                                                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-secondary text-white">
                                                            <h5 class="modal-title fw-bold" id="updateMaintenanceModal"> Update Maintenance: <?php echo htmlspecialchars($maintenance_data["bin_name"]); ?> </h5>
                                                            <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <!-- Form Start -->
                                                        <form action="../../process/personnel/bin-management.php" method="POST">

                                                            <input type="hidden" name="maintenance-id" value="<?php echo htmlspecialchars(base64_encode($maintenance_data["maintenance_id"])); ?>">

                                                            <div class="modal-body">

                                                                <div class="container-fluid">

                                                                    <div class="row mb-3">

                                                                        <div class="col-md-12">

                                                                            <label for="maintenance-status" class="form-label"> Maintenance Status: </label>

                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-tools"></i></span>
                                                                                <select class="form-select" id="maintenance-status" name="maintenance-status" required>
                                                                                    <option disabled selected> Select Maintenance Status </option> 
                                                                                    <option value="In Progress" <?php echo htmlspecialchars($maintenance_data["maintenance_status"] === "In Progress" ? "selected" : ""); ?>> In Progress </option>
                                                                                    <option value="Completed"> Completed </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <label for="maintenance-description" class="form-label"> Maintenance Description: </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-pencil-square"></i></span>
                                                                                <textarea class="form-control" id="maintenance-description" name="maintenance-description" placeholder="Enter maintenance details..." rows="4" required><?php echo htmlspecialchars($maintenance_data["maintenance_description"]); ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>        

                                                                </div>

                                                            </div>

                                                            <!-- Footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success custom-add-btn" name="update-maintenance-status">
                                                                    <i class="bi bi-tools me-1"></i> Update Status
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
                                            <td colspan="7" class="text-center text-muted">
                                                No maintenance data found. <br> 
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
                            <i class="bi bi-check-lg fs-3"></i>
                            Completed Maintenance
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Maintenance Title </th>
                                        <th scope="col"> Maintenance Bin </th>
                                        <th scope="col"> Maintenance Description </th>
                                        <th scope="col"> Assigned Personnel </th>
                                        <th scope="col"> Maintenance Status </th>
                                        <th scope="col"> Reported At </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_maintenance_list = $conn->prepare("SELECT
                                                                                mt.*,
                                                                                bt.bin_name                                                                            FROM maintenance_tbl mt
                                                                            LEFT JOIN bins_tbl bt
                                                                            ON mt.maintenance_bin = bt.bin_id
                                                                            WHERE mt.maintenance_status = :maintenance_status AND mt.assigned_personnel = :personnel_id
                                                                            ORDER BY
                                                                            FIELD(mt.maintenance_status, 'In Progress', 'Pending'),
                                                                            mt.reported_at DESC
                                                                            ");
                                    $fetch_maintenance_list->execute([":maintenance_status" => "Completed", ":personnel_id" => $personnel_id]);

                                    if ($fetch_maintenance_list->rowCount() > 0) {
                                        while ($maintenance_data = $fetch_maintenance_list->fetch()) {
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($maintenance_data["maintenance_title"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($maintenance_data["bin_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($maintenance_data["maintenance_description"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    You
                                                </td>

                                                <td class="fw-bold">
                                                    <span class="badge bg-success px-2 py-1">
                                                        <?php echo htmlspecialchars($maintenance_data["maintenance_status"]); ?>
                                                    </span>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($maintenance_data["reported_at"])); ?>
                                                </td>
                                                
                                            </tr>
      
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                No completed maintenance. <br> 
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

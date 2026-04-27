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

                        <a href="#" class="btn btn-primary custom-add-btn" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                            Add Maintenance
                        </a>

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
                                        <th scope="col"> Actions </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_maintenance_list = $conn->prepare("SELECT
                                                                                mt.*,
                                                                                bt.bin_name,
                                                                                COALESCE(CONCAT(pt.first_name, ' ', pt.last_name), 'No Assigned Personnel') AS 'personnel'
                                                                            FROM maintenance_tbl mt
                                                                            LEFT JOIN bins_tbl bt
                                                                            ON mt.maintenance_bin = bt.bin_id
                                                                            LEFT JOIN personnel_accounts_tbl pt
                                                                            ON mt.assigned_personnel = pt.personnel_id
                                                                            WHERE mt.maintenance_status != :maintenance_status
                                                                            ORDER BY
                                                                            FIELD(mt.maintenance_status, 'In Prgoress', 'Pending'),
                                                                            mt.reported_at DESC
                                                                            ");
                                    $fetch_maintenance_list->execute([":maintenance_status" => "Completed"]);

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
                                                    <?php echo htmlspecialchars($maintenance_data["personnel"]); ?>
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

                                                    <form action="../../process/admin/bin-management.php" method="POST">

                                                        <input type="hidden" name="maintenance-id" value="<?php echo htmlspecialchars(base64_encode($maintenance_data["maintenance_id"])); ?>">
                                                        <input type="hidden" name="delete-maintenance" value="1">

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete Maintenance"
                                                            onclick="return confirmAction(event, this.form, 'Delete Maintenance?', 'warning', 'Are you sure you want to delete this maintenance: <?php echo htmlspecialchars($maintenance_data['maintenance_title']); ?>?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>

                                                    </form>

                                                </td>
                                            </tr>
      
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                No maintenance data found. <br>

                                                <a class="btn btn-primary custom-add-btn btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                                                    Add new Maintenance
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
                                                                                bt.bin_name,
                                                                                COALESCE(CONCAT(pt.first_name, ' ', pt.last_name), 'No Assigned Personnel') AS 'personnel'
                                                                            FROM maintenance_tbl mt
                                                                            LEFT JOIN bins_tbl bt
                                                                            ON mt.maintenance_bin = bt.bin_id
                                                                            LEFT JOIN personnel_accounts_tbl pt
                                                                            ON mt.assigned_personnel = pt.personnel_id
                                                                            WHERE mt.maintenance_status = :maintenance_status
                                                                            ORDER BY mt.reported_at DESC
                                                                            ");
                                    $fetch_maintenance_list->execute([":maintenance_status" => "Completed"]);

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
                                                    <?php echo htmlspecialchars($maintenance_data["personnel"]); ?>
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
                                                No maintenance data found. <br>

                                                <a class="btn btn-primary custom-add-btn btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                                                    Add new Maintenance
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
<div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-labelledby="addMaintenanceLabel">

    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold" id="addMaintenanceLabel"> Add Maintenance </h5>
                <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form action="../../process/admin/bin-management.php" method="POST">

                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row mb-3">

                            <div class="col-md-12">
                                <label for="title" class="form-label"> Maintenance Title: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-pen"></i></span>
                                    <input type="text" class="form-control" id="title" name="maintenance-title" placeholder="Enter maintenance title" required>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="maintenance-bin" class="form-label"> Maintenance Bin: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-trash"></i></span>
                                    <select class="form-select" id="maintenance-bin" name="bin-id" required>
                                        <option selected disabled value=""> Choose Bin </option>

                                        <?php
                                        $fetch_bins = $conn->prepare("SELECT * FROM bins_tbl ORDER BY updated_at DESC");
                                        $fetch_bins->execute();

                                        while ($bin_data = $fetch_bins->fetch()) {
                                        ?>
                                            <option value="<?php echo htmlspecialchars($bin_data["bin_id"]); ?>">
                                                <?php echo htmlspecialchars($bin_data["bin_name"]); ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="assigned-personnel" class="form-label"> Assigned Personnel: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-gear"></i></span>
                                    <select class="form-select" id="assigned-personnel" name="personnel-id" required>
                                        <option selected disabled value=""> Choose Personnel </option>

                                        <?php
                                        $fetch_personnel = $conn->prepare("SELECT * FROM personnel_accounts_tbl ORDER BY last_name");
                                        $fetch_personnel->execute();

                                        while ($personnel_data = $fetch_personnel->fetch()) {
                                        ?>
                                            <option value="<?php echo htmlspecialchars($personnel_data["personnel_id"]); ?>">
                                                <?php echo htmlspecialchars($personnel_data["first_name"] . " " . $personnel_data["last_name"]); ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success custom-add-btn" name="add-new-maintenance">
                        <i class="bi bi-plus-circle me-1"></i> Add Maintenance
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
                </div>

            </form>
            <!-- Form End -->

        </div>

    </div>

</div>
<!-- End Modal -->

<!-- Confirm Action -->
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
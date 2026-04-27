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
                            <i class="bi bi-recycle fs-3"></i>
                            Trash Disposal Logs
                        </h5>

                        <div class="text-end text-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <?php echo $student_points ?? '0'; ?> Points
                            </h5>
                            <small class="text-white"> My Points </small>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Bin Used </th>
                                        <th scope="col"> Bin Location </th>
                                        <th scope="col"> Disposed Item Type </th>
                                        <th scope="col"> Disposed By </th>
                                        <th scope="col"> Points Gained </th>
                                        <th scope="col"> Disposed At </th>  
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_disposal_logs = $conn->prepare("SELECT
                                                                            dt.*,
                                                                            bt.bin_name,
                                                                            COALESCE(lt.target_location, 'No Assigned Location') AS 'bin_loc',
                                                                            COALESCE(CONCAT(sa.first_name, ' ', sa.last_name), 'Student Not Found') AS 'student_name'
                                                                        FROM student_disposal_log_tbl dt
                                                                        LEFT JOIN bins_tbl bt
                                                                        ON dt.bin_used = bt.bin_id
                                                                        LEFT JOIN bin_locations_tbl lt
                                                                        ON bt.bin_location = lt.location_id
                                                                        LEFT JOIN student_accounts_tbl sa
                                                                        ON dt.disposed_by = sa.student_lrn
                                                                        WHERE dt.disposed_by = :student_lrn
                                                                        ORDER BY dt.disposed_at DESC
                                                                        ");

                                    $fetch_disposal_logs->execute([":student_lrn" => $student_lrn]);

                                    if ($fetch_disposal_logs->rowCount() > 0) {
                                        while ($disposal_data = $fetch_disposal_logs->fetch()) {
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["bin_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["bin_loc"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["disposed_item_type"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["student_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["points_gained"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($disposal_data["disposed_at"])); ?>
                                                </td>
                                            </tr>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                No disposal log found.
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

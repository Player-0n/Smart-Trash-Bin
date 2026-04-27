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
                            <i class="bi bi-journal-text fs-3"></i>
                            Disposal Logs
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Bin Disposed </th>
                                        <th scope="col"> Disposed By </th>
                                        <th scope="col"> Disposed Item Type </th>
                                        <th scope="col"> Weight </th>
                                        <th scope="col"> Disposed At </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_disposal_logs = $conn->prepare("
                                                                                SELECT
                                        	dt.*,
                                            bt.bin_name,
                                            CONCAT(p.first_name, ' ', p.last_name) AS 'disposed_by_name'
                                        FROM disposal_logs_tbl dt
                                        LEFT JOIN bins_tbl bt
                                        ON dt.bin_disposed = bt.bin_id
                                        LEFT JOIN personnel_accounts_tbl p
                                        ON dt.disposed_by = p.personnel_id
                                        ORDER BY dt.disposed_at DESC;
                                                                            ");
                                    $fetch_disposal_logs->execute();

                                    if ($fetch_disposal_logs->rowCount() > 0) {
                                        while ($disposal_data = $fetch_disposal_logs->fetch()) {
                                            $personnel = "";

                                            if($disposal_data["disposed_by"] == (int)$personnel_id) {
                                                $personnel = "You";
                                            }

                                            else {
                                                $personnel = $disposal_data["dispsed_by_name"];
                                            }
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["bin_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($personnel); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["disposed_item_type"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($disposal_data["weight"] . "kg."); ?>
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
                                            <td colspan="6" class="text-center text-muted">
                                                No disposal logs data.
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
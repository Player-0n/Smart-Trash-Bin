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
                            <i class="bi bi-trash fs-3"></i>
                            List of Trash Bins
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Unique Bin ID </th>
                                        <th scope="col"> Bin Name </th>
                                        <th scope="col"> Bin Location </th>
                                        <th scope="col"> Plastic Fill Level </th>
                                        <th scope="col"> Paper Fill Level </th>
                                        <th scope="col"> Bin Status </th>
                                        <th scope="col"> Last Emptied </th>
                                        <th scope="col"> Actions </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_bins = $conn->prepare("SELECT
                                                                                bt.*,
                                                                                COALESCE(lt.target_location, 'No Location Assigned') AS 'bin_loc'
                                                                            FROM bins_tbl bt
                                                                            LEFT JOIN bin_locations_tbl lt
                                                                            ON bt.bin_location = lt.location_id
                                                                            ORDER BY
                                                                            FIELD(bt.bin_status, 'Active', 'Inactive')
                                                                            ");
                                    $fetch_bins->execute();

                                    if ($fetch_bins->rowCount() > 0) {
                                        while ($bin_data = $fetch_bins->fetch()) {

                                            $fill_types = [
                                                'Plastic' => $bin_data["plastic_fill_level"],
                                                'Metal' => $bin_data["metal_fill_level"]
                                            ];

                                                   
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($bin_data["unique_bin_id"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($bin_data["bin_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($bin_data["bin_loc"]); ?>
                                                </td>

                                                <?php foreach ($fill_types as $type => $fill): 
                                                    $color = '';
                                                    $label = '';

                                                    if ($fill <= 25) {
                                                        $color = 'bg-success';
                                                    } elseif ($fill <= 75) {
                                                        $color = 'bg-warning';
                                                    } else {
                                                        $color = 'bg-danger';
                                                    }
                                                ?>

                                                    <td class="fw-bold">
                                                        <span class="badge <?php echo htmlspecialchars($color); ?>">
                                                            <?php echo htmlspecialchars($fill . "%"); ?>
                                                        </span>
                                                    </td>
                                                
                                                <?php
                                                    endforeach;
                                                ?>

                                                <td class="fw-bold">
                                                    <span class="badge bg-<?php echo htmlspecialchars($bin_data["bin_status"] === "Active" ? "success" : ""); ?> px-2 py-1">
                                                        <?php echo htmlspecialchars($bin_data["bin_status"]); ?>
                                                    </span>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($bin_data["last_emptied"]) ?? "N/A"); ?>
                                                </td>

                                                <td class="d-flex gap-2 justify-content-center">
                                                    <?php
                                                        if($bin_data["plastic_fill_level"] > 0 || $bin_data["metal_fill_level"]) {
                                                    ?>
                                                        <button class="btn btn-primary custom-add-btn btn-sm" title="Empty Bin" data-bs-toggle="modal" data-bs-target="#empty-bin-<?php echo htmlspecialchars($bin_data["bin_id"]); ?>">
                                                            Empty Bin
                                                        </button>
                                                    <?php
                                                        } else {
                                                    ?>
                                                        <span class="fw-bold text-success">
                                                            Empty Bin
                                                        </span>
                                                    <?php
                                                        }
                                                    ?>

                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="empty-bin-<?php echo htmlspecialchars($bin_data["bin_id"]); ?>" tabindex="-1" aria-labelledby="updateBinModal">

                                                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-secondary text-white">
                                                            <h5 class="modal-title fw-bold" id="updateBinModal"> Empty Bin: <?php echo htmlspecialchars($bin_data["bin_name"]); ?> </h5>
                                                            <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <!-- Form Start -->
                                                        <form action="../../process/personnel/bin-management.php" method="POST">

                                                            <input type="hidden" name="bin-id" value="<?php echo htmlspecialchars(base64_encode($bin_data["bin_id"])); ?>">

                                                            <div class="modal-body">

                                                                <div class="container-fluid">

                                                                    <div class="row mb-3">

                                                                        <div class="col-md-12">

                                                                            <label for="item-type" class="form-label"> Trash Item Type: </label>

                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-recycle"></i></span>
                                                                                <select class="form-select" id="item-type" name="trash-item-type" required>
                                                                                    <option selected disabled> Select Trash Item Type </option> 
                                                                                    <?php
                                                                                        if($bin_data["plastic_fill_level"] > 0):
                                                                                    ?>
                                                                                            <option value="Plastic"> Plastic </option>
                                                                                    <?php
                                                                                        endif;
                                                                                    ?>

                                                                                    <?php
                                                                                        if($bin_data["metal_fill_level"] > 0):
                                                                                    ?>
                                                                                        <option value="Metal"> Paper </option>
                                                                                    <?php
                                                                                        endif;
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <label for="weight" class="form-label"> Trash Weight(kg): </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                                                                <input type="number" class="form-control" id="weight" name="trash-weight" placeholder="Enter trash weight" min="0" step="any" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>         

                                                                </div>

                                                            </div>

                                                            <!-- Footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success custom-add-btn" name="empty-bin">
                                                                    <i class="bi bi-recycle me-1"></i> Emtpy Bin
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
                                                No bin found. <br>                                           
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

            <!-- Trash Bin Status Table -->
            <div class="col-12 mt-2">

                <a href="home.php?page=bin-monitoring">
                    <div class="card shadow">

                        <div class="d-flex justify-content-between align-items-center px-4 py-2 bg-secondary mb-2 mt-1">
                            <h5 class="card-title text-white custom-card-title fw-bold">
                                <i class="bi bi-trash fs-3"></i>
                                Trash Bin Status
                            </h5>
                            
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <?php
                                $fetch_bin_fill_data = $conn->prepare("SELECT
                                                                                    bt.*,
                                                                                    COALESCE(lt.target_location, 'No Location Assigned') AS bin_location
                                                                                FROM bins_tbl bt
                                                                                LEFT JOIN bin_locations_tbl lt
                                                                                ON bt.bin_location = lt.location_id
                                                                                ");
                                $fetch_bin_fill_data->execute();

                                if($fetch_bin_fill_data->rowCount() > 0) {

                                    while($fill_data = $fetch_bin_fill_data->fetch()) {

                                        $fill_types = [
                                            'Plastic' => $fill_data["plastic_fill_level"],
                                            'Paper' => $fill_data["metal_fill_level"]
                                        ];

                                        
                                ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                                        <div class="border rounded p-3 shadow-sm bg-white h-100 d-flex flex-column justify-content-center align-items-center text-center">
                                            <h6 class="fw-bold"><?php echo htmlspecialchars($fill_data["bin_name"]); ?></h6>
                                            <p class="text-muted small">
                                                <i class="bi bi-geo-alt"></i>
                                                <?php echo htmlspecialchars($fill_data["bin_location"]); ?>
                                            </p>

                                            <div class="d-flex d-flex justify-content-center align-items-center gap-2 w-100">
                                                <?php foreach ($fill_types as $type => $fill): 
                                                    $color = '';
                                                    $label = '';

                                                    if ($fill <= 25) {
                                                        $color = 'bg-success';
                                                        $label = 'Low';
                                                    } elseif ($fill <= 75) {
                                                        $color = 'bg-warning';
                                                        $label = 'High';
                                                    } else {
                                                        $color = 'bg-danger';
                                                        $label = 'Loaded';
                                                    }
                                                ?>
                                                    <div class="text-center">
                                                        <div class="position-relative mx-auto mb-2" style="height: 150px; width: 75px; border: 2px solid #000; overflow: hidden; background-color: #f8f9fa;">
                                                            <div class="position-absolute bottom-0 start-0 w-100 <?php echo $color; ?>" style="height: <?php echo $fill; ?>%; transition: height 0.3s;"></div>
                                                            <div class="position-absolute top-50 start-50 translate-middle text-dark fw-bold small">
                                                                <?php echo $fill; ?>%
                                                            </div>
                                                        </div>
                                                        <div class="fw-semibold small"><?php echo $type; ?></div>
                                                        <span class="badge <?php echo $color; ?>"><?php echo $label; ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php 

                                    } 
                                }

                                else {
                                
                                ?>
                                    <div class="row text-center">
                                        <div class="col">
                                            <h5> No Trash Bin Data. </h5>
                                            <a href="#" class="btn btn-primary custom-add-btn btn-sm" data-bs-toggle="modal" data-bs-target="#addBinModal">
                                                Add New Bin
                                            </a>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                        </div>

                    </div>
                </a>

            </div>

        </div>

    </section>
    <!-- End Table -->

</main>
<!-- End #main -->
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

                        <a href="#" class="btn btn-primary custom-add-btn" data-bs-toggle="modal" data-bs-target="#addBinModal">
                            Add New Bin
                        </a>

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

                                                    <button class="btn btn-primary custom-add-btn btn-sm" title="Edit Bin" data-bs-toggle="modal" data-bs-target="#update-bin-<?php echo htmlspecialchars($bin_data["bin_id"]); ?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="../../process/admin/bin-management.php" method="POST">

                                                        <input type="hidden" name="bin-id" value="<?php echo htmlspecialchars(base64_encode($bin_data["bin_id"])); ?>">
                                                        <input type="hidden" name="delete-bin" value="1">

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete Bin"
                                                            onclick="return confirmAction(event, this.form, 'Delete Bin?', 'warning', 'Are you sure you want to delete this bin: <?php echo htmlspecialchars($bin_data['bin_name']); ?>?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>

                                                    </form>

                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="update-bin-<?php echo htmlspecialchars($bin_data["bin_id"]); ?>" tabindex="-1" aria-labelledby="updateBinModal">

                                                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-secondary text-white">
                                                            <h5 class="modal-title fw-bold" id="updateBinModal"> Update Bin: <?php echo htmlspecialchars($bin_data["bin_name"]); ?> </h5>
                                                            <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <!-- Form Start -->
                                                        <form action="../../process/admin/bin-management.php" method="POST">

                                                            <input type="hidden" name="bin-id" value="<?php echo htmlspecialchars(base64_encode($bin_data["bin_id"])); ?>">

                                                            <div class="modal-body">

                                                                <div class="container-fluid">

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <label for="bin-id" class="form-label"> Unique Bin ID: </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                                                                <input type="text" class="form-control" id="bin-id" name="unique-bin-id" placeholder="Enter unique bin ID" value="<?php echo htmlspecialchars($bin_data["unique_bin_id"]); ?>" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <label for="bin-name" class="form-label"> Bin Name: </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-box"></i></span>
                                                                                <input type="text" class="form-control" id="bin-name" name="bin-name" placeholder="Enter bin name" value="<?php echo htmlspecialchars($bin_data["bin_name"]); ?>" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <label for="bin-location" class="form-label"> Bin Location: </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                                                <select class="form-select" id="bin-location" name="bin-location" required>

                                                                                    <?php
                                                                                    $fetch_locations = $conn->prepare("SELECT * FROM bin_locations_tbl ORDER BY updated_at DESC");
                                                                                    $fetch_locations->execute();

                                                                                    while ($location_data = $fetch_locations->fetch()) {
                                                                                    ?>
                                                                                        <option value="<?php echo htmlspecialchars($location_data["location_id"]); ?>" <?php echo $location_data["location_id"] === $bin_data["bin_location"] ? "selected" : ""; ?>>
                                                                                            <?php echo htmlspecialchars($location_data["target_location"]); ?>
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
                                                                <button type="submit" class="btn btn-success custom-add-btn" name="update-bin">
                                                                    <i class="bi bi-pencil-square me-1"></i> Update Bin
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
                                                No bin found. <br>

                                                <a class="btn btn-primary custom-add-btn btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addBinModal">
                                                    Add New Bin
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

            <!-- Trash Bin Status Table -->
            <div class="col-12">

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

<!-- Modal -->
<div class="modal fade" id="addBinModal" tabindex="-1" aria-labelledby="addLocationLabel">

    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold" id="addLocationLabel"> Add New Location </h5>
                <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form action="../../process/admin/bin-management.php" method="POST">

                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="bin-id" class="form-label"> Unique Bin ID: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control" id="bin-id" name="unique-bin-id" placeholder="Enter unique bin ID" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="bin-name" class="form-label"> Bin Name: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-box"></i></span>
                                    <input type="text" class="form-control" id="bin-name" name="bin-name" placeholder="Enter bin name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="bin-location" class="form-label"> Bin Location: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <select class="form-select" id="bin-location" name="bin-location" required>
                                        <option selected disabled value=""> Choose Bin Location </option>

                                        <?php
                                        $fetch_locations = $conn->prepare("SELECT * FROM bin_locations_tbl ORDER BY updated_at DESC");
                                        $fetch_locations->execute();

                                        while ($location_data = $fetch_locations->fetch()) {
                                        ?>
                                            <option value="<?php echo htmlspecialchars($location_data["location_id"]); ?>">
                                                <?php echo htmlspecialchars($location_data["target_location"]); ?>
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
                    <button type="submit" class="btn btn-success custom-add-btn" name="add-new-bin">
                        <i class="bi bi-plus-circle me-1"></i> Add New Bin
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
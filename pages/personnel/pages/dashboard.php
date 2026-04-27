<?php
    // Bin Count
    $get_bin_count = $conn->prepare("SELECT 
                                            COUNT(*) AS 'bin_count' 
                                        FROM bins_tbl");
    $get_bin_count->execute();
    $bin_count = $get_bin_count->fetch()["bin_count"];

    // Completed Maintenance
    $get_completed_maintenance_count = $conn->prepare("SELECT 
                                            COUNT(*) AS 'maintenance_count' 
                                        FROM maintenance_tbl
                                        WHERE maintenance_status = :maintenance_status
                                        AND assigned_personnel = :personnel_id
                                        ");
    $get_completed_maintenance_count->execute([
        ":maintenance_status" => "Completed",
        ":personnel_id" => $personnel_id
    ]);
    $completed_maintenance_count = $get_completed_maintenance_count->fetch()["maintenance_count"];
?>

<!-- Main -->
<main id="main" class="main">

    <!-- Pagetitle -->
    <?php
        include_once "includes/pagetitle.php";
    ?>
    <!-- End Pagetitle -->

    <section class="section dashboard">

        <div class="row">

            <!-- Welcome Message -->
            <div class="col-12">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body py-4 px-4">
                        <h4 class="fw-bold mb-2" style="color: #7CBF42;">
                            <i class="bi bi-person-circle me-2"></i>Welcome, <?php echo htmlspecialchars($first_name); ?>!
                        </h4>
                        <p class="mb-0 fs-6 text-secondary">
                            Here’s an overview of the <strong>Smart Trash Bin System</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Bins -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <a href="home.php?page=bin-monitoring" class="text-decoration-none">
                    <div class="card info-card shadow-sm border-0 hover-shadow">

                        <div class="card-body">
                            <h5 class="card-title text-dark mb-3">
                                <i class="bi bi-trash me-2 text-success"></i> Total Bins
                            </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-trash fs-4"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 class="mb-0 text-dark"> <?php echo htmlspecialchars($bin_count); ?> </h6>
                                    <span class="text-muted small"> Total Bins </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div>
            <!-- End Total Bins -->

            <!-- Maintenance Alerts -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <a href="home.php?page=maintenance-alerts" class="text-decoration-none">
                    <div class="card info-card shadow-sm border-0 hover-shadow">

                        <div class="card-body">
                            <h5 class="card-title text-dark mb-3">
                                <i class="bi bi-tools me-2 text-warning"></i> Maintenance Alerts
                            </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-tools fs-4"></i>
                                </div>

                                <div class="ps-3">
                                    <h6 class="mb-0 text-dark"> <?php echo htmlspecialchars($maintenance_count); ?> </h6>
                                    <span class="text-muted small"> Pending Maintenance </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div>
            <!-- End Maintenance Alerts -->

            <!-- Maintenance Alerts -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <a href="home.php?page=maintenance-alerts" class="text-decoration-none">
                    <div class="card info-card shadow-sm border-0 hover-shadow">

                        <div class="card-body">
                            <h5 class="card-title text-dark mb-3">
                                <i class="bi bi-tools me-2 text-primary"></i> Maintenance Alerts
                            </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-tools fs-4"></i>
                                </div>

                                <div class="ps-3">
                                    <h6 class="mb-0 text-dark"> <?php echo htmlspecialchars($completed_maintenance_count); ?> </h6>
                                    <span class="text-muted small"> Done Maintenance </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div>
            <!-- End Maintenance Alerts -->

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
                                                                               
                                                                                LIMIT 8
                                                                                ");
                                $fetch_bin_fill_data->execute();

                                if($fetch_bin_fill_data->rowCount() > 0) {

                                    while($fill_data = $fetch_bin_fill_data->fetch()) {

                                        $fill_types = [
                                            'Plastic' => $fill_data["plastic_fill_level"],
                                            'Metal' => $fill_data["metal_fill_level"]
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
    
</main>
<!-- End Main -->
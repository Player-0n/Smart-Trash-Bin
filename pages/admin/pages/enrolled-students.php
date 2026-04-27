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
                            <i class="bi bi-person-lines-fill fs-3"></i>
                            Enrolled Students
                        </h5>
                        <a href="#" class="btn btn-primary custom-add-btn" data-bs-toggle="modal" data-bs-target="#addStudentsModal">
                            Add Student/s
                        </a>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Student LRN </th>
                                        <th scope="col"> First Name </th>
                                        <th scope="col"> Last Name </th>
                                        <th scope="col"> Email Address </th>
                                        <th scope="col"> Enrolled At </th>
                                        <th scope="col"> Updated At </th>
                                        <th scope="col"> Action </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_enrolled_students = $conn->prepare("SELECT 
                                                                                et.student_lrn,
                                                                                COALESCE(sc.first_name, et.first_name) AS 'first_name',
                                                                                COALESCE(sc.last_name, et.last_name) AS 'last_name',
                                                                                COALESCE(sc.email_address, 'No Registered Account') AS 'email_address',
                                                                                et.created_at,
                                                                                et.updated_at
                                                                            FROM enrolled_students_tbl et
                                                                            LEFT JOIN student_accounts_tbl sc
                                                                            ON et.student_lrn = sc.student_lrn
                                                                            ORDER BY et.created_at DESC
                                                                            ");
                                    $fetch_enrolled_students->execute();

                                    if ($fetch_enrolled_students->rowCount() > 0) {
                                        while ($student_data = $fetch_enrolled_students->fetch()) {

                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($student_data["student_lrn"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($student_data["first_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($student_data["last_name"]); ?>
                                                </td>

                                                <td class="fw-bold <?php echo htmlspecialchars($student_data["email_address"] === "No Registered Account" ? "text-danger" : ""); ?>">
                                                    <?php echo htmlspecialchars($student_data["email_address"]); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars(format_timestamp($student_data["created_at"])); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars(format_timestamp($student_data["updated_at"])); ?>
                                                </td>

                                                <td>
                                                    <form action="../../process/admin/student-management.php" method="POST">
                                                        <input type="hidden" name="student-lrn" value="<?php echo htmlspecialchars(base64_encode($student_data["student_lrn"])); ?>">
                                                        <input type="hidden" name="delete-student" value="1">

                                                        <button 
                                                        type="submit" 
                                                        title="Delete" 
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirmDelete(event, this.form, '<?php echo htmlspecialchars($student_data['first_name'] . ' ' . $student_data['last_name']); ?>', 'Are you sure you want to delete this student and it\'s data?')"
                                                        >
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
                                                No student data found. <br>
                                                <a href="#" class="btn btn-primary custom-add-btn btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#addStudentsModal"> 
                                                    Add Student/s 
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

        <div class="row">

            <div class="col-lg-12">

                <div class="card shadow">

                    <div class="d-flex justify-content-between align-items-center px-4 py-2 bg-secondary mb-2 mt-1">
                        <h5 class="card-title text-white custom-card-title fw-bold">
                            <i class="bi bi-box-arrow-up fs-3"></i>
                            Uploaded Files
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> File Name </th>
                                        <th scope="col"> File Size </th>
                                        <th scope="col"> Uploaded By </th>
                                        <th scope="col"> Uploaded At </th>
                                        <th scope="col"> Action </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_uploaded_files = $conn->prepare("SELECT 
                                                                                    ft.*, CONCAT(ac.first_name, ' ', ac.last_name) AS 'uploaded_by'
                                                                                FROM uploaded_files_tbl ft
                                                                                LEFT JOIN admin_accounts_tbl ac
                                                                                ON ft.uploaded_by = ac.admin_id
                                                                                ORDER BY uploaded_at DESC
                                                                            ");

                                    $fetch_uploaded_files->execute();

                                    if ($fetch_uploaded_files->rowCount() > 0) {
                                        while ($file_data = $fetch_uploaded_files->fetch()) {

                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($file_data["file_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($file_data["file_size"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($file_data["uploaded_by"]); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars(format_timestamp($file_data["uploaded_at"])); ?>
                                                </td>

                                                <td class="d-flex gap-2 align-items-center justify-content-center">
                                                    <a href="../../uploads/files/<?php echo htmlspecialchars($file_data["file_name"]); ?>" class="btn btn-primary custom-add-btn btn-sm" title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>

                                                    <form action="../../process/admin/student-management.php" method="POST">
                                                        <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">
                                                        <input type="hidden" name="delete-file" value="1">

                                                        <button 
                                                        type="submit" 
                                                        title="Delete" 
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirmDelete(event, this.form, '<?php echo htmlspecialchars($file_data['file_name']); ?>', 'Are you sure you want to delete this file?')"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal -->


                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No uploaded files.
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

<!-- End main -->

<!-- Modal -->
<div class="modal fade" id="addStudentsModal" tabindex="-1" aria-labelledby="addStudentsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold" id="addStudentsLabel"> Add Student/s </h5>
                <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">

                    <!-- Multiple Students -->
                    <form action="../../process/admin/student-management.php" method="POST" enctype="multipart/form-data" class="mb-4">

                        <h5 class="fw-bold"> Add Multiple Students </h5>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" for="student_file"> Upload File(Excel/CSV): </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upload"></i></span>
                                    <input class="form-control" type="file" id="student_file" name="student-files" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary custom-add-btn w-100" name="upload-student-files"> Add Students </button>
                    </form>

                    <hr>

                    <!-- Individual Student Form -->
                    <form action="../../process/admin/student-management.php" method="POST" autocomplete="off">

                        <h5 class="fw-bold"> Add Individual Student </h5>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label" for="lrn"> Student LRN: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                    <input type="text" class="form-control" id="lrn" name="student-lrn" placeholder="Learner Reference Number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="first_name"> First Name: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" class="form-control" id="first_name" name="first-name" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="last_name"> Last Name: </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" class="form-control" id="last_name" name="last-name" placeholder="Last Name" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success custom-add-btn w-100" name="add-student"> Add Student </button>
                    </form>

                    <div class="modal-footer mt-4 px-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Confirm Delete -->
<script>
    function confirmDelete(event, formElement, item, text) {
        event.preventDefault();

        Swal.fire({
            title: `Delete ${item}?`,
            text: text,
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
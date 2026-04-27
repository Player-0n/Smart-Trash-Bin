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
                            <i class="bi bi-person-vcard fs-3"></i>
                            Student Accounts
                        </h5>   
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Personnel ID </th>
                                        <th scope="col"> RFID </th>
                                        <th scope="col"> Profile </th>
                                        <th scope="col"> Name </th>
                                        <th scope="col"> Email Address </th>
                                        <th scope="col"> Created At </th>
                                        <th scope="col"> Details </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_student_accounts = $conn->prepare("SELECT
                                                                                sa.*, sp.*
                                                                            FROM student_accounts_tbl sa
                                                                            LEFT JOIN student_profile_tbl sp
                                                                            ON sa.student_lrn = sp.student_lrn
                                                                            ORDER BY sa.last_name
                                                                            ");
                                    $fetch_student_accounts->execute();

                                    if ($fetch_student_accounts->rowCount() > 0) {
                                        while ($student_data = $fetch_student_accounts->fetch()) {
                                            $student_profile_picture = $student_data["profile_picture"];

                                            if (empty($student_profile_picture) || !file_exists($file_path . $student_profile_picture)) {
                                                $student_profile_picture = "default-img.png";
                                            }
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($student_data["student_lrn"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($student_data["student_rfid"]); ?>
                                                </td>

                                                <td>
                                                    <img
                                                        src="<?php echo htmlspecialchars($file_path . $student_profile_picture); ?>"
                                                        alt="Profile Picture"
                                                        class="rounded-circle"
                                                        width="40" height="40">
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($student_data["first_name"] . " " . $student_data["last_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($student_data["email_address"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($student_data["created_at"])); ?>
                                                </td>

                                                <td>
                                                    <button class="btn btn-primary custom-add-btn btn-sm" data-bs-toggle="modal" data-bs-target="#student-account-<?php echo htmlspecialchars($student_data["student_lrn"]); ?>">
                                                        Details
                                                    </button>
                                                </td>
                                            </tr>

                                            

 <!-- Modal -->
 <div class="modal fade" id="student-account-<?php echo htmlspecialchars($student_data["student_lrn"]); ?>" tabindex="-1" aria-labelledby="viewAccountLabel" aria-hidden="true">

     <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

         <div class="modal-content">

             <div class="modal-header bg-secondary text-white fw-bold">

                 <h5 class="modal-title fw-bold w-100" id="viewAccountLabel">
                     Account Information
                 </h5>

                 <div class="w-100 d-flex justify-content-end">

                     <div class="me-3">
                         Student LRN: <?php echo htmlspecialchars($student_data["student_lrn"]); ?>
                     </div>

                 </div>

                 <button type="button" class="btn-close p-3 m-2" style="transform: scale(2)" data-bs-dismiss="modal" aria-label="Close"></button>

             </div>

             <div class="modal-body">

                 <div class="container-fluid">

                     <div class="row mb-4 mt-3">

                         <div class="col-md-3 text-center">
                             <img
                                 src="<?php echo htmlspecialchars($file_path . $student_profile_picture); ?>"
                                 alt="Profile Picture"
                                 class="rounded rounded-circle border mb-2"
                                 width="200" height="200">

                             <p>

                                 <?php
                                    if ($student_data["locked_account"] === "Yes"):
                                    ?>
                                     <span class="badge bg-danger px-2 py-1">
                                         Locked Account
                                     </span>

                                     <br>
                                 <?php
                                    endif;
                                    ?>

                                 <?php
                                    if (!empty($student_data["facebook_link"])):
                                    ?>
                                     <a href="<?php echo htmlspecialchars($student_data["facebook_link"]); ?>" target="_blank">
                                         <i class="bi bi-facebook fs-3"></i>
                                     </a>
                                 <?php
                                    endif;
                                    ?>
                             </p>
                         </div>

                         <div class="col-md-9">

                             <div class="row mb-3">

                                <?php
// Example: $student_data is fetched from database before this form
// Example: $student_data['student_lrn'] and $student_data['student_rfid'] are available
?>

<form action="../../api/update_rfid.php" method="POST" style="max-width: 350px;">
    <!-- Hidden Student LRN -->
    <input type="hidden" name="student_lrn" 
           value="<?php echo htmlspecialchars($student_data['student_lrn']); ?>">

    <!-- RFID Input -->
    <div class="mb-3 w-50">
        <label for="student_rfid" class="form-label">Student RFID:</label>
        <div class="input-group">
            <input type="text"
                   id="student_rfid"
                   name="student_rfid"
                   class="form-control form-control-sm"
                   value="<?php echo htmlspecialchars($student_data['student_rfid']); ?>"
                   placeholder="Enter RFID"
                   maxlength="20"
                   required>
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </div>
    </div>
</form>



<!-- Status Message -->
<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] === 'success'): ?>
        <small class="text-success">✔ RFID updated successfully!</small>
    <?php else: ?>
        <small class="text-danger">✖ Failed to update RFID.</small>
    <?php endif; ?>
<?php endif; ?>




                                 <div class="col-md-4">
                                     <label class="form-label"> First Name: </label>
                                     <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["first_name"]); ?>" readonly disabled>
                                 </div>

                                 <div class="col-md-4">
                                     <label class="form-label"> Middle Name: </label>
                                     <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["middle_name"]); ?>" readonly disabled>
                                 </div>

                                 <div class="col-md-4">
                                     <label class="form-label"> Last Name: </label>
                                     <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["last_name"]); ?>" readonly disabled>
                                 </div>

                                                                        </div>

                                                                        <div class="row mb-3">

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Email Address: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["email_address"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Grade Level: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["grade_level"]); ?>" readonly disabled>
                                                                            </div>

                                                                        </div>

                                                                        <div class="row mb-3">

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Date of Birth: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(format_date($student_data["date_of_birth"])); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Gender: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["gender"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Civil Status: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["civil_status"]); ?>" readonly disabled>
                                                                            </div>

                                                                        </div>

                                                                        <div class="row mb-3">

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Address: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["address"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Phone Number: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student_data["phone_number"]); ?>" readonly disabled>
                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="modal-footer">
                                                            <form action="../../process/admin/student-management.php" method="POST">

                                                                <input type="hidden" name="student-lrn" value="<?php echo htmlspecialchars(base64_encode($student_data["student_lrn"])); ?>">
                                                                <input type="hidden" name="account-status" value="<?php echo htmlspecialchars($student_data["locked_account"] === "No" ? "Yes" : "No"); ?>">
                                                                <input type="hidden" name="set-student-account-status" value="1">

                                                                <?php
                                                                    if($student_data["locked_account"] === "No") {
                                                                ?>
                                                                    <button
                                                                        type="submit"
                                                                        class="btn btn-sm btn-danger"
                                                                        title="Lock Account"
                                                                        onclick="return confirmAction(event, this.form, 'Lock Account', 'warning', 'Are you sure you want to lock this account: <?php echo htmlspecialchars($student_data['first_name'] . ' ' . $student_data['last_name']); ?>?')">
                                                                        Lock Account
                                                                    </button>
                                                                <?php
                                                                    } else {
                                                                ?>
                                                                   <button
                                                                        type="submit"
                                                                        class="btn btn-sm btn-primary custom-add-btn"
                                                                        title="Unlock Account"
                                                                        onclick="return confirmAction(event, this.form, 'Unlock Account', 'warning', 'Are you sure you want to unlock this account: <?php echo htmlspecialchars($student_data['first_name'] . ' ' . $student_data['last_name']); ?>?')">
                                                                        Unlock Account
                                                                    </button>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </form>

                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                No personnel data found.
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

<!-- Confirm Action -->
<script>
    function confirmAction(event, formElement, title, icon, message) {
        event.preventDefault();

        Swal.fire({
            title: `${title}?`,
            text: message,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: title,
            cancelButtonText: 'Cancel',
            confirmButtonColor: title === 'Lock Account' ? '#dc3545' : '#7cbf42',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                formElement.submit();
            }
        });

        return false;
    }
</script>

<script>
function updateRFID() {
    const rfid = document.getElementById('student_rfid').value;
    const lrn = "<?php echo $student_data['student_lrn']; ?>"; // get from PHP variable
    const status = document.getElementById('rfid-status');

    fetch('update_rfid.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'student_rfid=' + encodeURIComponent(rfid) + 
              '&student_lrn=' + encodeURIComponent(lrn)
    })
    .then(response => response.text())
    .then(result => {
        if (result.trim() === 'success') {
            status.style.display = 'inline';
            status.textContent = '✔ RFID updated successfully!';
            status.classList.remove('text-danger');
            status.classList.add('text-success');
        } else {
            status.style.display = 'inline';
            status.textContent = '⚠ Failed to update RFID.';
            status.classList.remove('text-success');
            status.classList.add('text-danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        status.style.display = 'inline';
        status.textContent = '⚠ Connection error.';
        status.classList.remove('text-success');
        status.classList.add('text-danger');
    });
}
</script>


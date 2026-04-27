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
                            <i class="bi bi-person-gear fs-3"></i>
                            Admin Accounts
                        </h5>
                        <a href="#" class="btn btn-primary custom-add-btn" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                            Add New Admin
                        </a>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col"> Admin ID </th>
                                        <th scope="col"> Profile </th>
                                        <th scope="col"> Name </th>
                                        <th scope="col"> Email Address </th>
                                        <th scope="col"> Created At </th>
                                        <th scope="col"> Details </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $fetch_admin_accounts = $conn->prepare("SELECT
                                                                                ac.*, ap.*
                                                                            FROM admin_accounts_tbl ac
                                                                            LEFT JOIN admin_profile_tbl ap
                                                                            ON ac.admin_id = ap.admin_id
                                                                            ");
                                    $fetch_admin_accounts->execute();

                                    if ($fetch_admin_accounts->rowCount() > 0) {
                                        while ($admin_data = $fetch_admin_accounts->fetch()) {
                                            $admin_profile_picture = $admin_data["profile_picture"];

                                            if (empty($admin_profile_picture) || !file_exists($file_path . $admin_profile_picture)) {
                                                $admin_profile_picture = "default-img.png";
                                            }
                                    ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($admin_data["admin_id"]); ?>
                                                </td>

                                                <td>
                                                    <img
                                                        src="<?php echo htmlspecialchars($file_path . $admin_profile_picture); ?>"
                                                        alt="Profile Picture"
                                                        class="rounded-circle"
                                                        width="40" height="40">
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($admin_data["first_name"] . " " . $admin_data["last_name"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars($admin_data["email_address"]); ?>
                                                </td>

                                                <td class="fw-bold">
                                                    <?php echo htmlspecialchars(format_timestamp($admin_data["created_at"])); ?>
                                                </td>

                                                <td>
                                                    <?php
                                                        if($admin_data["admin_id"] === (int)$admin_id) {
                                                    ?>
                                                        <span>
                                                            Logged in account
                                                        </span>  
                                                    <?php
                                                        } else {
                                                    ?>
                                                        <button class="btn btn-primary custom-add-btn btn-sm" data-bs-toggle="modal" data-bs-target="#admin-account-<?php echo htmlspecialchars($admin_data["admin_id"]); ?>">
                                                            Details
                                                        </button>
                                                    <?php
                                                        }
                                                    ?>
                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="admin-account-<?php echo htmlspecialchars($admin_data["admin_id"]); ?>" tabindex="-1" aria-labelledby="viewAccountLabel" aria-hidden="true">

                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-secondary text-white fw-bold">

                                                            <h5 class="modal-title fw-bold w-100" id="viewAccountLabel">
                                                                Account Information
                                                            </h5>

                                                            <div class="w-100 d-flex justify-content-end">

                                                                <div class="me-3">
                                                                    Admin ID: <?php echo htmlspecialchars($admin_data["admin_id"]); ?>
                                                                </div>

                                                            </div>

                                                            <button type="button" class="btn-close p-3 m-2" style="transform: scale(2)" data-bs-dismiss="modal" aria-label="Close"></button>

                                                        </div>

                                                        <div class="modal-body">

                                                            <div class="container-fluid">

                                                                <div class="row mb-4 mt-3">

                                                                    <div class="col-md-3 text-center">
                                                                        <img
                                                                            src="<?php echo htmlspecialchars($file_path . $admin_profile_picture); ?>"
                                                                            alt="Profile Picture"
                                                                            class="rounded rounded-circle border mb-2"
                                                                            width="200" height="200">

                                                                        <p>
                                                                            <?php
                                                                                if($admin_data["locked_account"] === "Yes"):
                                                                            ?>
                                                                                <span class="badge bg-danger px-2 py-1">
                                                                                    Locked Account
                                                                                </span>

                                                                                <br>
                                                                            <?php
                                                                                endif;
                                                                            ?>
                                                                                
                                                                            <?php
                                                                            if (!empty($admin_data["facebook_link"])):
                                                                            ?>
                                                                                <a href="<?php echo htmlspecialchars($admin_data["facebook_link"]); ?>" target="_blank">
                                                                                    <i class="bi bi-facebook fs-3"></i>
                                                                                </a>
                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                        </p>
                                                                    </div>

                                                                    <div class="col-md-9">

                                                                        <div class="row mb-3">

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> First Name: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["first_name"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Middle Name: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["middle_name"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Last Name: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["last_name"]); ?>" readonly disabled>
                                                                            </div>

                                                                        </div>

                                                                        <div class="row mb-3">

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Email Address: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["email_address"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Role: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["role"]); ?>" readonly disabled>
                                                                            </div>

                                                                        </div>

                                                                        <div class="row mb-3">

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Date of Birth: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(format_date($admin_data["date_of_birth"])); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Gender: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["gender"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="form-label"> Civil Status: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["civil_status"]); ?>" readonly disabled>
                                                                            </div>

                                                                        </div>

                                                                        <div class="row mb-3">

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Address: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["address"]); ?>" readonly disabled>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="form-label"> Phone Number: </label>
                                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin_data["phone_number"]); ?>" readonly disabled>
                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="modal-footer">
                                                            <form action="../../process/admin/account-management.php" method="POST">

                                                                <input type="hidden" name="admin-id" value="<?php echo htmlspecialchars(base64_encode($admin_data["admin_id"])); ?>">
                                                                <input type="hidden" name="account-status" value="<?php echo htmlspecialchars($admin_data["locked_account"] === "No" ? "Yes" : "No"); ?>">
                                                                <input type="hidden" name="set-admin-account-status" value="1">

                                                                <?php
                                                                    if($admin_data["locked_account"] === "No") {
                                                                ?>
                                                                    <button
                                                                        type="submit"
                                                                        class="btn btn-sm btn-danger"
                                                                        title="Lock Account"
                                                                        onclick="return confirmAction(event, this.form, 'Lock Account', 'warning', 'Are you sure you want to lock this account: <?php echo htmlspecialchars($admin_data['first_name'] . ' ' . $admin_data['last_name']); ?>?')">
                                                                        Lock Account
                                                                    </button>
                                                                <?php
                                                                    } else {
                                                                ?>
                                                                   <button
                                                                        type="submit"
                                                                        class="btn btn-sm btn-primary custom-add-btn"
                                                                        title="Unlock Account"
                                                                        onclick="return confirmAction(event, this.form, 'Unlock Account', 'warning', 'Are you sure you want to unlock this account: <?php echo htmlspecialchars($admin_data['first_name'] . ' ' . $admin_data['last_name']); ?>?')">
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
                                            <td colspan="5" class="text-center text-muted">
                                                No admin data found.
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
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title fw-bold" id="addAdminLabel"> Add New Admin </h5>
        <button type="button" class="btn-close p-3" style="transform: scale(2);" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="../../process/admin/account-management.php" method="POST" autocomplete="off">
        <div class="modal-body">

          <div class="container-fluid">

            <div class="row mb-3">

              <div class="col-md-4">
                <label class="form-label" for="first_name"> First Name: </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                  <input type="text" class="form-control" id="first_name" name="first-name" placeholder="First Name" required>
                </div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="middle_name"> Middle Name (Optional): </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person"></i></span>
                  <input type="text" class="form-control" id="middle_name" name="middle-name" placeholder="Middle Name">
                </div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="last_name"> Last Name: </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                  <input type="text" class="form-control" id="last_name" name="last-name" placeholder="Last Name" required>
                </div>
              </div>

            </div>

            <div class="row mb-3">

              <div class="col-md-6">
                <label class="form-label" for="email"> Email Address: </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                  <input type="email" class="form-control" id="email" name="email-address" placeholder="Email Address" required>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="gender"> Gender: </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                  <select class="form-select" id="gender" name="gender" required>
                    <option value="" selected disabled> Select Gender </option>
                    <option value="Male"> Male </option>
                    <option value="Female"> Female </option>
                    <option value="Others"> Others </option>
                  </select>
                </div>
              </div>

            </div>

            <div class="row mb-3">

              <div class="col-md-6">
                <label class="form-label" for="status"> Civil Status: </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-heart-fill"></i></span>
                  <select class="form-select" id="status" name="civil-status" required>
                    <option value="" selected disabled> Select Status </option>
                    <option value="Single"> Single </option>
                    <option value="Married"> Married </option>
                    <option value="Divorced"> Divorced </option>
                    <option value="Widowed"> Widowed </option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="phone"> Phone Number: </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                  <input type="text" class="form-control" id="phone" name="phone-number" placeholder="Phone Number" required>
                </div>
              </div>

            </div>

            <div class="row mb-3">

              <div class="col-md-12">
                <label class="form-label" for="address"> Address: </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                </div>
              </div>

            </div>

            <div class="row">

                <div class="col-md-12">
                    <span class="text-muted fst-italic">
                        Note: <strong>Default password</strong> will be the user's 
                        <strong>last name</strong> in all <strong>uppercase letters.</strong>. 
                    </span>
                </div>

            </div>

          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="add-admin-account">Add Admin</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>

      </form>

    </div>
  </div>
</div>

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

<?php

    $update_profile = false;
    $update_photo = false;
    $update_password = false;

    $active_tab = "overview";

    if(isset($_GET["update-profile"]) && isset($_GET["update-profile"]) === true) {
        $active_tab = "profile";
    } 
    
    else if(isset($_GET["update-photo"]) && isset($_GET["update-photo"]) === true) {
        $active_tab = "photo";
    } 
    
    else if(isset($_GET["update-password"]) && isset($_GET["update-password"]) === true) {
        $active_tab = "password";
    }

?>

<!-- Main -->
<main id="main" class="main">

    <?php include_once "includes/pagetitle.php"; ?>

    <!-- Details Main -->
    <section class="section profile">

        <div class="row">

            <div class="col-xl-4">

                <div class="card mb-2">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img
                            src="<?php echo htmlspecialchars($file_path . $profile_picture); ?>"
                            alt="Profile Picture"
                            class="rounded-circle"
                            id="profile-preview"
                            width="110" height="110">

                        <h2> <?php echo htmlspecialchars($first_name . " " . $last_name); ?> </h2>
                        <h3> <?php echo htmlspecialchars($grade_level . " " . $role); ?> </h3>

                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">

                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link <?php echo htmlspecialchars($active_tab === "overview" ? "active" : ""); ?>" data-bs-toggle="tab" data-bs-target="#profile-overview"> Profile Overview </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#other-information"> Profile Details </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link <?php echo htmlspecialchars($active_tab === "profile" ? "active" : ""); ?>" data-bs-toggle="tab" data-bs-target="#edit-information"> Edit Profile </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link <?php echo htmlspecialchars($active_tab === "photo" ? "active" : ""); ?>" data-bs-toggle="tab" data-bs-target="#picture-edit"> Change Photo </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link <?php echo htmlspecialchars($active_tab === "password" ? "active" : ""); ?>" data-bs-toggle="tab" data-bs-target="#change-password"> Change Password </button>
                            </li>

                        </ul>

                        <div class="tab-content pt-2">

                            <!-- Overview -->
                            <div class="tab-pane fade <?php echo htmlspecialchars($active_tab === "overview" ? "show active" : ""); ?> profile-overview custom-overview" id="profile-overview">

                                <h5 class="card-title"> Profile Overview </h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label "> Full Name </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($first_name . " " . $last_name); ?> </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Grade Level </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($grade_level); ?> </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Address </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($address); ?> </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Phone Number </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($phone_number); ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Email Address </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($email_address); ?> </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Facebook Link </div>
                                    <div class="col-lg-9 col-md-8">
                                        <?php
                                        if (!empty($facebook_link)) {
                                        ?>
                                            <a href="<?php echo htmlspecialchars($facebook_link); ?>" target="_blank">
                                                <?php echo htmlspecialchars($first_name . " " . $last_name); ?>
                                            </a>
                                        <?php
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </div>
                                </div>

                            </div>
                            <!-- Overview End -->

                            <!-- Information -->
                            <div class="tab-pane fade profile-overview" id="other-information">

                                <h5 class="card-title" style="color:#2b3c49;"> Primary Information </h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Date of Birth </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars(format_date($date_of_birth) ?? "N/A"); ?> </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label "> Gender </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($gender ?? "N/A"); ?> </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Marital Status </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($civil_status ?? "N/A"); ?> </div>
                                </div>

                                <h5 class="card-title" style="color:#2b3c49;"> Contact Information </h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label "> Phone Number </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($phone_number ?? "N/A"); ?> </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Address </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars($address ?? "N/A"); ?> </div>
                                </div>

                                <h5 class="card-title" style="color:#2b3c49;"> Social Media Links </h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Facebook Link </div>
                                    <div class="col-lg-9 col-md-8">
                                        <?php
                                        if (!empty($facebook_link)) {
                                        ?>
                                            <a href="<?php echo htmlspecialchars($facebook_link); ?>" target="_blank">
                                                <?php echo htmlspecialchars($first_name . " " . $last_name); ?>
                                            </a>
                                        <?php
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <h5 class="card-title" style="color:#2b3c49;"> Account Editing/Modify </h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"> Last Updated </div>
                                    <div class="col-lg-9 col-md-8"> <?php echo htmlspecialchars(format_timestamp($updated_at) ?? "N/A"); ?> </div>
                                </div>

                            </div>
                            <!-- Information End -->

                            <!-- Edit Infromation -->
                            <div class="tab-pane fade <?php echo htmlspecialchars($active_tab === "profile" ? "show active" : ""); ?> pt-3" id="edit-information">

                                <h5 class="card-title" style="color:#2b3c49;"> Update Profile </h5>

                                <!-- Profile Edit Form -->
                                <form action="../../process/student/update-profile.php" method="POST" class="custom-form">

                                    <div class="row mb-3">
                                        <label for="f-name" class="col-md-4 col-lg-3 col-form-label"> First Name: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="f-name" name="first-name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="m-name" class="col-md-4 col-lg-3 col-form-label"> Middle Name: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="m-name" name="middle-name" placeholder="Middle Name (Optional)" value="<?php echo htmlspecialchars($middle_name); ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="l-name" class="col-md-4 col-lg-3 col-form-label"> Last Name: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="l-name" name="last-name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="dob" class="col-md-4 col-lg-3 col-form-label"> Date of Birth: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="date" class="form-control" id="dob" name="date-of-birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="gend" class="col-md-4 col-lg-3 col-form-label"> Gender: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <select class="form-select" name="gender" id="gend" required style="height: 40px;">
                                                <option value="Male" <?php echo htmlspecialchars($gender === "Male" ? "selected" : ""); ?>> Male </option>
                                                <option value="Female" <?php echo htmlspecialchars($gender === "Female" ? "selected" : ""); ?>> Female </option>
                                                <option value="Others" <?php echo htmlspecialchars($gender === "Others" ? "selected" : ""); ?>> Others </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="status" class="col-md-4 col-lg-3 col-form-label"> Civil Status: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <select class="form-select" name="civil-status" id="status" required style="height: 40px;">
                                                <option value="Single" <?php echo htmlspecialchars($civil_status === "Single" ? "selected" : ""); ?>> Single </option>
                                                <option value="Married" <?php echo htmlspecialchars($civil_status === "Married" ? "selected" : ""); ?>> Married </option>
                                                <option value="Divorced" <?php echo htmlspecialchars($civil_status === "Divorced" ? "selected" : ""); ?>> Divorced </option>
                                                <option value="Widowed" <?php echo htmlspecialchars($civil_status === "Widowed" ? "selected" : ""); ?>> Widowed </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="level" class="col-md-4 col-lg-3 col-form-label"> Grade Level: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <select class="form-select" name="grade-level" id="level" required style="height: 40px;">
                                                <option value="Grade 7" <?php echo htmlspecialchars($grade_level === "Grade 7" ? "selected" : ""); ?>> Grade 7 </option>
                                                <option value="Grade 8" <?php echo htmlspecialchars($grade_level === "Grade 8" ? "selected" : ""); ?>> Grade 8 </option>
                                                <option value="Grade 9" <?php echo htmlspecialchars($grade_level === "Grade 9" ? "selected" : ""); ?>> Grade 9 </option>
                                                <option value="Grade 10" <?php echo htmlspecialchars($grade_level === "Grade 10" ? "selected" : ""); ?>> Grade 10 </option>
                                                <option value="Grade 11" <?php echo htmlspecialchars($grade_level === "Grade 11" ? "selected" : ""); ?>> Grade 11 </option>
                                                <option value="Grade 12" <?php echo htmlspecialchars($grade_level === "Grade 12" ? "selected" : ""); ?>> Grade 12 </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email_address" class="col-md-4 col-lg-3 col-form-label"> Email Address: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="email" class="form-control" id="email_address" name="email-address" placeholder="Email Address" value="<?php echo htmlspecialchars($email_address); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="contact" class="col-md-4 col-lg-3 col-form-label"> Phone Number: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="contact" name="phone-number" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="temp-address" class="col-md-4 col-lg-3 col-form-label"> Address: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="temp-address" name="address" placeholder="Address" value="<?php echo htmlspecialchars($address); ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="fb-link" class="col-md-4 col-lg-3 col-form-label"> Facebook Link: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="fb-link" name="facebook-link" placeholder="Facebook Link" value="<?php echo htmlspecialchars($facebook_link); ?>">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary float-end custom-save-btn" name="update-profile"> Save Changes </button>
                                    </div>
                                </form>
                                <!-- End Profile Edit Form -->

                            </div>
                            <!-- Edit Information End -->

                            <!-- Picture Edit -->
                            <div class="tab-pane fade <?php echo htmlspecialchars($active_tab === "photo" ? "show active" : ""); ?> pt-3" id="picture-edit">

                                <!-- Profile Edit Form -->
                                <form action="../../process/student/update-profile.php" method="POST" enctype="multipart/form-data">

                                    <h5 class="card-title"> Change Profile Picture </h5>

                                    <div class="row mb-3">
                                        <label for="profile-image" class="col-md-4 col-lg-3 col-form-label"> Profile Image </label>

                                        <div class="col-md-8 col-lg-9">

                                            <img
                                                id="profile-preview"
                                                src="<?php echo htmlspecialchars($file_path . $profile_picture); ?>"
                                                alt="Profile Picture"
                                                width="110" height="110">

                                            <div class="pt-2">

                                                <label for="upload-pic" style="color: white;" class="btn btn-primary btn-sm custom-save-btn" title="Upload new profile image">
                                                    <i class="bi bi-upload"></i>
                                                </label>

                                                <input type="file" style="display: none;" id="upload-pic" accept="image/*" name="uploaded-photo" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" name="update-profile-picture" class="btn btn-primary float-end custom-save-btn"> Update Photo </button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>
                            <!-- End Picture Edit -->

                            <!-- Change Password -->
                            <div class="tab-pane fade <?php echo htmlspecialchars($active_tab === "password" ? "show active" : ""); ?> p-0" id="change-password">

                                <h5 class="card-title"> Change Password </h5>

                                <!-- Change Password Form -->
                                <form action="../../process/student/update-profile.php" method="POST" class="custom-form">

                                    <div class="row mb-3">
                                        <label for="curr-password" class="col-md-4 col-lg-3 col-form-label"> Current Password: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="password" class="form-control" id="curr-password" name="current-password" placeholder="Current Password" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="new-pword" class="col-md-4 col-lg-3 col-form-label"> New Password: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="password" class="form-control" id="new-pword" name="new-password" placeholder="New Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$" title="Passwords must at least 8 characters long, at least 1 uppercase, lowecase letters, digits and symbols." required>
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <label for="conf-new-pword" class="col-md-4 col-lg-3 col-form-label"> Confirm New Password: </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="password" class="form-control" id="conf-new-pword" name="confirm-new-password" placeholder="Confirm New Password" required>
                                        </div>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input border border-secondary" type="checkbox" id="toggle-passwords">

                                        <label class="form-check-label" for="toggle-passwords">
                                            Show Passwords
                                        </label>

                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary float-end custom-save-btn" name="update-password"> Change Password </button>
                                    </div>
                                </form>
                                <!-- End Change Password Form -->

                            </div>
                            <!-- Change Password End -->

                        </div>

                    </div>
                </div>

            </div>
            
        </div>

    </section>

</main>
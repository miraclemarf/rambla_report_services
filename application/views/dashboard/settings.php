<div class="content-wrapper">
    <div class="row">
        <div class="col-xl-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-3">Profile</h4>
                        <a style="text-decoration: none;" href="<?= base_url(); ?>" role="button"><span class="typcn typcn-chevron-left"></span>Back</a>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="<?= base_url('Settings/edit_profile'); ?>" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="fullName">Username</label>
                                        <input type="text" class="form-control" id="fullName" name="FullName" value="<?= $username; ?>" disabled="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?= $email->email; ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger">Save Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-3">Change Password</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="<?= base_url('Settings/edit_password'); ?>" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="currentPassword">Current Password</label>
                                        <input type="password" class="form-control" id="currentPassword" name="PasswordLama" placeholder="Current Password" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" class="form-control" id="newPassword" name="PasswordBaru" placeholder="New Password" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="confirmNewPassword">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmNewPassword" name="KonfirmPassword" placeholder="Confirm New Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger">Save Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
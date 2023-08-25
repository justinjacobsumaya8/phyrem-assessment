<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timely - Change Password</title>

    <?php $this->load->view('admin/template/includes/css'); ?>
</head>

<body>
    <?php $this->load->view('admin/template/includes/svg'); ?>
    <main>
        <?php $this->load->view('admin/template/sidebar'); ?>
        <div class="b-example-divider"></div>
        <div class="container mt-4">
            <div>
                <?php if (isset($error)) echo $error; ?>
                <?php if (isset($success)) echo $success; ?>
            </div>
            <div class="d-flex gap-3 align-items-center mb-4">
                <h4>Change password</h4>
                <a href="<?php echo base_url('admin/users') ?>" class="btn btn-primary">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#backspace" />
                    </svg>
                    <span>Back</span>
                </a>
            </div>
            <div class="mt-3">
                <?php echo form_open("admin/users/change-password/{$user->id}"); ?>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                    <p class="form-text mb-0 mt-2">Password should contain lowercase, uppercase, number, and special number</p>
                    <p class="form-text mb-0">Minimum of 10 characters</p>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </main>

    <?php $this->load->view('admin/template/includes/js'); ?>
</body>

</html>
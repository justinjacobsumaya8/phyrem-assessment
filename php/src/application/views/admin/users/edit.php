<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timely - Edit User</title>

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
            <h4>Edit User</h4>
            <div class="mt-3">
                <?= form_open("admin/users/edit/{$user->id}"); ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= $user->user_name ?>" placeholder="jane.doe" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">User type</label>
                    <select class="form-select" id="user-type" name="user_type" required>
                        <option value="">--</option>
                        <option value="2" <?= $user->user_type == "2" ? "selected" : "" ?>>Admin</option>
                        <option value="1" <?= $user->user_type == "1" ? "selected" : "" ?>>Super admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </main>

    <?php $this->load->view('admin/template/includes/js'); ?>
</body>

</html>
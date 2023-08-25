<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timely - Users</title>

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
                <h4>Users</h4>
                <a href="<?php echo base_url('admin/users/create') ?>" class="btn btn-primary">
                    <span>Create new</span>
                    <svg class="bi" width="16" height="16">
                        <use xlink:href="#plus-lg" />
                    </svg>
                </a>
            </div>
            <table id="users-table" class='display dataTable w-100'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User name</th>
                        <th>User type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <form action="" id="form-delete-user"></form>
            </table>
        </div>
    </main>

    <?php $this->load->view('admin/template/includes/js'); ?>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#users-table', {
                processing: true,
                serverSide: true,
                serverMethod: 'POST',
                ajax: {
                    url: '<?= base_url('index.php/admin/users/list') ?>',
                },
                columns: [{
                        data: "id",
                        render: (data, type, row, meta) => {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'user_type',
                        render: (data) => {
                            return data === "1" ? "Super admin" : "Admin";
                        }
                    },
                    // Actions
                    {
                        data: 'id',
                        render: (data, type, row) => {
                            const authUserId = '<?= $this->session->userdata("auth_user")->id ?>';

                            let deleteHtml = "";
                            if (authUserId !== data) {
                                deleteHtml = `
                                    <li>
                                        <button type="button" class="dropdown-item text-danger" onclick="onClickDelete(${row.id})">Delete</button>
                                    </li>
                                `;
                            }

                            return `
                                <div class="dropdown">
                                    <a class="btn btn-primary dropdown-toggle" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#gear-fill" />
                                    </svg>
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li>
                                            <a href="<?php echo base_url('admin/users/edit') ?>/${data}" class="dropdown-item">Edit</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('admin/users/change-password') ?>/${data}" class="dropdown-item">Change Password</a>
                                        </li>
                                        ${deleteHtml}
                                    </ul>
                                </div>
                            `;
                        }
                    }
                ]
            });
        });

        function onClickDelete(id) {
            const job = confirm("Are you sure to delete permanently?");

            if (!job) return false;

            const formAction = "<?php echo base_url('index.php/admin/users/delete') ?>" + "/" + id;
            $('#form-delete-user').attr('action', formAction);
            $('#form-delete-user').submit();
        }
    </script>
</body>

</html>
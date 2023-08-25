<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timely - Employees</title>

    <?php $this->load->view('admin/template/includes/css'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
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
                <h4>Employees</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-new-modal">
                    <span>Create new</span>
                    <svg class="bi" width="16" height="16">
                        <use xlink:href="#plus-lg" />
                    </svg>
                </button>
            </div>
            <table id="employees-table" class='display dataTable w-100'>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>QR Image</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <form action="" id="form-delete-employee"></form>
            </table>
        </div>
    </main>

    <?php $this->load->view('admin/employees/includes/create-modal'); ?>
    <?php $this->load->view('admin/employees/includes/edit-modal'); ?>

    <?php $this->load->view('admin/template/includes/js'); ?>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#employees-table', {
                // dom: 'Bfrtip',
                // buttons: [{
                //         text: 'Select all',
                //         action: function() {
                //             table.rows().select();
                //         }
                //     },
                //     {
                //         text: 'Select none',
                //         action: function() {
                //             table.rows().deselect();
                //         }
                //     }
                // ],
                // select: true,
                processing: true,
                serverSide: true,
                serverMethod: 'POST',
                ajax: {
                    url: '<?= base_url('index.php/admin/employees/list') ?>'
                },
                columnDefs: [{
                    orderable: false,
                    targets: [3, 5]
                }],
                columns: [{
                        data: "id"
                    },
                    {
                        data: 'first_name'
                    },
                    {
                        data: 'last_name'
                    },
                    {
                        data: 'qr_image',
                        render: (data, type, row, meta) => {
                            return `
                                <div>
                                    <img src="<?php base_url(); ?>/uploads/employees/${data}" width="50" height="50" alt="QR Image">
                                    <a href="<?= base_url() ?>/uploads/employees/${data}" download class="btn">
                                        <svg class="bi" width="24" height="24" role="img">
                                            <use xlink:href="#download" />
                                        </svg>
                                    </a>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'datetime_added',
                        render: (data, type, row, meta) => {
                            return moment(data).format('MMM Do, YYYY');;
                        }
                    },
                    // Actions
                    {
                        data: 'id',
                        render: (data, type, row, meta) => {
                            return `
                                <div class="dropdown">
                                    <a class="btn btn-primary dropdown-toggle" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#gear-fill" />
                                    </svg>
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li>
                                            <button type="button" class="dropdown-item" onclick="onClickEdit(${row.id}, '${row.first_name}', '${row.last_name}')">Edit</button>
                                        </li>
                                        <li>
                                            <a href="<?= base_url() ?>/uploads/employees/qr-${data}.png" download class="dropdown-item">Download QR</a>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" onclick="onClickDelete(${row.id})">Delete</button>
                                        </li>
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

            const formAction = "<?php echo base_url('index.php/admin/employees/delete') ?>" + "/" + id;
            $('#form-delete-employee').attr('action', formAction);
            $('#form-delete-employee').submit();
        }

        function onClickEdit(id, firstName, lastName) {
            let editModal = new bootstrap.Modal(document.getElementById('edit-modal'), {
                keyboard: false
            });
            editModal.show();

            const formAction = "<?php echo base_url('index.php/admin/employees/update') ?>" + "/" + id;
            $('#form-edit-employee').attr('action', formAction);
            $("#form-edit-employee #first-name").val(firstName);
            $("#form-edit-employee #last-name").val(lastName);
        }
    </script>
</body>

</html>
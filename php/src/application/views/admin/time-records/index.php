<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timely - Time Records</title>

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
                <h4>Time Records</h4>
                <a href="<?php echo base_url('admin/time-records/scan-qr') ?>" class="btn btn-primary">
                    <span>Scan QR</span>
                </a>
            </div>
            <table id="time-records-table" class='display dataTable w-100'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Date added</th>
                        <th>Time in</th>
                        <th>TIme out</th>
                    </tr>
                </thead>
            </table>
        </div>
    </main>

    <?php $this->load->view('admin/template/includes/js'); ?>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#time-records-table', {
                processing: true,
                serverSide: true,
                serverMethod: 'POST',
                ajax: {
                    url: '<?= base_url('index.php/admin/time-records/list') ?>',
                    data: {
                        page: "all"
                    },
                },
                columns: [{
                        data: "id",
                        render: (data, type, row, meta) => {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'first_name',
                        render: (data, type, row) => {
                            return `${row.first_name} ${row.last_name}`;
                        }
                    },
                    {
                        data: 'date_added',
                        render: (data) => {
                            return moment(data, "YYYY-MM-DD").format("MMM DD, YYYY");
                        }
                    },
                    {
                        data: 'time_in',
                        render: (data) => {
                            if (data) {
                                return moment(data, "HH:mm:ss").format("hh:mm A");
                            }
                            return "";
                        }
                    },
                    {
                        data: 'time_out',
                        render: (data) => {
                            if (data) {
                                return moment(data, "HH:mm:ss").format("hh:mm A");
                            }
                            return "";
                        }
                    }
                ]
            });
        });
    </script>
</body>

</html>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timely - Scan QR</title>

    <?php $this->load->view('admin/template/includes/css'); ?>
    <style>
        :fullscreen,
        ::backdrop {
            background-color: white;
        }
    </style>
</head>

<body>
    <?php $this->load->view('admin/template/includes/svg'); ?>
    <main>
        <?php $this->load->view('admin/template/sidebar'); ?>
        <div class="b-example-divider"></div>
        <div class="container mt-5" id="fullscreen-container">
            <div>
                <h4>QR Scanner</h4>
                <h6 class="text-muted">Please scan a QR code using your phone and show it to the webcam.</h6>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" onclick="onClickFullscreen()">
                        <span>Fullscreen</span>
                        <svg class="bi ms-2" width="16" height="16" role="img">
                            <use xlink:href="#fullscreen" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-4">
                    <div class="position-relative">
                        <div class="position-absolute" style="top: 45%; left: 43%;">
                            <div class="spinner-border text-primary" style="display: none;" id="qr-scanner-loader" role="status"></div>
                        </div>
                        <video id="qr-scanner" width="390" height="250"></video>
                    </div>
                    <div class="spinner-border text-primary" style="display: none;" id="messages-loader" role="status"></div>
                    <div id="error-message-container" style="display: none;">
                        <div class="alert alert-danger d-flex align-items-center" style="width: 94%;" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                <use xlink:href="#exclamation-triangle-fill" />
                            </svg>
                            <div id="error-message"></div>
                        </div>
                    </div>
                    <div id="success-message-container" style="display: none;">
                        <div class="alert alert-success d-flex align-items-center" style="width: 94%;" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                <use xlink:href="#check-circle-fill" />
                            </svg>
                            <div id="success-message"></div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="border bg-secondary text-white p-3 text-center">
                        <div>
                            <h1><strong id="current-time"></strong></h1>
                            <div class="d-flex align-items-center justify-content-center">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#calendar1" />
                                </svg>
                                <h6 id="current-date" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <table id="time-records-table" class='display dataTable w-100'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Time in</th>
                                    <th>TIme out</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('admin/template/includes/js'); ?>
    <script src="<?php base_url(); ?>/assets/js/instascan.min.js"></script>
    <script type="text/javascript">
        $(document).ready(() => {
            $('#qr-scanner-loader').show();

            dateToday();

            setInterval(() => {
                dateToday();
            }, 1000);

            let table = new DataTable('#time-records-table', {
                processing: true,
                serverSide: true,
                serverMethod: 'POST',
                ajax: {
                    url: '<?= base_url('index.php/admin/time-records/list') ?>',
                    data: {
                        page: "scan-qr",
                        current_date: moment().format("YYYY-MM-DD")
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

        let scanner = new Instascan.Scanner({
            video: document.getElementById('qr-scanner')
        });
        scanner.addListener('scan', (content) => {
            saveQR(content);
        });
        Instascan.Camera.getCameras().then((cameras) => {
            $('#qr-scanner-loader').hide();
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch((e) => {
            $('#qr-scanner-loader').hide();
            console.error(e);
        });

        const saveQR = (value) => {
            $('#messages-loader').show();
            $.ajax({
                url: '<?= base_url('index.php/admin/time-records/scan-qr') ?>',
                type: "post",
                data: {
                    qr_value: value,
                    current_datetime: moment().format("YYYY-MM-DD HH:mm:ss")
                },
                success: (resp) => {
                    const response = JSON.parse(resp);

                    let inOut = "in";
                    let time = response.data.time_in;

                    if (response.data.time_out) {
                        inOut = "out";
                        time = response.data.time_out;
                    };

                    time = moment(time, "HH:mm:ss").format("hh:mm A");

                    $('#messages-loader').hide();
                    $('#success-message-container').show();
                    $('#success-message-container #success-message').html(`<strong>Your time ${inOut}:</strong> ${time}`);

                    $('#time-records-table').DataTable().ajax.reload();

                    setTimeout(function() {
                        $("#success-message-container").hide();
                        $('#success-message-container #success-message').html("");
                    }, 4000);
                },
                error: (data) => {
                    $('#messages-loader').hide();
                    $('#error-message-container').show();
                    $('#error-message-container #error-message').html(data.responseJSON.message);

                    setTimeout(function() {
                        $("#error-message-container").hide();
                        $('#error-message-container #error-message').html("");
                    }, 4000);
                }
            });
        }

        const dateToday = () => {
            const timeNow = moment().format("hh:mm:ss A");
            const dateNow = moment().format("dddd, MMM DD, YYYY");
            $('#current-time').html(timeNow);
            $('#current-date').html(dateNow);
        }

        const onClickFullscreen = () => {
            const element = document.querySelector("#fullscreen-container");
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else {
                return;
            }
        }
    </script>
</body>

</html>
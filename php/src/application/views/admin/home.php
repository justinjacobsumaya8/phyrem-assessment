<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timely - Home</title>

    <?php $this->load->view('admin/template/includes/css'); ?>
</head>

<body>
    <?php $this->load->view('admin/template/includes/svg'); ?>
    <main>
        <?php $this->load->view('admin/template/sidebar'); ?>

        <div class="b-example-divider"></div>

        <div class="container p-5">
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column gap-2 text-center">
                    <h3><span id="greeting">Good morning</span>, <?php echo $this->session->userdata("auth_user")->user_name ?></h3>
                    <div>
                        <h5><span id="date-today"></span></h5>
                    </div>
                </div>
            </div>
            <div class="fixed">
                <div class="lottie-center">
                    <lottie-player src="<?php echo base_url() ?>/assets/lottie/welcome.json" background="transparent" speed="1" style="width: 600px; height: 600px;" loop autoplay></lottie-player>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('admin/template/includes/js'); ?>
    <script>
        $(document).ready(() => {
            dateToday();
            greet();

            setInterval(() => {
                dateToday();
            }, 1000);
        });

        function dateToday() {
            const now = moment().format("dddd, MMMM Do, YYYY, h:mm:ss A");
            $('#date-today').html(now);
        }

        function greet() {
            let message = "";
            const thehours = new Date().getHours();
            const morning = ('Good morning');
            const afternoon = ('Good afternoon');
            const evening = ('Good evening');

            if (thehours >= 0 && thehours < 12) {
                message = morning;

            } else if (thehours >= 12 && thehours < 17) {
                message = afternoon;

            } else if (thehours >= 17 && thehours < 24) {
                message = evening;
            }

            $('#greeting').html(message);
        }
    </script>
</body>

</html>
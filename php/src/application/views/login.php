<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>

	<link rel="stylesheet" href="<?php base_url(); ?>/assets/css/bootstrap.min.css">
</head>

<body>
	<div class="content">
		<div class="vh-100 d-flex justify-content-center align-items-center">
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="col-12 col-md-8 col-lg-6">
						<div>
							<?php if (isset($error)) echo $error; ?>
						</div>
						<div class="card bg-white">
							<div class="card-body p-5">
								<?php echo form_open('login'); ?>
								<h2 class="fw-bold mb-2">Welcome</h2>
								<p class="mb-5">Please enter your login and password</p>
								<div class="mb-3">
									<label for="username" class="form-label ">Username</label>
									<input type="text" class="form-control" id="username" name="username" value="<?php if (isset($username_value)) echo $username_value; ?>" placeholder="jane.doe" required>
								</div>
								<div class="mb-3">
									<label for="password" class="form-label">Password</label>
									<input type="password" class="form-control" id="password" name="password" placeholder="*******" required>
								</div>
								<p class="small">
									<a class="text-primary" href="forget-password.html">Forgot password?</a>
								</p>
								<div class="d-grid">
									<button type="submit" class="btn btn-primary">Login</button>
								</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>
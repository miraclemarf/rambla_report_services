<style>
	.btnlog {
		width: 100%;
		height: 30px;
		background: #e71d2d;
		border: none;
		outline: none;
		cursor: pointer;
		font-size: 0.5em;
		font-weight: 600;
		color: #fff;
	}

	.btnlog:hover {
		background-color: #000;
		opacity: 0.8;
		color: white;
	}

	.content-wrapper {
		background-color: white;
	}

	.form-control,
	.select2-container--default .select2-selection--single,
	.select2-container--default .select2-selection--single .select2-search__field,
	.typeahead,
	.tt-query,
	.tt-hint {
		border: 1px solid #c3dafb;
	}

	.auth form .auth-form-btn {
		height: 45px;
		line-height: 1.5;
	}

	/* Small devices (landscape phones, 576px and up) */
	@media (min-width: 400px) {
		.form-login {
			margin-top: 10px;
		}

		.txt-login {
			text-align: center;
		}

		.brand-logo {
			text-align: center;
		}

		.brand-logo img {
			width: 10%;
		}
	}

	/* Medium devices (tablets, 768px and up) */
	@media (min-width: 768px) {
		.form-login {
			margin-top: 100px;
		}

		.txt-login {
			text-align: left;
		}

		.brand-logo img {
			width: 15%;
		}

		.brand-logo {
			text-align: left;
		}
	}

	/* Large devices (desktops, 992px and up) */
	@media (min-width: 992px) {
		.form-login {
			margin-top: 100px;
		}

		.txt-login {
			text-align: left;
		}

		.brand-logo img {
			width: 35%;
		}

		.brand-logo {
			text-align: left;
		}
	}

	/* Extra large devices (large desktops, 1200px and up) */
	@media (min-width: 1200px) {
		.form-login {
			margin-top: 170px;
		}

		.txt-login {
			text-align: left;
		}

		.brand-logo img {
			width: 35%;
		}

		.brand-logo {
			text-align: left;
		}
	}
</style>
<div class="container-fluid page-body-wrapper full-page-wrapper">
	<div class="content-wrapper d-flex align-items-center auth p-0">
		<div class="col-md-12">
			<div class="row h-100">
				<div class="col-md-6 p-0">
					<div class="col-lg-8 p-0 mx-auto form-login" style="">
						<div class="auth-form-light text-left py-3 px-4 px-sm-5">
							<div class="brand-logo" style="margin-bottom: 10px;">
								<img src="<?= base_url('assets/images/splash4.png'); ?>" alt="logo">
							</div>
							<!-- <h4 class="text-center">Masuk ke akun Anda</h4> -->
							<h5 class="font-weight-light mb-4 txt-login">Masuk ke akun Anda.</h5>
							<form class="pt-3" action="" method="post">
								<?php if (validation_errors()) : ?>
									<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<!-- <strong>Warning!</strong>
									<hr> -->
										<p><?= validation_errors(); ?></p>
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
								<?php endif; ?>
								<div class="form-group">
									<input type="text" name="username" class="form-control form-control-sm focus" placeholder="Username">
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control form-control-sm" placeholder="password">
								</div>
								<div class="mt-3">
									<button type="submit" class="btn btn-block btnlog btn-sm font-weight-medium auth-form-btn">Log in</button>
								</div>
								<div class="my-3 d-flex justify-content-center align-items-center">
									<!-- <div class="form-check">
									<label class="form-check-label text-muted">
										<input type="checkbox" class="form-check-input">
										Keep me signed in
									</label>
								</div> -->
									<a href="#" class="auth-link text-black">Forgot password?</a>
								</div>
								<!-- <div class="mb-2">
								<button type="button" class="btn btn-block btn-facebook auth-form-btn">
									<i class="typcn typcn-social-facebook-circular mr-2"></i>Connect using facebook
								</button>
							</div> -->
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-6 p-0 d-none d-md-flex bg-images; display: flex !important">
					<img src="<?= base_url('assets/images'); ?>/login.png" alt="" style="width:100%; height:auto; background-attachment: fixed;background-size: cover; background-repeat: no-repeat;">
					<!-- <img src="<?= base_url('assets/images'); ?>/bottomimageweb-01.jpg" alt="" style="width:100%; height:100%; background-attachment: fixed; background-repeat: no-repeat;"> -->
				</div>
			</div>
		</div>
		<!-- content-wrapper ends -->
	</div>
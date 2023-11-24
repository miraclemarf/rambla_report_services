<div class="container-fluid page-body-wrapper full-page-wrapper">
	<div class="content-wrapper d-flex align-items-center auth px-0">
		<div class="row w-100 mx-0">
			<div class="col-lg-4 mx-auto">
				<div class="auth-form-light text-left py-3 px-4 px-sm-5">
					<div class="brand-logo text-center" style="margin-bottom: 10px;;">
						<img src="<?= base_url('assets/ico/logorpg.png'); ?>" alt="logo" style="width:35%">
					</div>
					<h4 class="text-center">Naughty & Les Femmes</h4>
					<!-- <h6 class="font-weight-light">Sign in to continue.</h6> -->
					<form class="pt-3" action="" method="post">
						<?php if (validation_errors()) : ?>
							<div class="alert alert-warning alert-dismissible fade show" role="alert">
								<!-- <strong>Warning!</strong>
								<hr> -->
								<p><?= validation_errors(); ?></p>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<input type="text" name="fullname" class="form-control form-control-lg" placeholder="Masukkan Nama Lengkap" value="<?= set_value('fullname'); ?>">
						</div>
						<div class="form-group">
							<input type="text" class="form-control form-control-lg" name="phone" placeholder="Masukkan Nomor Handphone" value="<?= set_value('phone'); ?>">
						</div>
						<div class="form-group">
							<input type="text" class="form-control form-control-lg" name="email" placeholder="Email Anda" value="<?= set_value('email'); ?>">
						</div>
						<div class="form-group">
							<input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan Password">
						</div>
						<div class="form-group">
							<input type="password" name="passconf" class="form-control form-control-lg" placeholder="Ulangi Password">
						</div>
						<div class="mt-3">
							<button type="submit" class="btn btn-block btn-facebook btn-lg font-weight-medium auth-form-btn">Regist</button>
						</div>
						<!-- <div class="my-3 d-flex justify-content-center align-items-center"> -->
						<!-- <div class="form-check">
								<label class="form-check-label text-muted">
									<input type="checkbox" class="form-check-input">
									Keep me signed in
								</label>
							</div> -->
						<!-- <a href="#" class="auth-link text-black">Forgot password?</a>
						</div> -->
						<!-- <div class="mb-2">
							<button type="button" class="btn btn-block btn-facebook auth-form-btn">
								<i class="typcn typcn-social-facebook-circular mr-2"></i>Connect using facebook
							</button>
						</div> -->
						<div class="text-center mt-4 font-weight-light">
							Sudah mempunyai akun ? <a href="<?= base_url('Login'); ?>" class="text-primary">Login</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- content-wrapper ends -->
</div>
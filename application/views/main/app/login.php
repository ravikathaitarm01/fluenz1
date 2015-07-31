<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 */
$data['_this'] = array(
	'page-class' => 'login',
	'page-body-class' => 'bg-primary',
	'page-no-menu' => true,
	'page-no-sidebar' => true,
	'page-js' => array(
		'main/js/app/login.js',
	)
);
?>
<div class="cover" style="background-image: url('<?=$Url::asset_path('main/img/cover1.jpg')?>')"></div>
<div class="overlay bg-primary"></div>
<div class="center-wrapper">
	<div class="center-content">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<section class="panel bg-white no-b">
					<ul class="switcher-dash-action">
						<li class="active"><a href="#" class="selected">Sign in</a></li>
						<li><a href="<?=$Url::base('register')?>" class="">Register account</a></li>
					</ul>
					<div class="p15">
						<form class="login-form parsley-form" method="post" action="" data-parsley-validate>
							<input type="text" name="username" class="form-control input-lg mb25" placeholder="Username" required autofocus>
							<input type="password" name="password" class="form-control input-lg mb25" placeholder="Password" required>
							<div class="form-group show">
								<div class="col-sm-12">
									<div class="checkbox">
										<!--<label>
											<input type="checkbox" name="remember">Remember me
										</label>
										-->
										<div class="pull-right">
											<a href="<?=$Url::base('admin/user/recovery')?>">Forgot password?</a>
										</div>
									</div>

								</div>
							</div>

							<button class="btn btn-primary btn-lg btn-block" type="submit">Sign in</button>
						</form>
					</div>
				</section>
				<p class="text-center">
					Copyright &copy;
					<span id="year" class="mr5"></span>
					<span>Fluenz</span>
				</p>
			</div>
		</div>

	</div>
</div>
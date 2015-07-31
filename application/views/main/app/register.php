<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'register',
	'page-body-class' => 'bg-info',
	'page-no-menu' => true,
	'page-no-sidebar' => true,
	'page-css' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css'
	),
	'page-js' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
		'main/js/app/register.js',
	)
);

$show = $MongoDoc::get($data, 'extra.type', 'influencer');
?>
<div class="cover" style="background-image: url('<?=$Url::asset_path('backend/img/cover2.jpg')?>')"></div>
<div class="overlay bg-info"></div>
<div class="center-wrapper">
	<div class="center-content">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<section class="panel bg-white no-b">
					<ul class="switcher-dash-action">
						<li><a href="<?=$Url::base('auth')?>" class="selected">Sign in</a></li>
						<li class="active"><a href="<?=$Url::base('register')?>" class="">Register account</a></li>
					</ul>
					<div class="p15" style="min-height:700px">
						<div class="col-md-12">
							<div class="box-tab">
								<div class="tab-content">
									<div class="tab-pane <?=$show == 'influencer'? 'fade in active': 'fade'?>" id="x-register-influencer">
										<h4 class="text-primary bolder text-center">I'm an Influencer!</h4>
										<form class="register-form parsley-form" method="post" action="" data-parsley-validate>
											<input type="hidden" name="user" value="influencer">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label>Name</label>
														<input type="text" class="form-control input-lg" name="name" placeholder="Your name" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Email</label>
														<input type="email" class="form-control input-lg" name="email" placeholder="Your email address" required data-parseley-type="email" data-parsley-trigger="change">
													</div>
												</div>

												<div class="col-sm-6">
													<div class="form-group">
														<label>Contact</label>
														<input type="text" class="form-control input-lg" name="phone" placeholder="Your contact number" required data-parseley-type="number" data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>City</label>
														<select name="city" class="form-control input-lg" style="line-height: inherit;-webkit-appearance: menulist;" required data-parsley-trigger="change">
															<option value="new delhi">New Delhi</option>
														</select>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Username</label>
														<input type="text" class="form-control input-lg" name="username" data-parsley-minlength="6" placeholder="" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Password</label>
														<input type="password" class="form-control input-lg" name="password" data-parsley-minlength="6" placeholder="" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-12">
													<div class="form-group">
														<label style="display: block">Genre Tags</label>
														<input id="x-genre-tags" name="genre" type="text" class="form-control input-lg">
													</div>
												</div>
											</div>


											<div class="row text-center">
												<div class="col-xs-12">
													<div class="form-group submit-group">
														<button type="submit" class="btn btn-info btn-lg btn-block">Register</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="tab-pane <?=$show == 'brand'? 'fade in active': 'fade'?>" id="x-register-brand">
										<h4 class="text-primary bolder text-center">I'm a Brand!</h4>
										<form class="register-form parsley-form" method="post" action="" data-parsley-validate>
											<input type="hidden" name="user" value="brand">
											<?php if ($p = $MongoDoc::get($data, 'extra.partner')): ?>
												<input type="hidden" name="partner" value="<?=$p['_id']?>">
												<h4 class="text-center">Partner : <?=$p['name']?></h4>
											<?php endif ?>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<label>Brand Name</label>
														<input type="text" class="form-control input-lg" name="brand_name" data-parsley-minlength="5" placeholder="Brand Name" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-12">
													<div class="form-group">
														<label>Package <a href="#" title="Package descriptions"><i class="fa fa-info-circle text-primary"></i></a></label>
														<select name="package" class="form-control input-lg" style="line-height: inherit;-webkit-appearance: menulist;" required data-parsley-trigger="change">
															<option value="basic">Basic Outreach</option>
															<option value="standard">Standard Outreach</option>
															<option value="premium">Premium Outreach</option>
															<option value="custom">Custom Outreach</option>
														</select>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Registrant Name</label>
														<input type="text" class="form-control input-lg" name="name" placeholder="Your name" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Contact Number</label>
														<input type="text" class="form-control input-lg" name="phone" placeholder="Your contact number" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Contact Email</label>
														<input type="email" class="form-control input-lg" name="email" placeholder="Your email address" required data-parseley-type="email" data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Referral Code</label>
														<input type="text" class="form-control input-lg" name="referral" placeholder="Referral code" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Username</label>
														<input type="text" class="form-control input-lg" name="username" data-parsley-minlength="6" placeholder="" required data-parsley-trigger="change">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>Password</label>
														<input type="password" class="form-control input-lg" name="password" data-parsley-minlength="6" placeholder="" required data-parsley-trigger="change">
													</div>
												</div>
											</div>

											<div class="row text-center">
												<div class="col-xs-12">
													<div class="form-group submit-group">
														<button type="submit" class="btn btn-info btn-lg btn-block">Register</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
								<ul class="switcher-dash-action">
									<li class="<?=$show == 'influencer'? 'active': ''?>"><a href="#x-register-influencer" data-toggle="tab">Influencer</a></li>
									<li class="<?=$show == 'brand'? 'active': ''?>"><a href="#x-register-brand" data-toggle="tab">Brand</a></li>
								</ul>
							</div>
						</div>
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
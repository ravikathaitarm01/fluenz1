<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'brand-profile',
	'page-css' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css'
	),
	'page-js' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
		'main/js/app/brand/profile.js'
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<?php if ($data['manager']) :?>
				<div class="col-sm-12">
					<section class="panel panel-default">
						<header class="panel-heading">
							<h5>Manager Profile</h5>
						</header>
						<div class="panel-body">
							<form id="x-form-manager" class="parsley-form" action="" role="form" method="post" data-parsley-validate>
								<input type="hidden" name="action"  value="update-manager">
								<input type="hidden" name="id"  value="<?=$data['manager']['_id']?>">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Name</label>
										<input type="text" class="form-control input-lg" name="name"  value="<?=$data['manager']['name']?>" required data-parsley-trigger="change">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Email</label>
										<input type="email" class="form-control input-lg" name="email"  value="<?=$data['manager']['email']?>" required data-parseley-type="email" data-parsley-trigger="change">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Username</label>
										<input type="text" class="form-control input-lg" name="username"  value="<?=$data['manager']['username']?>" data-parsley-minlength="6" placeholder="" readonly aria-readonly="aria-readonly" data-parsley-trigger="change">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Password</label>
										<input type="password" class="form-control input-lg" name="password" data-parsley-minlength="6" placeholder="" data-parsley-trigger="change">
									</div>
								</div>
								<div class="col-sm-12"><hr /></div>
								<div  class="col-sm-12 mt25">
									<button id="x-form-update-manager" type="button" class="btn btn-outline btn-warning" value="update">Update</button>
								</div>
							</form>
						</div>
					</section>
				</div>
			<?php endif ?>
			<div class="col-sm-12">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Profile</h5>
					</header>
					<div class="panel-body">
						<form id="x-form-user" class="parsley-form" action="" role="form" method="post" data-parsley-validate>
							<input type="hidden" name="id"  value="<?=$data['user']['_id']?>">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Brand Name</label>
									<input type="text" class="form-control input-lg" value="<?=$data['user']['brand_name']?>" data-parsley-minlength="6" placeholder="" readonly aria-readonly="aria-readonly" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Name</label>
									<input type="text" class="form-control input-lg" name="name"  value="<?=$data['user']['name']?>" required data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control input-lg" name="email"  value="<?=$data['user']['email']?>" required data-parseley-type="email" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Username</label>
									<input type="text" class="form-control input-lg" name="username"  value="<?=$data['user']['username']?>" data-parsley-minlength="6" placeholder="" readonly aria-readonly="aria-readonly" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control input-lg" name="password" data-parsley-minlength="6" placeholder="" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Package</label>
									<?php if ($UserSession::get('main_user.type') == 'admin'): ?>
										<select name="package" class="form-control input-lg" style="line-height: inherit;-webkit-appearance: menulist;" required data-parsley-trigger="change">
											<option value="basic" <?=$data['user']['package'] == 'basic'? 'selected':''?>>Basic Outreach</option>
											<option value="standard" <?=$data['user']['package'] == 'standard'? 'selected':''?>>Standard Outreach</option>
											<option value="premium" <?=$data['user']['package'] == 'premium'? 'selected':''?>>Premium Outreach</option>
											<option value="custom" <?=$data['user']['package'] == 'custom'? 'selected':''?>>Custom Outreach</option>
										</select>
									<?php else: ?>
										<input  name="package" type="text" class="form-control input-lg" readonly value="<?=ucwords($data['user']['package']['name'])?>">
									<?php endif ?>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Contact</label>
									<input type="text" class="form-control input-lg" name="phone" value="<?=$data['user']['phone']?>"required data-parseley-type="number" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-12"><hr /></div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>About</label>
									<textarea name="about" class="form-control input-lg" rows="3" data-parsley-trigger="change"><?=$MongoDoc::get($data, 'user.about', '')?></textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Address</label>
									<textarea name="address" class="form-control input-lg" rows="3" data-parsley-trigger="change"><?=$MongoDoc::get($data, 'user.address', '')?></textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Website</label>
									<input type="text" class="form-control input-lg" name="url" value="<?=$MongoDoc::get($data, 'user.url', '')?>" required data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Timezone</label>
									<select name="timezone" class="form-control input-lg" style="line-height: inherit;-webkit-appearance: menulist;" data-parsley-trigger="change">
										<?php foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::ALL) as $tz): ?>
											<option <?=$MongoDoc::get($data, 'user.timezone', TIMEZONE) === $tz? 'selected': ''?> value="<?=$tz?>"><?=(new \DateTime('now', new \DateTimeZone($tz)))->format('P')?> - <?=$tz?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Logo</label>
									<input id="x-picture-url" type="url" class="form-control input-lg" name="logo"  value="<?=$MongoDoc::get($data, 'user.logo', '')?>" data-parsley-trigger="change">
								</div>
								<div class="fluid">
									<img src="<?=$MongoDoc::get($data, 'user.logo', '')?>" style="width:200px;height:200px">
								</div>
							</div>
							<?php if (in_array($UserSession::get('main_user.type'), array('admin', 'partner'))): ?>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Brand River</label><br />
										<input type="checkbox" class="input-toggle" name="social_river" value="1" <?=$MongoDoc::get($data, 'social_river.enabled')?'checked':''?> data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger" />
									</div>
								</div>
							<?php endif ?>
							<div class="col-sm-12"><hr /></div>
							<div  class="col-sm-12 mt25">
								<button id="x-form-update-user" type="button" class="btn btn-outline btn-warning" value="update">Update</button>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>

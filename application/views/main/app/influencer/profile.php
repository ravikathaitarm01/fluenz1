<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'influencer-profile',
	'page-css' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
		'main/plugins/datepicker/datepicker.css'
	),
	'page-js' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
		'main/plugins/datepicker/bootstrap-datepicker.js',
		'main/js/app/influencer/profile.js'
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-sm-12">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Profile</h5>
					</header>
					<div class="panel-body">
						<form id="x-form-user" class="parsley-form" action="" role="form" method="post" data-parsley-validate>
							<input type="hidden" name="id"  value="<?=$data['user']['_id']?>">
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
									<label>Contact</label>
									<input type="text" class="form-control input-lg" name="phone" value="<?=$data['user']['phone']?>"required data-parseley-type="number" data-parsley-trigger="change">
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
									<input type="text" class="form-control input-lg" name="username"  value="<?=$data['user']['username']?>" data-parsley-minlength="6" placeholder="" readonly aria-readonly="aria-readonly" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control input-lg" name="password" data-parsley-minlength="6" placeholder="" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label style="display: block">Genre Tags</label>
									<input id="x-genre-tags" name="genre" type="text" class="form-control input-lg" value="<?=implode(',', $data['user']['genre'])?>">
								</div>
							</div>
							<div class="col-sm-12"><hr /></div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>About</label>
									<textarea name="about" class="form-control input-lg" rows="3" placeholder="A brief description about the brand" data-parsley-trigger="change"><?=$MongoDoc::get($data, 'user.about', '')?></textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Address</label>
									<textarea name="address" class="form-control input-lg" rows="3" placeholder="A brief description about the brand" data-parsley-trigger="change"><?=$MongoDoc::get($data, 'user.address', '')?></textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Date of Birth</label>
									<input type="text" class="form-control input-lg datepicker" name="date_of_birth"  value="<?=$MongoDoc::get($data, 'user.date_of_birth', '')?>" data-parsley-trigger="change"
										data-date-end-date="-5y" data-date-format="yyyy-mm-dd" data-date-start-view="decade" style="margin-top: 0">
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
									<label>Picture</label>
									<input id="x-picture-url" type="url" class="form-control input-lg" name="picture"  value="<?=$MongoDoc::get($data, 'user.picture', '')?>" data-parsley-trigger="change">
								</div>
								<div class="fluid">
									<img src="<?=$MongoDoc::get($data, 'user.picture', '')?>" style="width:200px;height:200px">
								</div>
							</div>
							<div class="col-sm-12"><hr /></div>
							<div class="col-sm-12">
								<h4>I am here for</h4>
								<div class="checkbox">
									<label class="col-sm-3">
										<input name="interest[]" type="checkbox" value="digital-pr" <?=in_array('digital-pr', $MongoDoc::get($data, 'user.interest', array()))? 'checked': ''?>> Digital PR
									</label>
									<label class="col-sm-3">
										<input name="interest[]" type="checkbox" value="ad-serving" <?=in_array('ad-serving', $MongoDoc::get($data, 'user.interest', array()))? 'checked': ''?>> Ad Serving
									</label>
								</div>
							</div>

							<div class="col-sm-12"><hr /></div>
							<div  class="col-sm-12 mt25">
								<button id="x-form-update-user" type="button" class="btn btn-outline btn-warning" value="update">Update</button>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>

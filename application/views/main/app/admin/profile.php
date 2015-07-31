<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'admin-profile',
	'page-js' => array(
		'main/js/app/admin/profile.js'
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-sm-12">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Users</h5>
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
							<?php if ($UserSession::get('main_user.superadmin')): ?>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Super Admin</label><br />
									<input type="checkbox" name="superadmin" value="1" <?=$MongoDoc::get($data, 'user.superadmin', false)? 'checked':''?> data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />
								</div>
							</div>
							<?php endif; ?>
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
							<div class="col-sm-12"><hr /></div>
							<div  class="col-sm-12 mt25">
								<button id="x-form-update-user" type="button" class="btn btn-outline btn-warning" value="update">Update</button>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>

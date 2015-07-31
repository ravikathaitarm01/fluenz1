<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'partner-profile',
	'page-css' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
		'main/plugins/datepicker/datepicker.css'
	),
	'page-js' => array(
		'main/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
		'main/plugins/datepicker/bootstrap-datepicker.js',
		'main/js/app/partner/profile.js'
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
									<label>Contact</label>
									<input type="text" class="form-control input-lg" name="phone" value="<?=$data['user']['phone']?>"required data-parseley-type="number" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Contact</label>
									<input type="text" class="form-control input-lg" name="phone" value="<?=$data['user']['phone']?>" required data-parseley-type="number" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Company Name</label>
									<input type="text" class="form-control input-lg" name="company_name"  value="<?=$data['user']['company_name']?>" required data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Company Address</label>
									<textarea name="company_address" class="form-control input-lg" rows="3" required data-parsley-trigger="change"><?=$data['user']['company_address']?></textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Company Website</label>
									<input type="text" class="form-control input-lg" name="company_url" value="<?=$data['user']['company_url']?>" required data-parsley-trigger="change">
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

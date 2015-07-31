<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'admin-partner',
	'page-js' => array(
		'main/js/app/admin/partner.js',
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-sm-12">
					<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Create</h5>
					</header>
					<div class="panel-body">
						<form class="parsley-form" action="" role="form" method="post" data-parsley-validate>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Name</label>
									<input type="text" class="form-control input-lg" name="name"  value="" required data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control input-lg" name="email"  value="" required data-parseley-type="email" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Username</label>
									<input type="text" class="form-control input-lg" name="username"  required value="" data-parsley-minlength="6" placeholder="" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control input-lg" name="password" required data-parsley-minlength="6" placeholder="" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Contact</label>
									<input type="text" class="form-control input-lg" name="phone" value=""required data-parseley-type="number" data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Company Name</label>
									<input type="text" class="form-control input-lg" name="company_name"  value="" required data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Company Address</label>
									<textarea name="company_address" class="form-control input-lg" rows="3" required data-parsley-trigger="change"></textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Company Website</label>
									<input type="text" class="form-control input-lg" name="company_url" value="" required data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-12"><hr /></div>
							<div  class="col-sm-12 mt25">
								<button id="x-form-create-partner" type="submit" class="btn btn-outline btn-primary" name="action" value="create">Create</button>
							</div>
						</form>
					</div>
				</section>
			</div>
			<div class="col-sm-12">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Partners</h5>
					</header>
					<div class="panel-body">
						<div class="table-responsive no-border">
							<table class="table table-bordered table-striped mg-t datatable" data-url="">
								<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Username</th>
									<th>Created</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($data['partners'] as $a): ?>
									<tr class="x-influencer-id-<?=$a['_id']?>">
										<td><?=$a['name']?></td>
										<td><?=$a['email']?></td>
										<td><?=$a['username']?></td>
										<td><?=$Time::str($a['_id']->getTimestamp(), $UserSession::get('user.timestamp'), 'j F Y, H:i')?></td>
										<td data-search="">
											<div class="col-sm-9">
												<div class="mr15">
													<form method="post" action="<?=$Url::base('admin/partner')?>">
														<input type="hidden" name="id" value="<?=$a['_id']?>">
														<button class="btn btn-default btn-outline x-partner-login" data-url="<?=$Url::base('auth')?>" data-id="<?=$a['_id']?>">
															<i class="fa fa-sign-in" title="Login"></i>
														</button>
														<?php if ($UserSession::get('user.type') == 'admin') :?>
														<button type="submit" class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-trash"></i></button>
														<?php endif; ?>
													</form>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="mr15">
													<form method="post" action="<?=$Url::base('admin/partner')?>">
														<input type="hidden" name="action" value="activation" />
														<input type="hidden" name="id" value="<?=$a['_id']?>" />
														<input type="checkbox" name="active" value="1" class="x-partner-activation" <?=$a['active']?'checked':''?> data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" />
													</form>
												</div>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</section>
			</div>
		</div>

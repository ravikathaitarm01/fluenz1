<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'admin-account',
	'page-js' => array(
		'main/plugins/switchery/switchery.js',
		'main/js/admin/accounts.js',
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-sm-12">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Accounts</h5>
					</header>
					<div class="panel-body">
						<div class="table-responsive no-border">
							<table class="table table-bordered table-striped mg-t datatable" data-url="">
								<thead>
								<tr>
									<th>Name</th>
									<th>Package</th>
									<th>Created</th>
									<th>Owner</th>
									<th>Status</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($data['accounts'] as $a): ?>
									<tr class="x-account-id-<?=$a['_id']?>">
										<td><?=$a['name']?></td>
										<td><?=ucfirst($a['package'])?></td>
										<td><?=date('j F Y, H:i', $a['_id']->getTimestamp())?></td>
										<td><?=$a['owner']['email']?></td>
										<td>
											<div class="col-sm-3">
												<div class="mr15">
													<form method="post" action="<?=$Url::base('account/update')?>">
														<input type="hidden" name="action" value="account-activation" />
														<input type="hidden" name="_id" value="<?=$a['_id']?>" />
														<input type="checkbox" name="active" value="1" class="x-account-activation" <?=$a['active']?'checked':''?> data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" />
													</form>
												</div>
											</div>
											<div class="col-sm-2">
												<button class="btn btn-primary btn-outline x-account-select"
														data-url="<?=$Url::base('account')?>"
														data-id="<?=$a['_id']?>"><span class="fa fa-edit"></span> Details</button>
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
			<div class="col-sm-6">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Account Details</h5>
					</header>
					<div class="panel-body">
						<div class="table-responsive no-border">
							<form id="x-form-account" class="parsley-form animated bounceInLeft" action="" role="form" method="post" data-parsley-validate>
								<input type="hidden" name="_id" value="">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Name</label>
										<input type="text" class="form-control input-lg" name="name" placeholder="Account name" required data-parsley-trigger="change" disabled>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Package</label>
										<select name="package" class="form-control input-lg" style="line-height: inherit;-webkit-appearance: menulist;" required data-parsley-trigger="change" disabled>
											<option value="">Please select...</option>
											<?php foreach ($data['packages'] as $r): ?>
												<option value="<?=$r['name']?>"><?=ucfirst($r['name'])?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Owner</label>
										<select name="owner_id" class="form-control input-lg" style="line-height: inherit;-webkit-appearance: menulist;" required data-parsley-trigger="change" disabled>
											<option value="">Please select...</option>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group" style="min-height: 72px;">
										<label>Active</label>
										<div>
											<input type="checkbox" name="active" value="1" data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" disabled />
										</div>
									</div>
								</div>
								<div class="col-sm-12"><hr /></div>
								<div id="x-form-update-user-buttons" class="col-sm-12 mt25">
									<button type="button" class="btn btn-outline btn-warning" value="update"
											data-url="<?=$Url::base('account/update')?>"
											data-confirm-action="Proceed to update this account's details?"
											disabled>Update</button>
									<?php if ($UserSession::get('user.role.auth') <= 9): ?>
										<button type="button" class="btn btn-outline btn-danger" value="remove"
												data-url="<?=$Url::base('account/remove')?>"
												data-confirm-action="Proceed to remove this account? This action is permanent."
												disabled>Remove</button>
									<?php endif; ?>
								</div>
						</div>
					</div>
				</section>
			</div>
		</div>

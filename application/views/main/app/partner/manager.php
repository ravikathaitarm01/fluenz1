<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'partner-manager',
	'page-js' => array(
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
						<h5>Managers</h5>
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
								<?php foreach ($data['managers'] as $a): ?>
									<tr class="x-influencer-id-<?=$a['_id']?>">
										<td><?=$a['name']?></td>
										<td><?=$a['email']?></td>
										<td><?=$a['username']?></td>
										<td><?=$Time::str($a['_id']->getTimestamp(), $UserSession::get('user.timestamp'), 'j F Y, H:i')?></td>
										<td data-search="">
											<div class="col-sm-12">
												<div class="mr15">
													<form method="post" action="<?=$Url::base('partner/manager')?>">
														<input type="hidden" name="id" value="<?=$a['_id']?>">
														<button class="btn btn-default btn-outline x-partner-login" data-url="<?=$Url::base('auth')?>" data-id="<?=$a['_id']?>">
															<i class="fa fa-sign-in" title="Login"></i>
														</button>
														<button type="submit" class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-trash"></i></button>
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

<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'admin-brand',
	'page-js' => array(
		'main/js/app/admin/brand.js',
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
				<div class="col-sm-2">
					<div class="col-sm-12">
						<strong style="margin-left:-10px; margin-top: 5px">Status</strong>
						<div class="checkbox">
							<label><input id="x-filter-status-active" type="checkbox" checked>Active</label>
						</div>
						<div class="checkbox">
							<label><input id="x-filter-status-inactive" type="checkbox" checked>Inactive</label>
						</div>
					</div>
					<div class="col-sm-12 mt10">
						<strong style="margin-left:-10px;">Package</strong>
						<div class="checkbox">
							<label><input class="x-filter-package" type="checkbox" checked value="basic">Basic</label>
						</div>
						<div class="checkbox">
							<label><input class="x-filter-package" type="checkbox" checked value="standard">Standard</label>
						</div>
						<div class="checkbox">
							<label><input class="x-filter-package" type="checkbox" checked value="premium">Premium</label>
						</div>
						<div class="checkbox">
							<label><input class="x-filter-package" type="checkbox" checked value="custom">Custom</label>
						</div>
					</div>
				</div>
				<div class="col-sm-10">
					<div class="table-responsive no-border">
						<table class="table table-bordered table-striped mg-t datatable" data-url="">
							<thead>
							<tr>
								<th>Brand</th>
								<th>Contact</th>
								<th>Package</th>
								<th>Phone</th>
								<th>Created</th>
								<th>Status</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['brands'] as $a): ?>
								<tr class="x-brand-id-<?=$a['_id']?>">
									<td data-search="<?=$a['brand_name']?>">
										<div class="col-sm-3 mt5">
											<button class="btn btn-default btn-outline btn-xs x-brand-login" data-url="<?=$Url::base('auth')?>" data-id="<?=$a['_id']?>">
												<i class="fa fa-sign-in" title="Login"></i>
											</button>
										</div>
										<div class="col-sm-9">
											<div class="col-sm-12">
												<img style="max-height:75px" src="<?=$MongoDoc::get($a, 'logo', $Url::asset_path('main/img/faceless.jpg'))?>" />
											</div>
											<div class="col-sm-12">
												<a href="<?=$Url::base('brand/view/'.$a['_id'])?>"><?=$a['brand_name']?></a>
											</div>
										</div>
									</td>

									<td><?=$a['name']?></td>
									<td data-search="<?=$a['package']?>"><?=ucfirst($a['package'])?></td>
									<td data-ssearch="<?=$a['phone']?> <?=$a['email']?>">
										<?=$a['phone']?><br />
										<?=$a['email']?>
									</td>
									<td><?=$Time::str($a['_id']->getTimestamp(), $UserSession::get('user.timestamp'), 'j F Y, H:i')?></td>
									<td data-search="<?=$a['active']?'active':'inactive'?>">
										<div class="col-sm-3">
											<div class="mr15">
												<form method="post" action="<?=$Url::base('admin/brand')?>">
													<input type="hidden" name="action" value="activation" />
													<input type="hidden" name="id" value="<?=$a['_id']?>" />
													<input type="checkbox" name="active" value="1" class="x-brand-activation" <?=$a['active']?'checked':''?> data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" />
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
			</div>
		</section>
	</div>
</div>

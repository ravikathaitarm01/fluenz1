<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'admin-influencer',
	'page-js' => array(
		'main/js/app/admin/influencer.js',
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
						<strong style="margin-left:-10px;">Score</strong>
						<div class="checkbox">
							<label><input class="x-filter-score" type="checkbox" checked value="0-20">0 - 	20</label>
						</div>
						<div class="checkbox">
							<label><input class="x-filter-score" type="checkbox" checked value="20-50">20 - 50</label>
						</div>
						<div class="checkbox">
							<label><input class="x-filter-score" type="checkbox" checked value="50-80">50 - 80</label>
						</div>
						<div class="checkbox">
							<label><input class="x-filter-score" type="checkbox" checked value="80-100">80 - 100</label>
						</div>
					</div>
				</div>
				<div class="col-sm-10">
					<div class="table-responsive no-border">
						<table class="table table-bordered table-striped mg-t datatable" data-url="">
							<thead>
							<tr>
								<th>Name</th>
								<th>Contact</th>
								<th>City</th>
								<th>Genre</th>
								<th>Created</th>
								<th>Score</th>
								<th>Status</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['influencers'] as $a): ?>
								<tr class="x-influencer-id-<?=$a['_id']?>">
									<td data-search="<?=$a['name']?> <?=$a['username']?>">
										<div class="col-sm-2 mt15">
											<button class="btn btn-default btn-outline btn-xs x-influencer-login" data-url="<?=$Url::base('auth')?>" data-id="<?=$a['_id']?>">
												<i class="fa fa-sign-in" title="Login"></i>
											</button>
										</div>
										<div class="col-sm-10">
											<div class="col-sm-12">
												<img style="max-height:75px" src="<?=$MongoDoc::get($a, 'picture', $Url::asset_path('main/img/faceless.jpg'))?>" />
											</div>
											<div class="col-sm-12">
												<a href="<?=$Url::base('influencer/view/'.$a['_id'])?>"><?=$a['name']?></a><br />
												<small><?=$a['username']?></small> <br />
											</div>
										</div>
									</td>
									<td data-search="<?=$a['phone']?> <?=$a['email']?> ">
										<?=$a['phone']?><br />
										<?=$a['email']?>
									</td>
									<td data-search="<?=$a['city']?>"><?=ucwords($a['city'])?></td>
									<td data-search="<?=implode(',', $a['genre'])?>">
										<?php foreach ($a['genre'] as $g): ?>
											<span class="label label-default"><?=$g?></span>
										<?php endforeach ?>
									</td>
									<td><?=$Time::str($a['_id']->getTimestamp(), $UserSession::get('user.timestamp'), 'j F Y, H:i')?></td>
									<td><?=$MongoDoc::get($a, 'score', 0)?></td>
									<td data-search="<?=$a['active']?'active':'inactive'?>">
										<div class="col-sm-3">
											<div class="mr15">
												<form method="post" action="<?=$Url::base('admin/influencer')?>">
													<input type="hidden" name="action" value="activation" />
													<input type="hidden" name="id" value="<?=$a['_id']?>" />
													<input type="checkbox" name="active" value="1" class="x-influencer-activation" <?=$a['active']?'checked':''?> data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" />
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

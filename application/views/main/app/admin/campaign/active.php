<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'admin-campaign-pending',
	'page-js' => array(
		'main/js/app/admin/campaign/pending.js',
	)
);
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-sm-12 text-center">
		<div class="col-sm-12 text-center">
			<section class="mb25">
				<div class="btn-group btn-group-justified">
					<a href="<?=$Url::base('admin/campaign/all')?>" class="btn btn-success btn-rounded" role="button">All</a>
					<a href="<?=$Url::base('admin/campaign/pending')?>" class="btn btn-color bolder" role="button">Pending</a>
					<a href="<?=$Url::base('admin/campaign/active')?>" class="btn btn-default" role="button">Active</a>
					<a href="<?=$Url::base('admin/campaign/rejected')?>" class="btn btn-danger btn-rounded" role="button">Rejected</a>
				</div>
			</section>
		</div>
	</div>
	<div class="col-sm-12">
		<section class="panel panel-default">
			<header class="panel-heading">
				<h5>Campaigns</h5>
			</header>
			<div class="panel-body">
				<div class="col-sm-12">
					<div class="table-responsive no-border">
						<table class="table table-bordered table-striped mg-t datatable" data-url="">
							<thead>
							<tr>
								<th>Brand</th>
								<th>Name</th>
								<th>Duration</th>
								<th>&nbsp;</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['campaigns'] as $c): ?>
								<tr class="x-campaign-id-<?=$c['_id']?>">
									<td data-search="<?=$c['brand']['username']?>">
										<a href="<?=$Url::base('brand/view/'.$c['brand']['_id'])?>"><?=$c['brand']['username']?></a>
									</td>
									<td data-search="<?=$c['title']?> <?=str_replace('-', ' ', $c['type'])?>">
										<div style="font-size:20px">
											<a href="<?=$Url::base('admin/campaign/view/'.$c['_id'])?>"><?=$c['title']?></a><br />
											<small class="label label-info"><?=ucwords(str_replace('-', ' ', $c['type']))?></small> <br />
										</div>
									</td>
									<td data-search=""><?=$c['duration']['start']['date']?> - <?=$c['duration']['end']['date']?></td>
									<td data-search="">
										<button class="btn btn-danger x-campaign-remove" title="Remove" data-id="<?=$c['_id']?>" data-url="<?=$Url::base('brand/campaign/remove')?>"><i class="fa fa-remove"></i></button>
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

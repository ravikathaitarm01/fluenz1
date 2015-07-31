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
		'main/js/app/admin/campaign/index.js',
	)
);
function __get_state_label($state)
{
	switch ($state)
	{
		case 'pending':
			return 'default';
		case 'active':
			return 'success';
		case 'completed':
			return 'primary';
		case 'rejected':
			return 'danger';
		default:
			return '';
	}
}
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-sm-12 text-center">
		<div class="col-sm-12 text-center">
			<section class="mb25">
				<div class="btn-group btn-group-justified">
					<a href="<?=$Url::base('admin/campaign/all')?>" class="btn btn-default btn-rounded  x-btn-all" role="button">All</a>
					<a href="<?=$Url::base('admin/campaign/pending')?>" class="btn btn-default x-btn-pending" role="button">Pending</a>
					<a href="<?=$Url::base('admin/campaign/active')?>" class="btn btn-default" role="button">Active</a>
					<a href="<?=$Url::base('admin/campaign/rejected')?>" class="btn btn-default btn-rounded" role="button">Rejected</a>
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
											<small class="label label-info"><?=ucwords(str_replace('-', ' ', $c['type']))?></small>
											<small class="label label-info"><?=ucwords(str_replace('-', ' ', $c['subtype']))?></small> <br />
										</div>
									</td>
									<td data-search="<?=$c['type']?> <?=$c['subtype']?>">
										<div class="mb5"><?=$c['duration']['start']['date']?> - <?=$c['duration']['end']['date']?></div>
										<span style="font-size:15px" class="label label-<?=__get_state_label($c['state'])?>">
											<?=ucwords($c['state'])?>
										</span>
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



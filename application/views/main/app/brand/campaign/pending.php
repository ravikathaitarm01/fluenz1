<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'brand-campaign-pending',
	'page-js' => array(
		'main/js/app/brand/campaign/pending.js',
	)
);
?>
<!-- inner content wrapper -->
<div class="wrapper">
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
								<th>Name</th>
								<th>Duration</th>
								<th>&nbsp;</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['campaigns'] as $c): ?>
								<tr class="x-campaign-id-<?=$c['_id']?>">
									<td data-search="<?=$c['title']?> <?=str_replace('-', ' ', $c['type'])?>">
										<div style="font-size:20px">
											<a href="<?=$Url::base('brand/campaign/view/'.$c['_id'])?>"><?=$c['title']?></a><br />
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

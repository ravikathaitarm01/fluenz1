<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'brand-influencer',
	'page-js' => array(
		'main/js/app/brand/influencer.js',
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
								<th>City</th>
								<th>Genre</th>
								<th>Score</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['influencers'] as $a): ?>
								<tr class="x-influencer-id-<?=$a['_id']?>">
									<td data-search="<?=$a['name']?> <?=$a['username']?>">
										<div class="col-sm-12">
											<div class="col-sm-4">
												<img style="max-height:150px" src="<?=$MongoDoc::get($a, 'picture', $Url::asset_path('main/img/faceless.jpg'))?>" />
											</div>
											<div class="col-sm-8" style="font-size:20px">
												<a href="<?=$Url::base('influencer/view/'.$a['_id'])?>"><?=$a['name']?></a><br />
												<small><?=$a['username']?></small> <br />
											</div>
										</div>
									</td>
									<td data-search="<?=$a['city']?>"><?=ucwords($a['city'])?></td>
									<td data-search="<?=implode(',', $a['genre'])?>">
										<?php foreach ($a['genre'] as $g): ?>
											<span class="label label-default"><?=$g?></span>
										<?php endforeach ?>
									</td>
									<td><?=$MongoDoc::get($a, 'score', 0)?></td>
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

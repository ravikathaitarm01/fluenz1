<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'influencer-brand',
	'page-js' => array(
		'main/js/app/influencer/brand.js',
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
				<div class="col-sm-12">
					<div class="table-responsive no-border">
						<table class="table table-bordered table-striped mg-t datatable" data-url="">
							<thead>
							<tr>
								<th>Name</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['brands'] as $a): ?>
								<tr class="x-influencer-id-<?=$a['_id']?>">
									<td data-search="<?=$a['name']?> <?=$a['username']?>">
										<div class="col-sm-12">
											<div class="col-sm-4">
												<a href="<?=$MongoDoc::get($data, 'user.url', '')?>" target="_blank">
													<img style="max-height:150px" src="<?=$MongoDoc::get($a, 'logo', $Url::asset_path('main/img/faceless.jpg'))?>" />
												</a>
											</div>
											<div class="col-sm-8" style="font-size:20px">
												<a href="<?=$Url::base('brand/view/'.$a['_id'])?>"><?=$a['name']?></a><br />
												<small><?=$a['username']?></small> <br />
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

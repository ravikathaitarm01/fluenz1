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
		'main/js/app/brand/favorite.js',
	)
);
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-sm-12">
		<section class="panel panel-default">
			<header class="panel-heading">
				<h5>Favorites</h5>
			</header>
			<div class="panel-body">
				<div class="col-sm-12">
					<div class="table-responsive no-border">
						<table class="table table-bordered table-striped mg-t favorite-datatable" data-url="">
							<thead>
							<tr>
								<th>Name</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['favorites'] as $a): ?>
								<tr>
									<td data-search="<?=$a['name']?> <?=$a['username']?>">
										<div class="col-sm-12">
											<a href="<?=$Url::base('influencer/view/'.$a['_id'])?>"><?=$a['name']?></a><br />
											<small><?=$a['username']?></small> <br />
										</div>
									</td>
									<td data-search="">
										<button class="btn btn-danger x-influencer-unfavorite" title="Unfavorite" data-url=<?=$Url::base('influencer/favorite')?> data-id="<?=$a['_id']?>"><i class="fa fa-star"></i></button>
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
	<div class="col-sm-12">
		<section class="panel panel-default">
			<header class="panel-heading">
				<h5>Lists</h5>
			</header>
			<div class="panel-body">
				<div class="col-sm-3">
					<select id="x-influencer-list" class="form-control" data-url="<?=$Url::base('brand/favorite')?>">
						<option value="" selected>--- Select ---</option>
						<?php foreach ($data['lists'] as $k=>$l): ?>
							<option value="<?=$k?>"><?=$l?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div id="x-influencer-list-content" class="col-sm-9">
				</div>
			</div>
		</section>
	</div>
</div>

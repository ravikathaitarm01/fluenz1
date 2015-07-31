<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\MongoDoc $MongoDoc
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'admin-influencer',
	'page-js' => array(
		'main/js/app/admin/campaign/approve.js',
	)
);
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-sm-6">
		<section class="panel panel-default">
			<header class="panel-heading">
				<h5>Selected Influencers</h5>
			</header>
			<div class="panel-body">
				<div class="col-sm-12">
					<h4><?=$data['campaign']['title']?></h4>
					<form method="post">
						<input type="hidden" name="action" value="finish">
						<input type="hidden" name="id" value="<?=$data['campaign']['_id']?>">
						<input id="x-selected-influencers" type="hidden" name="selected_influencers" value="<?=$data['influencers_select_string']?>">

						<div class="col-sm-4">
							<label>Points
							<input type="text" name="points" value="" required class="form-control"></label>
						</div>
						<div class="col-sm-4">
							<label>Globally Discoverable<br />
							<input type="checkbox" name="global" value="1"></label>
						</div>
						<div class="col-sm-4">
							<button id="x-influencers-finish" class="btn btn-success" type="submit">Finish</button>
						</div>
					</form>
				</div>
				<div class="col-sm-12"><hr /></div>
				<div id="x-selected-influencers-view" class="col-sm-12 mt25">
					<?php foreach ($MongoDoc::get($data['campaign'], 'influencers_select', array()) as $i): ?>
						<div class="col-sm-3 mb5">
							<div class="col-sm-2">
								<button type="button" class="btn btn-danger btn-xs x-btn-influencer-remove" data-id="<?=$i['_id']?>"><i class="fa fa-close"></i></button>
							</div>
							<div class="col-sm-9">
								<a href="<?=$Url::base('influencer/view/'.$i['_id'])?>"><?=$i['username']?></a>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-6">
		<section class="panel panel-default">
			<header class="panel-heading">
				<h5>Influencers</h5>
			</header>
			<div class="panel-body">
				<div class="col-sm-3">
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
				<div class="col-sm-9">
					<div class="table-responsive no-border">
						<table id="x-influencers-list" class="table table-bordered table-striped mg-t datatable" data-url="">
							<thead>
							<tr>
								<th>&nbsp;</th>
								<th>Name</th>
								<th>City</th>
								<th>Genre</th>
								<th>Score</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data['influencers'] as $a): ?>
								<tr data-id="<?=$a['_id']?>" data-username="<?=$a['username']?>" data-url="<?=$Url::base('influencer/view/'.$a['_id'])?>">
									<td data-search="">
										<div class="text-center">
											<button class="btn btn-primary x-btn-influencer-add" t><i class="fa fa-plus"></i></button>
										</div>
									</td>
									<td data-search="<?=$a['name']?> <?=$a['username']?>">
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

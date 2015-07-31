<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\Time $Time
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'influencer-view',
	'page-js' => array(
		'main/js/app/influencer/view.js'
	)
);
?>
<?php
$invalid = array();
foreach ($MongoDoc::get($data['user'], 'social_invalidated', array()) as $a)
{
	$invalid[$a] = true;
}
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-sm-12">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Influencer</h5>
					</header>
					<div class="panel-body">
						<div class="col-sm-12">
							<div class="col-sm-3">
								<img src="<?=$MongoDoc::get($data, 'user.picture', '')?>" style="max-height:300px;width:auto">
							</div>
							<div class="col-sm-7">
								<div class="col-sm-2 mt20">
									<div class="badge bg-warning" style="font-size:50px"><?=$MongoDoc::get($data, 'user.score', 0)?></div>
								</div>
								<div class="col-sm-10">
									<h3><?=$data['user']['name']?></h3>
									<h4><?=$data['user']['username']?></h4>
									<h5><?=ucwords($MongoDoc::get($data, 'user.city', ''))?></h5>
									<small>Seen <?=$MongoDoc::get($data, 'user.last_login')? ($Time::since($MongoDoc::get($data, 'user.last_login'), $UserSession::get('user.timezone')?:TIMEZONE, 3)) : ''?> ago</small>
								</div>
							</div>
							<div class="col-sm-2">
								<?php if ($UserSession::get('user.type') === 'brand'): ?>
									<form style="display: inline" method="post" action="<?=$Url::base('influencer/favorite')?>">
										<input type="hidden" name="action" value="favorite" />
										<input type="hidden" name="id" value="<?=$data['user']['_id']?>" />
										<button type="button" id="x-influencer-favorite" class="btn btn-primary <?=$data['favorite']?'':'btn-outline'?>" title="<?=$data['favorite']?'Unfavorite':'Favorite'?>">
											<i class="fa fa-star"></i>
										</button>
									</form>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#x-list-add-modal">
										<i class="fa fa-list"></i>
									</button>
								<?php endif ?>
							</div>
							<div class="col-sm-12 mt15">
								<?php foreach ($data['user']['genre'] as $g): ?>
									<span class="label label-primary" style="font-size:15px"><?=$g?></span>
								<?php endforeach ?>
							</div>
							<div class="col-sm-12 mt15">
								<div class="well well-sm">
									<?=$MongoDoc::get($data,'user.about', '')?:$MongoDoc::get($data,'user.social.twitter.details.description', '')?>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="col-sm-12 text-center">

								<?php if ( ! isset($invalid['facebook']) && ($s = $MongoDoc::get($data['user'], 'social.facebook'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="<?=$s['details']['link']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-facebook"></i> Facebook</h5>
										</a>
									</div>
								<?php endif ?>

								<?php if ( ! isset($invalid['twitter']) && ($s = $MongoDoc::get($data['user'], 'social.twitter'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="https://twitter.com/<?=$s['screen_name']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-twitter"></i> Twitter</h5>
										</a>
									</div>
								<?php endif ?>

								<?php if ( ! isset($invalid['instagram']) && ($s = $MongoDoc::get($data['user'], 'social.instagram'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="https://instagram.com/<?=$s['username']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-instagram"></i> Instagram</h5>
										</a>
									</div>
								<?php endif ?>

								<?php if ( ! isset($invalid['google-youtube']) && ($s = $MongoDoc::get($data['user'], 'social.google-youtube'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="https://www.youtube.com/channel/<?=$s['id']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-youtube"></i> YouTube</h5>
										</a>
									</div>
								<?php endif ?>

								<?php if ( ! isset($invalid['google-analytics']) && ($s = $MongoDoc::get($data['user'], 'social.google-analytics'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="<?=$s['websiteUrl']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-cloud"></i> Blog</h5>
										</a>
									</div>
								<?php endif ?>

								<?php if ( ! isset($invalid['google-plus']) && ($s = $MongoDoc::get($data['user'], 'social.google-plus'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="<?=$s['url']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-google-plus"></i> Google+</h5>
										</a>
									</div>
								<?php endif ?>

								<?php if ( ! isset($invalid['vine']) && ($s = $MongoDoc::get($data['user'], 'social.vine'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="<?=$s['shareUrl']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-vine"></i> Vine</h5>
										</a>
									</div>
								<?php endif ?>

								<?php if ($s = $MongoDoc::get($data['user'], 'social.klout')): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="https://klout.com/user/<?=$s['nick']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-bar-chart"></i> Klout</h5>
										</a>
									</div>
								<?php endif ?>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="x-list-add-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Influencer To List</h4>
			</div>
			<div class="modal-body">
				<div class="row-fluid">
					<form style="display: inline" method="post" action="<?=$Url::base('influencer/lists')?>">
						<input type="hidden" name="action" value="add" />
						<input type="hidden" name="id" value="<?=$data['user']['_id']?>" />
						<div class="form-group col-sm-4">
							<select class="form-control" name="list">
								<option value="">--- Select ---</option>
								<?php foreach ($data['lists'] as $k=>$list): ?>
									<option value="<?=$k?>"><?=$list['name']?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="col-sm-2 text-center">Or</div>
						<div class="form-group col-sm-4">
							<input type="text" name="new_list" class="form-control" placeholder="Create New" />
						</div>
						<button id="x-influencer-list-add" type="button" class="btn btn-primary col-sm-offset-1">
							<i class="fa fa-floppy-o"></i>
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
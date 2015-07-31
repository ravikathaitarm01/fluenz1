<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\Time $Time
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'brand-view',
	'page-js' => array(
		'main/js/app/brand/view.js'
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
						<h5>Brand</h5>
					</header>
					<div class="panel-body">
						<div class="col-sm-12">
							<div class="col-sm-3">
								<img src="<?=$MongoDoc::get($data, 'user.logo', '')?>" style="max-height:300px;width:auto">
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12">
									<h3><a href="<?=$MongoDoc::get($data, 'user.url', '')?>" target="_blank"><?=$data['user']['name']?></a></h3>
									<h4><?=$data['user']['username']?></h4>
									<small>Seen <?=$MongoDoc::get($data, 'user.last_login')? ($Time::since($MongoDoc::get($data, 'user.last_login'), $UserSession::get('user.timezone')?:TIMEZONE, 3)) : ''?> ago</small>
								</div>
							</div>
							<div class="col-sm-2">
								<?php if ($UserSession::get('user.type') === 'influencer'): ?>
									<form style="display: inline" method="post" action="<?=$Url::base('brand/favorite')?>">
										<input type="hidden" name="action" value="favorite" />
										<input type="hidden" name="id" value="<?=$data['user']['_id']?>" />
										<button type="button" id="x-brand-favorite" class="btn btn-primary <?=$data['favorite']?'':'btn-outline'?>" title="<?=$data['favorite']?'Unfavorite':'Favorite'?>">
											<i class="fa fa-star"></i>
										</button>
									</form>
								<?php endif ?>
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

								<?php if ( ! isset($invalid['google-plus']) && ($s = $MongoDoc::get($data['user'], 'social.google-plus'))): ?>
									<div class="row-fluid" style="display: inline-block;">
										<a href="<?=$s['url']?>" class="btn btn-info btn-outline" target="_blank">
											<h5 class="mt0 mb0"><i class="fa fa-google-plus"></i> Google+</h5>
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
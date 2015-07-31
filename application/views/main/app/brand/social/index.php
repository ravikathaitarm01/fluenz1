<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Output $Output
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'brand-social',
	'page-css' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
	),
	'page-js' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.js',
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
	)
);
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-md-4">
		<section class="panel panel-default social-item">
			<header class="panel-heading">
				<div class="pull-right">
					<form class="col-sm-3"  method="post" action="<?=$Url::base('brand/social/facebook')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('brand/social/facebook')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Facebook</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['brand'], 'social.facebook')): ?>
					<?php if (in_array('facebook', $MongoDoc::get($data['brand'], 'social_invalidated', array()))): ?>
						<div class="row-fluid">
							<div class="col-sm-12 text-danger text-center">
								<h1><i class="fa fa-unlink"></i></h1>
								<h4>Account has been invalidated. Please re-link</h4>
							</div>
						</div>
					<?php else: ?>
					<div class="row-fluid">
						<div class="col-sm-12">
							<a href="<?=$s['details']['link']?>" class="pull-left mr15" target="_blank">
								<img src="<?=$s['details']['picture']['url']?>" class="avatar avatar-md img-rounded" alt="" style="100px;">
							</a>
							<div class="overflow-hidden">
								<b><?=$s['name']?></b>
								<small class="show"><?=$s['details']['category']?></small>
								<div class="show social-item-description"><?=$s['details']['about']?></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['details']['likes'])?></b></h2>
								<small>Likes</small>
							</div>
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=round($s['insights']['engaged_users']/7, 2)?></b></h2>
								<small>Weekly Engaged Users</small>
							</div>
						</div>
					</div>
					<?php endif ?>
				<?php endif ?>
			</div>
		</section>
	</div>
	<div class="col-md-4">
		<section class="panel panel-default social-item">
			<header class="panel-heading">
				<div class="pull-right">
					<form class="col-sm-3"  method="post" action="<?=$Url::base('brand/social/twitter')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('brand/social/twitter')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Twitter</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['brand'], 'social.twitter')): ?>
					<?php if (in_array('twitter', $MongoDoc::get($data['brand'], 'social_invalidated', array()))): ?>
						<div class="row-fluid">
							<div class="col-sm-12 text-danger text-center">
								<h1><i class="fa fa-unlink"></i></h1>
								<h4>Account has been invalidated. Please re-link</h4>
							</div>
						</div>
					<?php else: ?>
					<div class="row-fluid">
						<div class="col-sm-12">
							<a href="https://twitter.com/<?=$s['screen_name']?>" class="pull-left mr15" target="_blank">
								<img src="<?=$s['details']['profile_image_url_https']?>" class="avatar avatar-md img-rounded" alt="" style="100px;">
							</a>
							<div class="overflow-hidden">
								<b>@<?=$s['screen_name']?></b>
								<small class="show"><?=$s['details']['name']?></small>
								<div class="show social-item-description"><?=$s['details']['description']?></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-4 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['details']['statuses_count'])?></b></h2>
								<small>Tweets</small>
							</div>
							<div class="col-xs-12 col-sm-4 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['details']['followers_count'])?></b></h2>
								<small>Followers</small>
							</div>
							<div class="col-xs-12 col-sm-4 text-center">
								<h2 class="mb0">
									<b>
										<?php
											$t = strtotime($s['details']['created_at']);
											$days = floor((time() - $t)/(3600*24));
											echo round($days? ($s['details']['statuses_count'] / $days) : 0, 2);
										?>
									</b></h2>
								<small>Tweets/Day</small>
							</div>
						</div>
					</div>
					<?php endif ?>
				<?php endif ?>
			</div>
		</section>
	</div>
	<div class="col-md-4">
		<section class="panel panel-default social-item">
			<header class="panel-heading">
				<div class="pull-right">
					<form class="col-sm-3"  method="post" action="<?=$Url::base('brand/social/instagram')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('brand/social/instagram')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Instagram</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['brand'], 'social.instagram')): ?>
					<?php if (in_array('instagram', $MongoDoc::get($data['brand'], 'social_invalidated', array()))): ?>
						<div class="row-fluid">
							<div class="col-sm-12 text-danger text-center">
								<h1><i class="fa fa-unlink"></i></h1>
								<h4>Account has been invalidated. Please re-link</h4>
							</div>
						</div>
					<?php else: ?>
					<div class="row-fluid">
						<div class="col-sm-12">
							<a href="https://instagram.com/<?=$s['username']?>" class="pull-left mr15" target="_blank">
								<img src="<?=$s['profile_picture']?>" class="avatar avatar-md img-rounded" alt="" style="100px;">
							</a>
							<div class="overflow-hidden">
								<b><?=$s['username']?></b>
								<small class="show"><?=$s['full_name']?></small>
								<div class="show social-item-description"><?=$s['bio']?></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['counts']['media'])?></b></h2>
								<small>Posts</small>
							</div>
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['counts']['followed_by'])?></b></h2>
								<small>Followers</small>
							</div>
						</div>
					</div>
					<?php endif ?>
				<?php endif ?>
			</div>
		</section>
	</div>
	<div class="col-md-4">
		<section class="panel panel-default social-item">
			<header class="panel-heading">
				<div class="pull-right">
					<form class="col-sm-3"  method="post" action="<?=$Url::base('brand/social/youtube')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('brand/social/youtube')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Youtube</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['brand'], 'social.google-youtube')): ?>
					<?php if (in_array('google-youtube', $MongoDoc::get($data['brand'], 'social_invalidated', array()))): ?>
						<div class="row-fluid">
							<div class="col-sm-12 text-danger text-center">
								<h1><i class="fa fa-unlink"></i></h1>
								<h4>Account has been invalidated. Please re-link</h4>
							</div>
						</div>
					<?php else: ?>
					<div class="row-fluid">
						<div class="col-sm-12">
							<a href="https://www.youtube.com/channel/<?=$s['id']?>" class="pull-left mr15" target="_blank">
								<img src="<?=$s['snippet']['thumbnails']['default']['url']?>" class="avatar avatar-md img-rounded" alt="" style="100px;">
							</a>
							<div class="overflow-hidden">
								<b><?=$s['snippet']['title']?></b>
								<small class="show"><?=$s['snippet']['title']?></small>
								<div class="show social-item-description"><?=$s['snippet']['description']?></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-3 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['statistics']['viewCount'])?></b></h2>
								<small>Views</small>
							</div>
							<div class="col-xs-12 col-sm-3 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['statistics']['subscriberCount'])?></b></h2>
								<small>Subscribers</small>
							</div>
							<div class="col-xs-12 col-sm-3 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['statistics']['videoCount'])?></b></h2>
								<small>Videos</small>
							</div>
							<div class="col-xs-12 col-sm-3 text-center">
								<h2 class="mb0"><b><?=$s['statistics']['videoCount']? round($s['statistics']['viewCount']/$s['statistics']['videoCount'], 2):0?></b></h2>
								<small>Views/Video</small>
							</div>
						</div>
					</div>
					<?php endif ?>
				<?php endif ?>
			</div>
		</section>
	</div>
	<div class="col-md-4">
		<section class="panel panel-default social-item">
			<header class="panel-heading">
				<div class="pull-right">
					<form class="col-sm-3"  method="post" action="<?=$Url::base('brand/social/gplus')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('brand/social/gplus')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Google+</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['brand'], 'social.google-plus')): ?>
					<?php if (in_array('google-plus', $MongoDoc::get($data['brand'], 'social_invalidated', array()))): ?>
						<div class="row-fluid">
							<div class="col-sm-12 text-danger text-center">
								<h1><i class="fa fa-unlink"></i></h1>
								<h4>Account has been invalidated. Please re-link</h4>
							</div>
						</div>
					<?php else: ?>
					<div class="row-fluid">
						<div class="col-sm-12">
							<a href="<?=$s['url']?>" class="pull-left mr15" target="_blank">
								<img src="<?=$s['image']['url']?>" class="avatar avatar-md img-rounded" alt="" style="100px;">
							</a>
							<div class="overflow-hidden">
								<b><?=$s['displayName']?></b>
								<small class="show"><?=$MongoDoc::get($s, 'tagline', '')?></small>
								<div class="show social-item-description"><?=$MongoDoc::get($s, 'aboutMe', '')?></div>
							</div>
						</div>
					</div>
					<?php endif ?>
				<?php endif ?>
			</div>
		</section>
	</div>
</div>

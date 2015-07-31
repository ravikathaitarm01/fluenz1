<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Output $Output
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'influencer-home',
	'page-css' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
	),
	'page-js' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.js',
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
	)
);

function _stat($n)
{
	$r = '';
	if ($n)
	{
		$r = sprintf('<i class="fa fa-arrow-%s text-%s" title="%s%%"></i> ', $n>0?'up':'down', $n>0?'success':'danger', round($n,5));
	}
	return $r;
}
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-md-4">
		<section class="panel panel-default social-item">
			<header class="panel-heading">
				<div class="pull-right">
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/facebook')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/facebook')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Facebook</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.facebook')): ?>
					<?php if (in_array('facebook', $MongoDoc::get($data['influencer'], 'social_invalidated', array()))): ?>
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
								<small><?=_stat($MongoDoc::get($data['statistics'], 'facebook.details.likes'))?>Likes</small>
							</div>
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=round($s['insights']['engaged_users']/7, 2)?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'facebook.insights.engaged_users'))?>Weekly Engaged Users</small>
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
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/twitter')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/twitter')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Twitter</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.twitter')): ?>
					<?php if (in_array('twitter', $MongoDoc::get($data['influencer'], 'social_invalidated', array()))): ?>
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
								<small><?=_stat($MongoDoc::get($data['statistics'], 'twitter.details.statuses_count'))?>Tweets</small>
							</div>
							<div class="col-xs-12 col-sm-4 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['details']['followers_count'])?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'twitter.details.followers_count'))?>Followers</small>
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
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/instagram')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/instagram')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Instagram</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.instagram')): ?>
					<?php if (in_array('instagram', $MongoDoc::get($data['influencer'], 'social_invalidated', array()))): ?>
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
								<small><?=_stat($MongoDoc::get($data['statistics'], 'instagram.counts.media'))?>Posts</small>
							</div>
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['counts']['followed_by'])?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'instagram.counts.followed_by'))?>Followers</small>
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
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/youtube')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/youtube')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Youtube</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.google-youtube')): ?>
					<?php if (in_array('google-youtube', $MongoDoc::get($data['influencer'], 'social_invalidated', array()))): ?>
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
								<small><?=_stat($MongoDoc::get($data['statistics'], 'google-youtube.statistics.subscriberCount'))?>Subscribers</small>
							</div>
							<div class="col-xs-12 col-sm-3 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['statistics']['videoCount'])?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'google-youtube.statistics.videoCount'))?>Videos</small>
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
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/analytics')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/analytics')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Blog/Analytics</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.google-analytics')): ?>
					<?php if (in_array('google-analytics', $MongoDoc::get($data['influencer'], 'social_invalidated', array()))): ?>
						<div class="row-fluid">
							<div class="col-sm-12 text-danger text-center">
								<h1><i class="fa fa-unlink"></i></h1>
								<h4>Account has been invalidated. Please re-link</h4>
							</div>
						</div>
					<?php else: ?>
					<div class="row-fluid">
						<div class="col-sm-12">
							<div class="overflow-hidden">
								<b><?=$s['name']?></b>
								<div class="show">
									<a href="<?=$s['websiteUrl']?>" class="pull-left mr15" target="_blank">
										<?=$s['websiteUrl']?>
									</a>
									&nbsp;
								</div>
								<div class="show social-item-description">&nbsp;</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['ga_data']['ga:sessions'])?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'google-analytics.ga_data.ga:sessions'))?>Views</small>
							</div>
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=round($s['ga_data']['ga:avgSessionDuration'], 2)?>s</b></h2>
								<small>Avg. Session Duration</small>
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
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/gplus')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/gplus')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Google+</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.google-plus')): ?>
					<?php if (in_array('google-plus', $MongoDoc::get($data['influencer'], 'social_invalidated', array()))): ?>
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
	<div class="col-md-4">
		<section class="panel panel-default social-item">
			<header class="panel-heading">
				<div class="pull-right">
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/vine')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/vine')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Vine</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.vine')): ?>
					<?php if (in_array('vine', $MongoDoc::get($data['influencer'], 'social_invalidated', array()))): ?>
						<div class="row-fluid">
							<div class="col-sm-12 text-danger text-center">
								<h1><i class="fa fa-unlink"></i></h1>
								<h4>Account has been invalidated. Please re-link</h4>
							</div>
						</div>
					<?php else: ?>
					<div class="row-fluid">
						<div class="col-sm-12">
							<a href="<?=$s['shareUrl']?>" class="pull-left mr15" target="_blank">
								<img src="<?=$s['avatarUrl']?>" class="avatar avatar-md img-rounded" alt="" style="100px;">
							</a>
							<div class="overflow-hidden">
								<b><?=$s['username']?></b>
								<div class="show"><?=$MongoDoc::get($s, 'description', '')?></div>
								<div class="show social-item-description">&nbsp;</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['followerCount'])?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'vine.followerCount'))?>Followers</small>
							</div>
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$Output::number($s['postCount'])?></b></h2>
								<small>Posts</small>
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
					<form class="col-sm-3"  method="post" action="<?=$Url::base('influencer/social/klout')?>">
						<button class="btn btn-danger" name="action" value="remove" title="Remove"><i class="fa fa-remove"></i></button>
					</form>
					<form class="col-sm-4" method="post" action="<?=$Url::base('influencer/social/klout')?>">
						<button class="btn btn-primary" name="action" value="attach"><i class="fa fa-link"></i> Link</button>
					</form>
				</div>
				<h5>Klout</h5>
			</header>
			<div class="panel-body">
				<?php if ($s = $MongoDoc::get($data['influencer'], 'social.klout')): ?>
					<div class="row-fluid">
						<div class="col-sm-6">
							<div class="col-xs-12 col-sm-12 text-center">
								<a href="https://klout.com/user/<?=$s['nick']?>" class="" target="_blank">
									<b style="font-size: 40px;line-height: 100px"><?=$s['nick']?></b>
								</a>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="col-xs-12 col-sm-12 text-center">
								<h2 style="font-size: 70px" class="mb0"><b><?=round($s['score']['score'], 2)?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'klout.score.score'))?>Score</small>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</section>
	</div>
</div>

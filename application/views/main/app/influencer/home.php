<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
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
		'main/js/app/influencer/home.js',
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

/**
 * @param $MongoDoc app\helpers\MongoDoc
 * @param $data
 * @param $key
 * @param $inval_key
 * @return null
 */
function _social_allowed($MongoDoc, $data, $key, $inval_key)
{
	if ( ! in_array($inval_key, $MongoDoc::get($data, 'social_invalidated', array())))
	{
		return $MongoDoc::get($data, $key);
	}
	return null;
}
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-md-12">
		<!-- profile information sidebar -->
		<div class="panel overflow-hidden no-b profile p15">
			<div class="row mb25 bb">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-4 mt10">
							<h4 class="mb0"><b><?=$UserSession::get('user.name')?></b></h4>
							<small><?=$UserSession::get('user.username')?></small><br />
							<h6>Last Login: <?=$UserSession::get('user.last_login')? ($Time::str($UserSession::get('user.last_login'), $UserSession::get('user.timezone')?:TIMEZONE, 'jS F Y H:i:s T')) : ''?></h6>
						</div>
						<div class="col-xs-8 text-center">
							<div class="col-xs-12 col-sm-3">
								<h2 class="mb0"><b>-</b></h2>
								<small>Level</small>
							</div>
							<div class="col-xs-12 col-sm-3">
								<h2 class="mb0"><b>0</b></h2>
								<small>Points</small>
							</div>
							<div class="col-xs-12 col-sm-3">
								<h2 class="mb0"><b><?=$MongoDoc::get($data['influencer'], 'score', 0)?></b></h2>
								<small>Fluenz Score</small>
							</div>
							<div class="col-xs-12 col-sm-3">
								<h2 class="mb0">
									<?php
									$r = 0;
									foreach (array(

												'facebook.details.links',
												'twitter.details.followers_count',
												'instagram.counts.followed_by',
												'google-youtube.statistics.subscriberCount',
												'google-analytics.ga_data.ga:sessions',
												'vine.followerCount',
										) as $k)
									{
										$r += $MongoDoc::get($data['influencer'], 'social.'.$k, 0);
									}
									?>
									<b><?=round($r/1000, 2)?>k</b>
								</h2>
								<small>Reach</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- /profile information sidebar -->
	</div>
	<div class="row-fluid">
		<?php if ($s = _social_allowed($MongoDoc, $data['influencer'], 'social.facebook', 'facebook')): ?>
		<div class="col-md-3">
			<section class="panel panel-default">
				<div class="panel-body">
					<div class="row-fluid">
						<h2>Facebook</h2>
						<div class="col-sm-12">
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=$s['details']['likes']?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'facebook.details.likes'))?>Likes</small>
							</div>
							<div class="col-xs-12 col-sm-6 text-center">
								<h2 class="mb0"><b><?=round($s['insights']['engaged_users']/7, 2)?></b></h2>
								<small><?=_stat($MongoDoc::get($data['statistics'], 'facebook.insights.engaged_users'))?>Engaged Users</small>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php endif ?>
		<?php if ($s = _social_allowed($MongoDoc, $data['influencer'], 'social.twitter', 'twitter')): ?>
			<div class="col-md-3">
				<section class="panel panel-default">
					<div class="panel-body">
						<div class="row-fluid">
							<h2>Twitter</h2>
							<div class="col-sm-12">
								<div class="col-xs-12 col-sm-6 text-center">
									<h2 class="mb0"><b><?=$s['details']['statuses_count']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'twitter.details.statuses_count'))?>Tweets</small>
								</div>
								<div class="col-xs-12 col-sm-6 text-center">
									<h2 class="mb0"><b><?=$s['details']['followers_count']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'twitter.details.followers_count'))?>Followers</small>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		<?php endif ?>
		<?php if ($s = _social_allowed($MongoDoc, $data['influencer'], 'social.instagram', 'instagram')): ?>
			<div class="col-md-3">
				<section class="panel panel-default">
					<div class="panel-body">
						<div class="row-fluid">
							<h2>Instagram</h2>
							<div class="col-sm-12">
								<div class="col-xs-12 col-sm-6 text-center">
									<h2 class="mb0"><b><?=$s['counts']['media']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'instagram.counts.media'))?>Posts</small>
								</div>
								<div class="col-xs-12 col-sm-6 text-center">
									<h2 class="mb0"><b><?=$s['counts']['followed_by']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'instagram.counts.followed_by'))?>Followers</small>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		<?php endif ?>
		<?php if ($s = _social_allowed($MongoDoc, $data['influencer'], 'social.google-youtube', 'google.youtube')): ?>
			<div class="col-md-3">
				<section class="panel panel-default">
					<div class="panel-body">
						<div class="row-fluid">
							<h2>YouTube</h2>
							<div class="col-sm-12">
								<div class="col-xs-12 col-sm-6 text-center">
									<h2 class="mb0"><b><?=$s['statistics']['subscriberCount']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'google-youtube.statistics.subscriberCount'))?>Subscribers</small>
								</div>
								<div class="col-xs-12 col-sm-6 text-center">
									<h2 class="mb0"><b><?=$s['statistics']['videoCount']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'google-youtube.statistics.videoCount'))?>Videos</small>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		<?php endif ?>
		<?php if ($s = _social_allowed($MongoDoc, $data['influencer'], 'social.google-analytics', 'google.analytics')): ?>
			<div class="col-md-3">
				<section class="panel panel-default">
					<div class="panel-body">
						<div class="row-fluid">
							<h2>Blog</h2>
							<div class="col-sm-12">
								<div class="col-xs-12 col-sm-12 text-center">
									<h2 class="mb0"><b><?=$s['ga_data']['ga:sessions']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'google-analytics.ga_data.ga:sessions'))?>Views</small>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		<?php endif ?>
		<?php if ($s = _social_allowed($MongoDoc, $data['influencer'], 'social.vine', 'vine')): ?>
			<div class="col-md-3">
				<section class="panel panel-default">
					<div class="panel-body">
						<div class="row-fluid">
							<h2>Vine</h2>
							<div class="col-sm-12">
									<div class="col-xs-12 col-sm-12 text-center">
									<h2 class="mb0"><b><?=$s['followerCount']?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'vine.followerCount'))?>Followers</small>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		<?php endif ?>
		<?php if ($s = _social_allowed($MongoDoc, $data['influencer'], 'social.klout', 'klout')): ?>
			<div class="col-md-3">
				<section class="panel panel-default">
					<div class="panel-body">
						<div class="row-fluid">
							<h2>Klout</h2>
							<div class="col-sm-12">
								<div class="col-xs-12 col-sm-12 text-center">
									<h2 class="mb0"><b><?=round($s['score']['score'], 2)?></b></h2>
									<small><?=_stat($MongoDoc::get($data['statistics'], 'klout.score.score'))?>Score</small>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		<?php endif ?>
	</div>
</div>
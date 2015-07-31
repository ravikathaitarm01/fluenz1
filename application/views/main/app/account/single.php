<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 */
$data['_this'] = array(
	'page-class' => 'admin-account',
	'page-js' => array(
		'main/plugins/switchery/switchery.js',
	)
);

$limit_name_map = array(
	'max-users' => 'Users',
	'max-brands' => 'Brands',
	'max-predefined-replies' => 'Predefined Replies',
	'twitter-tracking-keywords' => 'Tracked Keywords',
	'twitter-tracking-mentions' => 'Mentions'
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<section class="panel panel-default">
				<header class="panel-heading">
				<?php if ($UserSession::get('user.role.auth') <= $data['ROLES']['ROLE_POWER_ACCOUNT']): ?>
					<span class="pull-right"  data-toggle="tooltip" data-placement="left" title="" data-original-title="Admin Access">
						<a href="<?=$Url::base('account/admin')?>" class="btn btn-outline btn-color"><i class="fa fa-gears"></i></a>
					</span>
				<?php endif; ?>
					<h5>Account Details</h5>
				</header>
				<div class="panel-body">
					<div class="col-sm-6 center-col bordered mt25 mb25">
						<div class="h1 text-primary">
							<b><?=$data['account']['name']?></b>
						</div>
						<hr />
						<div class="pull-right text-right">
							<span class="label label-<?=$data['account']['active']? 'success':'danger'?>"><?=$data['account']['active']? 'Active':'Inactive'?></span>
							<br />
							<small><?=date('j F Y, H:i', $data['account']['_id']->getTimestamp())?></small>
						</div>
						<div class="h5 col-sm-offset-1">
							<small><?=ucfirst($data['account']['package']['name'])?> Package</small>
						</div>
						<div class="h4 col-sm-offset-1">
							<?=$data['account']['owner']['email']?>
						</div>
					</div>
					<div class="col-sm-12 mt25 mb25">
						<div class="row" style="text-align: center">
						<?php foreach ($data['account']['package']['limits'] as $k => $v): ?>
							<div class="col-sm-2">
								<span class="badge bg-primary"><?=$v?></span> <?=$limit_name_map[$k]?>
							</div>
						<?php endforeach; ?>
						</div>
					</div>

					<div class="col-sm-12 mt25 mb25 bt ml5">
					<?php
					foreach ($data['account']['package']['limits'] as $k => $v):
						$perc = round(100 * $data['account']['limits'][$k] / $v);
					?>
						<div class="col-sm-2 text-center">
							<figure>
								<div class="small mt10"><?=$limit_name_map[$k]?></div>
								<div class="progress progress-xs mt5 mb5">
									<div class="progress-bar done" role="progressbar" aria-valuenow="<?=$perc?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$perc?>%">
									</div>
								</div>
								<small><?=$data['account']['limits'][$k]?> / <?=$v?></small>
							</figure>
						</div>
					<?php endforeach; ?>
					</div>
				</div>
			</section>
		</div>

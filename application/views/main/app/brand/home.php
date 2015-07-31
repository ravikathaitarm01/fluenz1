<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'brand-home',
	'page-css' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
	),
	'page-js' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.js',
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
		'main/js/app/brand/home.js',
	)
);
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
									<small><?=$UserSession::get('user.username')?></small>
									<h6>Last Login: <?=$UserSession::get('user.last_login')? ($Time::str($UserSession::get('user.last_login'), $UserSession::get('user.timezone')?:TIMEZONE, 'jS F Y H:i:s T')) : ''?></h6>
								</div>
								<div class="col-xs-8 text-center">
									<div class="col-xs-12 col-sm-4">
										<h2 class="mb0"><b>0</b></h2>
										<small>Active Campaigns</small>
									</div>
									<div class="col-xs-12 col-sm-4">
										<h2 class="mb0"><b>0</b></h2>
										<small>Total Campaigns</small>
									</div>
									<div class="col-xs-12 col-sm-4">
										<h2 class="mb0"><b>0</b></h2>
										<small>Influencers Engaged</small>
									</div>
									<div class="col-xs-12 col-sm-4">
										<h2 class="mb0"><b>0</b></h2>
										<small>Outreach</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- /profile information sidebar -->
			</div>
		</div>

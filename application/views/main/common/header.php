<?php
/**
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\MongoDoc $MongoDoc
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var array $data
 */

?>
<!doctype html>
<html class="<?=$data['_meta']['page-class']?> no-js" lang="" data-user="<?=$UserSession->get('user._id')?>" data-role="<?=$UserSession->get('user.type')?>">

<head>
	<!-- meta -->
	<meta charset="utf-8">
	<meta name="description" content="flat, clean, responsive, application frontend template built with bootstrap 3">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<!-- /meta -->

	<title><?=$data['_meta']['page-title']?></title>

	<!-- page level plugin styles -->
	<link rel="stylesheet" href="<?=$Url::asset_path('main/plugins/datepicker/datepicker.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/plugins/toastr/toastr.min.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/plugins/bootstrap-toggle/bootstrap-toggle.min.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/plugins/chosen/chosen.min.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/plugins/datatables/jquery.dataTables.css')?>">
	<!--<link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.6/css/dataTables.responsive.css">-->

	<?php foreach ($data['_meta']['page-css'] as $js): ?>
	<link rel="stylesheet" href="<?=$Url::asset_path($js)?>">
	<?php endforeach; ?>
	<!-- /page level plugin styles -->

	<!-- core styles -->
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/spinner.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/bootstrap/css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/font-awesome.min.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/themify-icons.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/animate.min.css')?>">
	<!-- /core styles -->

	<!-- template styles -->
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/skin/palette.css')?>" id="skin">
	<!--<link rel="stylesheet" href="<?=$Url::asset_path('main/css/fonts/font.css')?>" id="font">-->
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/main.css')?>">
	<!-- template styles -->

	<!-- override -->
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/app/main.css')?>">
	<!-- override -->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- load modernizer -->
	<script src="<?=$Url::asset_path('main/plugins/modernizr.js')?>"></script>
</head>

<!-- body -->

<body class="<?=$data['_meta']['page-body-class']?>">

	<div class="app">
	<?php if ( ! $data['_meta']['page-no-menu']): ?>
		<!-- menu navigation -->
		<header class="header header-fixed navbar">

			<div class="brand">
				<!-- toggle offscreen menu -->
				<a href="javascript:;" class="ti-menu off-left visible-xs" data-toggle="offscreen" data-move="ltr"></a>
				<!-- /toggle offscreen menu -->

				<!-- logo -->
				<a href="index.html" class="navbar-brand">
					<img src="<?=$Url::asset_path('main/img/logo.png')?>" alt="">
						<span class="heading-font">
							Fluenz
						</span>
				</a>
				<!-- /logo -->
			</div>

			<ul class="nav navbar-nav">
				<li class="hidden-xs">
					<!-- toggle small menu -->
					<a href="javascript:;" class="toggle-sidebar">
						<i class="ti-menu"></i>
					</a>
					<!-- /toggle small menu -->
				</li>
				<li class="header-search">
					<!-- toggle search -->
					<a href="javascript:;" class="toggle-search">
						<i class="ti-search"></i>
					</a>
					<!-- /toggle search -->
					<div class="search-container">
						<form role="search">
							<input type="text" class="form-control search" placeholder="type and press enter">
						</form>
					</div>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">

				<li class="dropdown hidden-xs">
					<a href="javascript:;" data-toggle="dropdown">
						<i class="ti-more-alt"></i>
					</a>
					<ul class="dropdown-menu animated zoomIn">
						<li class="dropdown-header">Quick Links</li>
						<li>
							<a href="javascript:;">Start New Campaign</a>
						</li>
						<li>
							<a href="javascript:;">Review Campaigns</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="javascript:;">Settings</a>
						</li>
						<li>
							<a href="javascript:;">Wish List</a>
						</li>
						<li>
							<a href="javascript:;">Purchases History</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="javascript:;">Activity Log</a>
						</li>
						<li>
							<a href="javascript:;">Settings</a>
						</li>
						<li>
							<a href="javascript:;">System Reports</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="javascript:;">Help</a>
						</li>
						<li>
							<a href="javascript:;">Report a Problem</a>
						</li>
					</ul>
				</li>

				<li class="notifications dropdown">
					<a href="javascript:;" data-toggle="dropdown">
						<i class="ti-bell"></i>
						<div class="badge badge-top bg-danger animated flash">
							<span>
								<?php
									$uid = $UserSession::get('user._id');
									$data['notifications_unread'] = array_filter($MongoDoc::get($data, 'notifications', array()), function($v) use($uid) {
										return in_array($uid, $v['readers']);
									});
									echo count($data['notifications_unread']);
								?>
							</span>
						</div>
					</a>
					<div class="dropdown-menu animated fadeInLeft">
						<div class="panel panel-default no-m">
							<div class="panel-heading small"><b>Notifications</b>
							</div>
							<?php if (count($data['notifications_unread']) == 0): ?>
								<small class="text-warning ml15">No unread notifications</small>
							<?php else: ?>
								<ul class="list-group" style="max-height: 400px; overflow-y:scroll">
									<?php foreach ($MongoDoc::get($data, 'notifications_unread', array()) as $n): ?>
										<li class="list-group-item">
											<a href="<?=$Url::base('notification/'.$n['_id'])?>">
												<div class="pull-left mt5 mr15">
													<div class="circle-icon bg-default">
														<i class="ti-flag-alt"></i>
													</div>
												</div>
												<div class="m-body">
													<div>
														<small><strong><?=$n['sender']['name']?></strong></small>
													<span class="time small pull-right">
													<?=$Time::since($n['_id']->getTimestamp(), 0);?> ago
													</span>
													</div>
													<div class="small">
														<?=$n['text']?>
													</div>
												</div>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif ?>

							<div class="panel-footer">
								<a href="<?=$Url::base('notification')?>">See all notifications</a>
							</div>
						</div>
					</div>
				</li>

				<li class="off-right">
					<a href="javascript:;" data-toggle="dropdown">
						<?php
						$image = $UserSession::get('user.picture') ?: sprintf('https://www.gravatar.com/avatar/%s?d=%s',
									md5(strtolower(trim($UserSession::get('user.email')))),
									urlencode($Url::base('assets/main/img/faceless.jpg')));
						?>
						<!--<img src="<?=$Url::asset_path('main/img/faceless.jpg')?>" class="header-avatar img-circle" alt="user" title="user">-->
						<img src="<?=$image?>" class="header-avatar img-circle" alt="user" title="user">
						<span class="hidden-xs ml10"><?=$UserSession::get('user.manager.name')?:$UserSession::get('user.name')?></span>
						<i class="ti-angle-down ti-caret hidden-xs"></i>
					</a>
					<ul class="dropdown-menu animated fadeInRight">
						<?php if (($a=$UserSession::get('user.type')) && in_array($a, array('brand', 'influencer'))): ?>
						<li>
							<a href="<?=$Url::base($a.'/view/'.$UserSession::get('user._id'))?>">Public Profile</a>
						</li>
						<?php endif ?>
						<li>
							<a href="<?=$Url::base('user')?>">Settings</a>
						</li>
						<li>
							<a href="javascript:;">Help</a>
						</li>
						<li>
							<a href="<?=$Url::base('auth/logout')?>">Logout</a>
						</li>
					</ul>
				</li>
			</ul>
		</header>
		<!-- /menu navigation -->
	<?php endif; ?>

		<section class="layout">
	<?php if ( ! $data['_meta']['page-no-sidebar']): ?>
			<?php include(__DIR__ . '/sidebar.php'); ?>
	<?php endif; ?>
			<!-- main content -->
			<section class="main-content">
				<!-- content wrapper -->
				<div class="content-wrap">
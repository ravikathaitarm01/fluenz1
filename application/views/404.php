<!doctype html>
<html class="error-page no-js" lang="">

<head>
	<!-- meta -->
	<meta charset="utf-8">
	<meta name="description" content="Flat, Clean, Responsive, application admin template built with bootstrap 3">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<!-- /meta -->

	<title>Fluenz - Page Not Found</title>

	<!-- page level plugin styles -->
	<!-- /page level plugin styles -->

	<!-- core styles -->
	<link rel="stylesheet" href="<?=$Url::asset_path('main/bootstrap/css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/font-awesome.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/themify-icons.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/animate.min.css')?>">
	<!-- /core styles -->

	<!-- template styles -->
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/skins/palette.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/fonts/font.css')?>">
	<link rel="stylesheet" href="<?=$Url::asset_path('main/css/main.css')?>">
	<!-- template styles -->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- load modernizer -->
	<script src="<?=$Url::asset_path('main/plugins/modernizr.js')?>"></script>
</head>

<!-- body -->

<body class="bg-primary">

<!-- error wrapper -->
<div class="center-wrapper">
	<div class="center-content text-center">
		<div class="error-number animated bounceIn"><i class="ti-unlink mr15 show"></i>404</div>
		<div class="mb25">PAGE NOT FOUND</div>
		<p>Sorry, but the page you were trying to view does not exist.</p>
		<div class="row mt25">
			<a href="<?=$Url::base('')?>"><i class="fa fa-arrow-circle-left"></i> Go Back to Fluenz</a>
		</div>
		<!-- Error -->
		<section class="row mt25">
			<code>
				<?php isset($data['error'])? var_dump($data['error']): null; ?>
			</code>
		</section>
		<!-- /Error -->
	</div>
</div>
<!-- /error wrapper -->

</body>
<!-- /body -->

</html>

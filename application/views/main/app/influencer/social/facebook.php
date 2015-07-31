<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
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
		'main/js/app/influencer/social/facebook.js'
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-md-4">
				<section class="panel panel-default">
					<header class="panel-heading">
						<span class="pull-right"><a href="<?=$Url::base('influencer/social/')?>" class="btn btn-warning" style="color:#fff"><i class="fa fa-ban"></i> Cancel</a></span>
						<h5>Facebook</h5>
					</header>
					<div class="panel-body">
						<form id="x-form-page" class="parsley-form" action="" role="form" method="post" data-parsley-validate>
							<input id="x-page-data" type="hidden" name="page_data" value="" />
							<div class="col-sm-8">
								<select id="x-page-select" name="page" class="form-control input-lg" style="line-height: inherit;-webkit-appearance: menulist;" required data-parsley-trigger="change">
									<option value="">Please select...</option>
									<?php foreach ($data['pages'] as $p): $p = (array)$p; ?>
										<option value="<?=$p['id']?>" data-page='<?=json_encode($p)?>'><?=$p['name']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-4">
								<button type="submit" class="btn btn-outline btn-danger mt5" name="action" value="save">Select</button>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>

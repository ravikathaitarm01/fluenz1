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
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js'
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-md-4">
				<section class="panel panel-default">
					<header class="panel-heading">
						<span class="pull-right"><a href="<?=$Url::base('influencer/social/')?>" class="btn btn-warning" style="color:#fff"><i class="fa fa-ban"></i> Cancel</a></span>
						<h5>Vine</h5>
					</header>
					<div class="panel-body">
						<p>
							Adding your Klout account, requires at least one of the accounts pre-added to Fluenz:
							<br />
							<span class="text-info">Note: The respective account must also be linked to your Klout profile</span>
						</p>
						<ul>
							<li><i class="text-info fa fa-<?=isset($data['accounts']['twitter'])?'check':'close'?>"></i> Twitter</li>
							<li><i class="text-info fa fa-<?=isset($data['accounts']['twitter'])?'check':'close'?>"></i> Google+</li>
							<li><i class="text-info fa fa-<?=isset($data['accounts']['twitter'])?'check':'close'?>"></i> Instagram</li>
						</ul>

						<form id="x-form-page" class="parsley-form" action="" role="form" method="post" data-parsley-validate>
							<div class="col-sm-2 pull-right">
								<button type="submit" class="btn btn-outline btn-danger mt5" name="action" value="save">Add</button>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>

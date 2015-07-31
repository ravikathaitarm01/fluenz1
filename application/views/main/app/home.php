<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'admin',
	'page-css' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
	),
	'page-js' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.js',
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
		'main/js/app/home.js',
	)
);

function _time($ts)
{
	return date('l jS F Y, H:i', strtotime($ts));
}

function _url($link, $name=null)
{
	return sprintf('<a href="%s" target="_blank">%s</a>', $link, $name?:$link);
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
									<h4 class="mb0"><b>Brand Name</b></h4>
									<small>Account Name</small>
								</div>
								<div class="col-xs-8 text-center">
									<div class="col-xs-12 col-sm-4">
										<h2 class="mb0"><b>0</b></h2>
										<small>Followers</small>
									</div>
									<div class="col-xs-12 col-sm-4">
										<h2 class="mb0"><b>0</b></h2>
										<small>Following</small>
									</div>
									<div class="col-xs-12 col-sm-4">
										<h2 class="mb0"><b>0</b></h2>
										<small>Posts</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- /profile information sidebar -->
			</div>
		</div>

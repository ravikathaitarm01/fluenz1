<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'notifications-home',
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
			<div class="col-md-12">
				<!-- profile information sidebar -->
				<div class="panel overflow-hidden no-b profile p15">
					<div class="row mb25 bb">
						<div class="col-sm-12">
							<div class="notifications dropdown">
								<a href="javascript:;" data-toggle="dropdown">
									<div class="badge badge-top bg-danger animated flash">
										<span>
											<?php
												$uid = $UserSession::get('user._id');
												echo count(array_filter($MongoDoc::get($data, 'notifications', array()), function($v) use($uid) {
													return in_array($uid, $v['readers']);
												}));
											?>
										</span>
										Unread
									</div>
								</a>
								<div class="row-fluid">
										<?php foreach ($MongoDoc::get($data, 'notifications', array()) as $n): ?>
											<div class="pl10 pt5 pr10 mb20 bt">
												<div class="mr15 ml5 mb10 mt5">
													<div class="circle-icon bg-<?=in_array($uid, $n['readers'])? 'primary': 'default'?>">
														<a href="<?=$Url::base('notification/'.$n['_id'])?>">
															<i class="ti-flag-alt"></i>
														</a>
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
											</div>
										<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- /profile information sidebar -->
			</div>
		</div>

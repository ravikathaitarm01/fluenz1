<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Output $Output
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'brand-river',
	'page-css' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
	),
	'page-js' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.js',
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
		'main/js/app/brand/river.js'
	)
);

https://www.facebook.com/Honeytechblog/posts/10152862248294872
?>

<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-sm-12">
		<div class="col-sm-12">

			<div class="col-md-6">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Facebook</h5>
					</header>
					<div class="panel-body">
						<div id="fb-root"></div>
						<script>
							(function(d, s, id) {
								var js, fjs = d.getElementsByTagName(s)[0];
								if (d.getElementById(id)) return;
								js = d.createElement(s); js.id = id;
								js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=1615025442106209";
								fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));
							window.renderFacebookItem = function() {
								if (typeof(FB) != 'undefined') {
									console.log('call-right');
									FB.XFBML.parse($('#url-facebook-render')[0]);
								} else {
									console.log('call-again');
									setTimeout(window.renderFacebookItem, 2000);
								}
							};
						</script>
						<div class="col-sm-12">
							<div class="row-fluid">
								<?php
								$custom = false;
								if ($u = $MongoDoc::get($data['user'], 'social_river.data_custom.facebook'))
								{
									$custom = true;
								}
								else if($t = $MongoDoc::get($data['user'], 'social_river.data.facebook'))
								{
									$u = sprintf('https://www.facebook.com/%s/posts/%s', $t['from']['name'], explode('_', $t['id'])[1]);
								}
								?>
								<form method="post">
									<div class="col-sm-9">
										<input id="url-facebook" class="form-control" type="text" name="url" value="<?=$u?>">
									</div>
									<div class="col-sm-3 pull-right">
										<input class="input-toggle x-form-social-auto" type="checkbox" name="auto" value="1" <?=$custom?'':'checked'?> data-on="Auto" data-off="Custom" data-onstyle="primary" data-offstyle="default">
										<button class="btn btn-primary" type="submit" name="action" value="facebook"><i class="fa fa-floppy-o"></i></button>
									</div>
								</form>
							</div>
							<div class="col-sm-12"><hr /></div>
						</div>
						<div class="col-sm-12 social-item-render">
							<div class="row-fluid">
								<div id="url-facebook-render"></div>
							</div>
						</div>
					</div>
				</section>
			</div>

			<div class="col-md-6">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Twitter</h5>
					</header>
					<div class="panel-body">
						<script>
							window.twttr = (function(d, s, id) {
								var js, fjs = d.getElementsByTagName(s)[0],
									t = window.twttr || {};
								if (d.getElementById(id)) return t;
								js = d.createElement(s);
								js.id = id;
								js.src = "https://platform.twitter.com/widgets.js";
								fjs.parentNode.insertBefore(js, fjs);

								t._e = [];
								t.ready = function(f) {
									t._e.push(f);
								};
								return t;
							}(document, "script", "twitter-wjs"));
							window.renderTwitterItem = function() {
								if (typeof(twttr.widgets) != 'undefined') {
									twttr.widgets.load($('#url-twitter-render')[0]);
									console.log('call-right');
								} else {
									console.log('call-again');
									setTimeout(window.renderTwitterItem, 2000);
								}
							};
						</script>

						<div class="col-sm-12">
							<div class="row-fluid">
								<?php
								$custom = false;
								if ($u = $MongoDoc::get($data['user'], 'social_river.data_custom.twitter'))
								{
									$custom = true;
								}
								else if($t = $MongoDoc::get($data['user'], 'social_river.data.twitter'))
								{
									$u = sprintf('https://twitter.com/%s/status/%s', $t['user']['screen_name'], $t['id_str']);
								}
								?>
								<form method="post">
									<div class="col-sm-9">
										<input id="url-twitter" class="form-control" type="text" name="url" value="<?=$u?>">
									</div>
									<div class="col-sm-3 pull-right">
										<input class="input-toggle x-form-social-auto" type="checkbox" name="auto" value="1" <?=$custom?'':'checked'?> data-on="Auto" data-off="Custom" data-onstyle="primary" data-offstyle="default">
										<button class="btn btn-primary" type="submit" name="action" value="twitter"><i class="fa fa-floppy-o"></i></button>
									</div>
								</form>
							</div>
							<div class="col-sm-12"><hr /></div>
						</div>
						<div class="col-sm-12 social-item-render">
							<div class="row-fluid">
								<div id="url-twitter-render" class=""></div>
							</div>
						</div>
					</div>
				</section>
			</div>

			<div class="col-md-6">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Instagram</h5>
					</header>
					<div class="panel-body ">
						<script>
							window.renderInstagramItem = function() {
								if (typeof(window.instgrm) != 'undefined') {
									window.instgrm.Embeds.process($('#url-twitter-render')[0]);
									console.log('call-right');
								} else {
									console.log('call-again');
									setTimeout(window.renderInstagramItem, 2000);
								}
							};
						</script>
						<script async defer src="//platform.instagram.com/en_US/embeds.js"></script>
						<div class="col-sm-12">
							<div class="row-fluid">
								<?php
								$custom = false;
								if ($u = $MongoDoc::get($data['user'], 'social_river.data_custom.instagram'))
								{
									$custom = true;
								}
								else if($t = $MongoDoc::get($data['user'], 'social_river.data.instagram'))
								{
									$u = sprintf('%s', $t['link']);
								}
								?>
								<form method="post">
									<div class="col-sm-9">
										<input id="url-instagram" class="form-control" type="text" name="url" value="<?=$u?>">
									</div>
									<div class="col-sm-3 pull-right">
										<input class="input-toggle x-form-social-auto" type="checkbox" name="auto" value="1" <?=$custom?'':'checked'?> data-on="Auto" data-off="Custom" data-onstyle="primary" data-offstyle="default">
										<button class="btn btn-primary" type="submit" name="action" value="instagram"><i class="fa fa-floppy-o"></i></button>
									</div>
								</form>
							</div>
							<div class="col-sm-12"><hr /></div>
						</div>
						<div class="col-sm-12 social-item-render">
							<div class="row-fluid">
								<div id="url-instagram-render">
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>

			<div class="col-md-6">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Youtube</h5>
					</header>
					<div class="panel-body ">
						<div class="col-sm-12">
							<div class="row-fluid">
								<?php
								$custom = false;
								if ($u = $MongoDoc::get($data['user'], 'social_river.data_custom.google-youtube'))
								{
									$custom = true;
								}
								else if($t = $MongoDoc::get($data['user'], 'social_river.data.google-youtube'))
								{
									$u = sprintf('https://www.youtube.com/watch?v=%s', $t['resourceId']['videoId']);
								}
								?>
								<form method="post">
									<div class="col-sm-9">
										<input id="url-google-youtube" class="form-control" type="text" name="url" value="<?=$u?>">
									</div>
									<div class="col-sm-3 pull-right">
										<input class="input-toggle x-form-social-auto" type="checkbox" name="auto" value="1" <?=$custom?'':'checked'?> data-on="Auto" data-off="Custom" data-onstyle="primary" data-offstyle="default">
										<button class="btn btn-primary" type="submit" name="action" value="google-youtube"><i class="fa fa-floppy-o"></i></button>
									</div>
								</form>
							</div>
							<div class="col-sm-12"><hr /></div>
						</div>
						<div class="col-sm-12 social-item-render">
							<div class="row-fluid">
								<div id="url-google-youtube-render">
									<iframe width="100%" height="375" src="" frameborder="0" allowfullscreen></iframe>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>

			<div class="col-md-6">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Google+</h5>
					</header>
					<div class="panel-body ">
						<script >
							window.___gcfg = {
								parsetags: 'onload'
							};
						</script>
						<script src="https://apis.google.com/js/platform.js" async defer></script>
						<script >
						window.renderGooglePlusItem = function() {
							if (typeof(gapi) != 'undefined') {
								gapi.post.render($('#url-google-plus-render')[0]);
								console.log('call-right');
							} else {
								console.log('call-again');
								setTimeout(window.renderGooglePlusItem, 2000);
							}
						};
						</script>
						<div class="col-sm-12">
							<div class="row-fluid">
								<?php
								$custom = false;
								if ($u = $MongoDoc::get($data['user'], 'social_river.data_custom.google-plus'))
								{
									$custom = true;
								}
								else if($t = $MongoDoc::get($data['user'], 'social_river.data.google-plus'))
								{
									$u = sprintf('%s', $t['url']);
								}
								?>
								<form method="post">
									<div class="col-sm-9">
										<input id="url-google-plus" class="form-control" type="text" name="url" value="<?=$u?>">
									</div>
									<div class="col-sm-3 pull-right">
										<input class="input-toggle x-form-social-auto" type="checkbox" name="auto" value="1" <?=$custom?'':'checked'?> data-on="Auto" data-off="Custom" data-onstyle="primary" data-offstyle="default">
										<button class="btn btn-primary" type="submit" name="action" value="google-plus"><i class="fa fa-floppy-o"></i></button>
									</div>
								</form>
							</div>
							<div class="col-sm-12"><hr /></div>
						</div>
						<div class="col-sm-12 social-item-render">
							<div class="row-fluid">
								<div id="url-google-plus-render">
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

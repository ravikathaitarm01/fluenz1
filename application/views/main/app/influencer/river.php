<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Output $Output
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'influencer-river',
	'page-css' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
	),
	'page-js' => array(
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.min.js',
		'main/plugins/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
		'main/js/app/influencer/river.js'
	)
);

https://www.facebook.com/Honeytechblog/posts/10152862248294872
?>

<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-sm-12 mb25">
		<div class="row-fluid col-sm-6">
			<label>Brand</label>
			<select class="form-control" id="x-brand-select">
				<option value="">-- Select --</option>
				<?php foreach ($data['brands'] as $b): ?>
					<option value="<?=$b['_id']?>" <?=$b['_id'] == $data['brand_id']?'selected':''?>><?=$b['name']?></option>
				<?php endforeach ?>
			</select>
		</div>
		<form id="x-brand-form" method="post"><input type="hidden" name="id" value=""></form>
	</div>
	<div class="col-sm-12 mb25">
		<hr/>
	</div>
	<?php if ($data['river']): ?>
	<div class="col-sm-12">
		<div class="col-md-4">
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
					<?php
					$custom = false;
					if ($u = $MongoDoc::get($data['river'], 'data_custom.facebook'))
					{
						$custom = true;
					}
					else if($t = $MongoDoc::get($data['river'], 'data.facebook'))
					{
						$u = sprintf('https://www.facebook.com/%s/posts/%s', $t['from']['name'], explode('_', $t['id'])[1]);
					}
					?>
					<div class="col-sm-12 social-item-render">
						<div class="row-fluid">
							<div id="url-facebook-render" data-url="<?=$u?>"></div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<div class="col-md-4">
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
					<?php
					$custom = false;
					if ($u = $MongoDoc::get($data['river'], 'data_custom.twitter'))
					{
						$custom = true;
					}
					else if($t = $MongoDoc::get($data['river'], 'data.twitter'))
					{
						$u = sprintf('https://twitter.com/%s/status/%s', $t['user']['screen_name'], $t['id_str']);
					}
					?>
					<div class="col-sm-12 social-item-render">
						<div class="row-fluid">
							<div id="url-twitter-render" data-url="<?=$u?>"></div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<div class="col-md-4">
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
					<?php
					$custom = false;
					if ($u = $MongoDoc::get($data['river'], 'data_custom.instagram'))
					{
						$custom = true;
					}
					else if($t = $MongoDoc::get($data['river'], 'data.instagram'))
					{
						$u = sprintf('%s', $t['link']);
					}
					?>
					<div class="col-sm-12 social-item-render">
						<div class="row-fluid" style="height:100%">
							<div id="url-instagram-render" data-url="<?=$u?>" style="height:100%">

							</div>
							<!--<iframe src="" width="100%" height="480" frameborder="0" scrolling="no"></iframe>-->
						</div>
					</div>
				</div>
			</section>
		</div>

		<div class="col-md-4">
			<section class="panel panel-default">
				<header class="panel-heading">
					<h5>Youtube</h5>
				</header>
				<div class="panel-body ">
					<?php
					$custom = false;
					if ($u = $MongoDoc::get($data['river'], 'data_custom.google-youtube'))
					{
						$custom = true;
					}
					else if($t = $MongoDoc::get($data['river'], 'data.google-youtube'))
					{
						$u = sprintf('https://www.youtube.com/watch?v=%s', $t['resourceId']['videoId']);
					}
					?>
					<div class="col-sm-12 social-item-render">
						<div class="row-fluid">
							<div id="url-google-youtube-render" data-url="<?=$u?>">
								<iframe width="100%" height="375" src="" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<div class="col-md-4">
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
					<?php
					$custom = false;
					if ($u = $MongoDoc::get($data['river'], 'data_custom.google-plus'))
					{
						$custom = true;
					}
					else if($t = $MongoDoc::get($data['river'], 'data.google-plus'))
					{
						$u = sprintf('%s', $t['url']);
					}
					?>
					<div class="col-sm-12 social-item-render">
						<div class="row-fluid">
							<div id="url-google-plus-render" data-url="<?=$u?>">
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?php endif ?>
</div>

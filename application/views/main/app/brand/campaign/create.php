<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Output $Output
 * @var app\helpers\MongoDoc $MongoDoc
 */
$data['_this'] = array(
	'page-class' => 'brand-campaign-create',
	'page-css' => array(
		'main/plugins/stepy/jquery.stepy.css'
	),
	'page-js' => array(
		'main/plugins/stepy/jquery.validate.min.js',
		'main/plugins/stepy/jquery.stepy.js',
		'main/plugins/fuelux/wizard.js',
		'main/js/app/brand/campaign/create.js',
	)
);
?>
<!-- inner content wrapper -->
<div class="wrapper">
	<div class="col-md-12">

		<form id="x-create-form" class="stepy" method="post">
			<fieldset title="Category">
				<legend>Campaign Type</legend>
				<input type="hidden" name="id" />
				<div class="form-group">
					<label class="col-sm-12">Type</label>
					<div class="col-sm-offset-1">
						<div class="radio col-sm-12">
							<label>
								<input name="type" type="radio" value="digital-pr" class="x-input-type" data-title="Digital PR"> Digital PR
							</label>
							<div class="col-sm-offset-1 animated fadeIn x-container-subtype" style="display: none">
								<div class="radio col-sm-12">
									<label>
										<input type="radio" name="subtype" value="create" class="x-input-subtype" data-title="Create"> Create
									</label>
								</div>
								<div class="radio col-sm-12">
									<label>
										<input type="radio" name="subtype" value="amplify" class="x-input-subtype" data-title="Amplification"> Amplification
									</label>
								</div>
							</div>
						</div>
						<div class="radio col-sm-12">
							<label>
								<input name="type" type="radio" value="ad-serving" class="x-input-type" data-title="Ad Serving"> Ad Serving
							</label>
						</div>
						<div class="radio col-sm-12">
							<label>
								<input type="radio" name="type" value="custom" class="x-input-type" data-title="Custom"> Custom
							</label>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset title="Social">
				<legend>Exposure Channels</legend>
				<div id="x-social-digital-pr-create" class="x-social">
					<div class="form-group">
						<div class="checkbox col-sm-12">
							<label>
								<input name="social_create[facebook]" type="checkbox" value="1" > Facebook
							</label>
						</div>
						<div class="checkbox col-sm-12">
							<label>
								<input name="social_create[twitter]" type="checkbox" value="1" > Twitter
							</label>
						</div>
						<div class="checkbox col-sm-12">
							<label>
								<input name="social_create[instagram]" type="checkbox" value="1" > Instagram
							</label>
						</div>
						<div class="checkbox col-sm-12">
							<label>
								<input name="social_create[google_analytics]" type="checkbox" value="1" > Blog
							</label>
						</div>
						<div class="checkbox col-sm-12">
							<label>
								<input name="social_create[google_youtube]" type="checkbox" value="1" > YouTube
							</label>
						</div>
						<div class="checkbox col-sm-12">
							<label>
								<input name="social_create[google_plus]" type="checkbox" value="1" > Google+
							</label>
						</div>
						<div class="checkbox col-sm-12">
							<label>
								<input name="social_create[vine]" type="checkbox" value="1" > Vine
							</label>
						</div>
					</div>
				</div>

				<div id="x-social-digital-pr-amplify" class="x-social">
					<div class="form-group">
						<div class="checkbox col-sm-12">
							<label class="col-sm-3">
								Facebook
							</label>
							<div class="col-sm-2">
								<select name="action_social_amplify[facebook]" class="form-control">
									<option value="like">Like Post</option>
								</select>
							</div>
							<div class="col-sm-7">
								<input name="social_amplify[facebook]" type="url" class="form-control col-sm-12" placeholder="URL" />
							</div>
						</div>
						<div class="checkbox col-sm-12">
							<label class="col-sm-3">
								Twitter
							</label>
							<div class="col-sm-2">
								<select name="action_social_amplify[twitter]" class="form-control">
									<option value="favorite">Favorite Tweet</option>
									<option value="retweet">Retweet</option>
								</select>
							</div>
							<div class="col-sm-7">
								<input name="social_amplify[twitter]" type="url" class="form-control col-sm-12" placeholder="URL" />
							</div>

						</div>
						<div class="checkbox col-sm-12">
							<label class="col-sm-3">
								Instagram
							</label>
							<div class="col-sm-2">
								<select name="action_social_amplify[instagram]" class="form-control">
									<option value="favorite">Favorite Image</option>
								</select>
							</div>
							<div class="col-sm-7">
								<input name="social_amplify[instagram]" type="url" class="form-control col-sm-12" placeholder="URL" />
							</div>
						</div>
						<div class="checkbox col-sm-12">
							<label class="col-sm-3">
								Blog
							</label>
							<div class="col-sm-2">
								<select name="action_social_amplify[google_analytics]" class="form-control">
									<option value="">-</option>
								</select>
							</div>
							<div class="col-sm-7">
								<input name="social_amplify[google_analytics]" type="url" class="form-control col-sm-12" placeholder="URL" />
							</div>
						</div>
						<div class="checkbox col-sm-12">
							<label class="col-sm-3">
								YouTube
							</label>
							<div class="col-sm-2">
								<select name="action_social_amplify[google_youtube]" class="form-control">
									<option value="like">Like Video</option>
									<option value="subscribe">Subscribe Channel</option>
								</select>
							</div>
							<div class="col-sm-7">
								<input name="social_amplify[google_youtube]" type="url" class="form-control col-sm-12" placeholder="URL" />
							</div>
						</div>
						<div class="checkbox col-sm-12">
							<label class="col-sm-3">
								Google+
							</label>
							<div class="col-sm-2">
								<select name="action_social_amplify[google_plus]" class="form-control">
									<option value="plus-one">+1</option>
								</select>
							</div>
							<div class="col-sm-7">
								<input name="social_amplify[google_plus]" type="url" class="form-control col-sm-12" placeholder="URL" />
							</div>
						</div>
						<div class="checkbox col-sm-12">
							<label class="col-sm-3">
								Vine
							</label>
							<div class="col-sm-2">
								<select name="action_social_amplify[vine]" class="form-control">
									<option value="like">Like Video</option>
								</select>
							</div>
							<div class="col-sm-7">
								<input name="social_amplify[vine]" type="url" class="form-control col-sm-12" placeholder="URL" />
							</div>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset title="Duration">
				<legend>Time Period</legend>
				<div class="form-group col-sm-6">
					<label>From</label>
					<div>
						<input type="text" name="date[from]" class="form-control datepicker" />
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label>Till</label>
					<div>
						<input type="text" name="date[to]" class="form-control datepicker" />
					</div>
				</div>
				<div class="form-group col-sm-12">
					<h3>
						<strong>Days: </strong> <span id="x-input-duration-days" class="text-primary">0</span>
					</h3>
				</div>
			</fieldset>

			<fieldset title="Description">
				<legend>Fine Print</legend>
				<div class="form-group">
					<label>Title</label>
					<div>
						<input type="text" name="title" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label>Brief</label>
					<div>
						<textarea name="brief" class="form-control" rows="30"></textarea>
					</div>
				</div>
			</fieldset>

			<fieldset title="Influencers">
				<legend>Pre-select List</legend>
				<div class="form-group">
					<label>Influencer List</label>
					<div>
						<select class="chosen" name="influencer_list" style="width:400px">
							<option value="">-- Auto --</option>
							<?php foreach ($data['lists'] as $k=>$l): ?>
								<option value="<?=$k?>"><?=$l?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</fieldset>

			<fieldset title="Finish">
				<legend>Submit for Approval</legend>
				<div class="col-sm-8">
					<h3 id="x-overview-title">Title</h3>
					<h5 id="x-overview-category" class="bolder text-info">Digital PR : Create</h5>
					<h5>Influencer List: <strong id="x-overview-influencer-list"></strong></h5>
				</div>
				<div class="pull-right text-center">
					<span id="x-overview-date-from" class="bolder">25th July 2015</span>
					-
					<span id="x-overview-date-to" class="bolder">27th July 2015</span>
					<br />
					<span id="x-overview-date-days">(4 days)</span>
				</div>
				<div class="col-sm-12">
					<hr />
				</div>
				<div class="col-sm-12">
					<h4>Social Channels:</h4>
				</div>
				<?php foreach (array(
									'facebook' => 'Facebook',
									'twitter' => 'Twitter',
									'instagram' => 'Instagram',
									'google_analytics' => 'Blog',
									'google_youtube' => 'YouTube',
									'google_plus' => 'Google+',
									'vine' => 'Vine'
								) as $k=>$t): ?>
					<div class="col-sm-6">
						<label class="col-sm-3"><?=$t?></label>
						<div id="x-overview-social-<?=$k?>" class="col-sm-9"></div>
					</div>
				<?php endforeach ?>
				<div class="col-sm-12">
					<hr />
				</div>
				<div class="col-sm-12">
					<pre id="x-overview-brief"></pre>
				</div>
				<div class="col-sm-12">
					<hr />
				</div>
			</fieldset>


			<button class="btn btn-primary stepy-finish pull-right"><i class="ti-share mr5"></i>Finish</button>

		</form>
	</div>
</div>

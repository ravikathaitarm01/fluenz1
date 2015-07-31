<?php
/**
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\Url $Url
 * @var array $data
 */
?>
<!-- sidebar menu -->
<aside class="sidebar offscreen-left">
	<!-- main navigation -->
	<nav class="main-navigation" data-height="auto" data-size="6px" data-distance="0" data-rail-visible="true" data-wheel-step="10">
		<p class="nav-title">MENU</p>
		<ul class="nav">
			<!-- home -->
			<li>
				<a href="<?=$Url::base('brand/home')?>">
					<i class="ti-home"></i>
					<span>Home</span>
				</a>
			</li>
			<!-- /home -->

			<!-- manage -->
			<li>
				<a href="javascript:;">
					<i class="toggle-accordion"></i>
					<i class="ti-crown"></i>
					<span>Manage</span>
				</a>
				<ul class="sub-menu">
					<li>
						<a href="<?=$Url::base('brand/profile')?>">
							<span>Profile</span>
						</a>
					</li>
				<?php if ($UserSession::get('user.manager') === null): ?>
					<li>
						<a href="<?=$Url::base('brand/manager')?>">
							<span>Managers</span>
						</a>
					</li>
				<?php endif ?>
					<li>
						<a href="<?=$Url::base('brand/social')?>">
							<span>Social</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('brand/river')?>">
							<span>River</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('brand/favorite')?>">
							<span>Favorites</span>
						</a>
					</li>
				</ul>
			</li>
			<!-- /manage -->
			<li>
				<a href="<?=$Url::base('brand/influencer')?>">
					<i class="ti-home"></i>
					<span>Influencers</span>
				</a>
			</li>
			<!-- campaign -->
			<li>
				<a href="javascript:;">
					<i class="toggle-accordion"></i>
					<i class="ti-layout-media-overlay-alt-2"></i>
					<span>Campaigns</span>
				</a>
				<ul class="sub-menu">
					<li>
						<a href="<?=$Url::base('brand/campaign/create')?>">
							<span>Create</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('brand/campaign/create')?>">
							<i class="toggle-accordion"></i>
							<span>View</span>
						</a>
						<ul class="sub-menu">
							<li>
								<a href="<?=$Url::base('brand/campaign/pending')?>">
									<span>Pending</span>
								</a>
							</li>
							<li>
								<a href="<?=$Url::base('brand/campaign/active')?>">
									<span>Active</span>
								</a>
							</li>
							<li>
								<a href="<?=$Url::base('brand/campaign/completed')?>">
									<span>Completed</span>
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</li>
			<!-- /campaign -->


			<!-- report -->
			<li>
				<a href="<?=$Url::base('brand/report')?>">
					<i class="ti-home"></i>
					<span>Report</span>
				</a>
			</li>
			<!-- /report -->

			<li>
				<a href="<?=$Url::base('calendar')?>">
					<i class="ti-home"></i>
					<span>Calendar</span>
				</a>
			</li>
		</ul>
	</nav>
</aside>
<!-- /sidebar menu -->
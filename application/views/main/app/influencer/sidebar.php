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
				<a href="<?=$Url::base('influencer/home')?>">
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
						<a href="<?=$Url::base('influencer/profile')?>">
							<span>Profile</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('influencer/social')?>">
							<span>Social</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('influencer/brand')?>">
							<span>Points</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('influencer/favorite')?>">
							<span>Favorites</span>
						</a>
					</li>
				</ul>
			</li>
			<!-- /manage -->

			<li>
				<a href="<?=$Url::base('influencer/brand')?>">
					<i class="ti-home"></i>
					<span>Brands</span>
				</a>
			</li>

			<li>
				<a href="<?=$Url::base('influencer/river')?>">
					<i class="ti-home"></i>
					<span>Brand River</span>
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
						<a href="<?=$Url::base('campaigns/discover')?>">
							<span>Discover</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('campaigns/manage')?>">
							<span>Manage</span>
						</a>
					</li>
				</ul>
			</li>
			<!-- /campaign -->


			<!-- report -->
			<li>
				<a href="<?=$Url::base('influencer/report')?>">
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
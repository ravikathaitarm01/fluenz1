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
			<!-- dashboard -->
			<li>
				<a href="<?=$Url::base('admin/home')?>">
					<i class="ti-home"></i>
					<span>Home</span>
				</a>
			</li>
			<!-- /dashboard -->

			<!-- manage -->
			<li>
				<a href="javascript:;">
					<i class="toggle-accordion"></i>
					<i class="ti-crown"></i>
					<span>Manage</span>
				</a>
				<ul class="sub-menu">
					<li>
						<a href="<?=$Url::base('admin/profile')?>">
							<span>Profile</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('admin/admin')?>">
							<span>Admins</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('admin/partner')?>">
							<span>Partners</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('admin/influencer')?>">
							<span>Influencers</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('admin/brand')?>">
							<span>Brands</span>
						</a>
					</li>
					<li>
						<a href="<?=$Url::base('admin/campaign')?>">
							<span>Campaigns</span>
						</a>
					</li>
				</ul>
			</li>
			<!-- /manage -->

			<!-- report -->
			<li>
				<a href="<?=$Url::base('admin/report')?>">
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
<div>
	<p>
		Hi,
	</p>
	<p>
		The brand <?=$data['brand']['name']?> has created a new campaign
		<a href="<?=$Url::base('admin/campaign/'.$data['campaign']['_id'])?>"><strong><?=$data['campaign']['title']?></strong></a>
		that requires admin action.
	</p>
</div>
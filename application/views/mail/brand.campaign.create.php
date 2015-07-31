<div>
	<p>
		Hi <?=$data['user']['name']?>,
	</p>
	<p>
		Your campaign <a href="<?=$Url->base('brand/campaign/'.$data['campaign']['_id'])?>"><strong><?=$data['campaign']['title']?></strong></a> has been created.
		<br />
		Our admins will approve it shortly and contact the appropriate influencers
	</p>
</div>
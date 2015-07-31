<div>
	<p>
		Hi <?=$data['user']['name']?>,
	</p>
	<p>
		Your <?=$data['social']?> account under <strong><?=$data['user']['username']?></strong> has been invalidated.
		This means we are no longer able to track changes on your social account.
	</p>
	<p>
		The possible reasons for account invalidation are:
	</p>
	<ul>
		<li>User changed password</li>
		<li><?=$data['social']?> invalidated the application access</li>
		<li>An unknown error occurred trying to track your <?=$data['social']?> account</li>
	</ul>
	<br />
	<p>
		Please re-link the account from your account's <a href="<?=$Url::base('brand/social')?>">Social</a> settings.
		Please note that until you re-link your <?=$data['social']?> account, it will be completely ignored by our service.
	</p>
</div>
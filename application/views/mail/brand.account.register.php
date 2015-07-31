<div>
	<p>
		Hi <?=$data['user']['name']?>,
	</p>
	<p>
		Your account <strong><?=$data['user']['username']?></strong> has been created with the following details:
	</p>
	<p>
		Username: <strong><?=$data['user']['username']?></strong>
		<br />
		Password <strong><?=$data['user']['password']?></strong>
	</p>
	<p>
		However your account is not active yet. You will be receiving an additional email when an admin activates it.
	</p>
</div>
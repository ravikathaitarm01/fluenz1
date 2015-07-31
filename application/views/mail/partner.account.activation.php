<div>
	<p>
		Hi <?=$data['user']['name']?>,
	</p>
	<p>
		Your account <strong><?=$data['user']['username']?></strong> has been <?=$data['user']['active']? 'activated': 'deactivated'?> by
		the administrator: <?=$data['by']['name']?>.
	</p>
</div>
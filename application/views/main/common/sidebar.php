<?php
/**
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\Url $Url
 * @var array $data
 */
?>
<?php
switch ($t = $UserSession::get('user.type'))
{
	case 'admin':
	case 'partner':
	case 'influencer':
	case 'brand':
		/** @noinspection PhpIncludeInspection */
		include($Url::view(sprintf('main/app/%s/sidebar.php', $t)));
		break;
}
?>
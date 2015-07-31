<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 */
$data['_this'] = array(
	'page-class' => 'debug-mail',
	'page-page-body-class' => '',
	'page-no-menu' => true,
	'page-no-sidebar' => true
);
?>
<div class="cover" style="background-image: url('<?=$Url::asset_path('backend/img/cover1.jpg')?>')"></div>
<div class="center-wrapper">
	<div class="center-content">
		<div class="row bordered">
	<?php foreach ($data['mails'] as $doc): ?>
			<div class="col-sm-5 mb25 <?=$doc['processing']? 'bg-white':''?> col-sm-offset-1" style="height: 400px;overflow-y: auto;">
				<div class="col-sm-5"><?=$doc['_id']?></div>
				<div class="col-sm-3"><?=date('Y-m-d H:i:s', $doc['_id']->getTimestamp())?></div>
				<div class="col-sm-1"><?=$doc['tries']?></div>
				<div class="col-sm-4"><?=implode(',', $doc['to'])?></div>
				<div class="col-sm-4"><?=$doc['from']?></div>
				<div class="col-sm-4"><?=$doc['subject']?></div>
				<div class="col-sm-12"><?=$doc['message']?></div>
			</div>
	<?php endforeach; ?>
		</div>
	</div>
</div>

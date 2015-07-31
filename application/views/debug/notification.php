<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 */
$data['_this'] = array(
	'page-class' => 'debug-notifications',
	'page-page-body-class' => '',
	'page-no-menu' => true,
	'page-no-sidebar' => true
);
?>
<div class="cover" style="background-image: url('<?=$Url::asset_path('backend/img/cover1.jpg')?>')"></div>
<div class="center-wrapper">
	<div class="center-content">
		<div class="row bordered">
			<div class="col-sm-3">
				<form method="post">
					<select name="id">
						<?php foreach ($data['users'] as $doc): ?>
							<option <?=$doc['_id'] == $data['selected']? 'selected': ''?> value="<?=$doc['_id']?>"><?=$doc['username']?></option>
						<?php endforeach; ?>
					</select>
					<button type="submit" class="btn btn-small btn-primary">View</button>
				</form>
			</div>
			<div class="col-sm-9">
				<?php foreach ($data['notifications'] as $doc): ?>
					<div class="mb25 col-sm-offset-1" style="height: 400px;overflow-y: auto;">
						<div class="col-sm-4"><?=$doc['_id']?></div>
						<div class="col-sm-3"><?=date('Y-m-d H:i:s', $doc['_id']->getTimestamp())?></div>
						<div class="col-sm-2"><?=$doc['type']?></div>
						<div class="col-sm-2"><?=$doc['sender']['username'];?></div>
						<div class="col-sm-3"><?=$doc['url']?></div>
						<div class="col-sm-9" style="background-color:white"><?=$doc['text']?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\MongoDoc $MongoDoc
 * @var app\helpers\UserSession $UserSession
 */

function __get_state_label($state)
{
	switch ($state)
	{
		case 'pending':
			return 'default';
		case 'active':
			return 'success';
		case 'completed':
			return 'primary';
		case 'rejected':
			return 'danger';
		default:
			return '';
	}
}
function __get_social_name($account)
{
	switch ($account)
	{
		case 'facebook':
			return 'Facebook';
		case 'twitter':
			return 'Twitter';
		case 'instagram':
			return 'Instagram';
		case 'google-youtube':
			return 'YouTube';
		case 'google-analytics':
			return 'Blog';
		case 'google-plus':
			return 'Google+';
		case 'vine':
			return 'Vine';
		case 'klout':
			return 'Klout';
	}
	return null;
}
?>
<div class="col-sm-12">
	<section class="panel panel-default">
		<header class="panel-heading">
			<h5>Campaign</h5>
		</header>
		<div class="panel-body">
			<div class="col-sm-12">
				<div class="pull-right text-right" style="font-size: 20px">
						<span class="label label-<?=__get_state_label($data['campaign']['state'])?>">
							<?=ucwords($data['campaign']['state'])?>
						</span>
					<br />
						<span class="bolder">
							<?=ucwords(str_replace('-', ' ', $data['campaign']['type']))?>
							<?php if ($st = $MongoDoc::get($data, 'campaign.subtype')):?>
								: <?=ucwords(str_replace('-', ' ', $st))?>
							<?php endif ?>
						</span>
					<?php if ($MongoDoc::get($data, 'campaign.global', false)):?>
						<br /><i class="ti ti-world" title="Globally Discoverable"></i>
					<?php endif ?>
				</div>
				<h2><?=$data['campaign']['title']?></h2>
				<h4><a href="<?=$Url::base('brand/view/'.$data['campaign']['brand']['_id'])?>"><?=$data['campaign']['brand']['name']?></a></h4>
			</div>
			<div class="col-sm-12">
				<h3><?=$data['campaign']['duration']['start']['date']?> - <?=$data['campaign']['duration']['end']['date']?></h3>
			</div>
			<div class="col-sm-12">
				<h5>Points: <span class="label bg-info" style="font-size:15px"><?=$data['campaign']['points']?></span></h5>
			</div>
			<div class="col-sm-12"><hr/></div>
			<div class="col-sm-12 mt25">
				<?php foreach ($data['campaign']['social'] as $k=>$v): ?>
					<div class="col-sm-12">
						<?php if (is_bool($v)): ?>
							<label class="col-sm-3"><?=__get_social_name($k)?></label>
							<i class="col-sm-1 fa fa-<?=$v?'check':'close'?> text-<?=$v?'success':'danger'?>"></i>
						<?php else: ?>
							<label class="col-sm-3"><?=__get_social_name($k)?></label>
							<?php if ($MongoDoc::get($data, 'campaign.social_amplify_actions')): ?>
								<span style="width:50px;display:inline-block;padding: 0" class="mr5 label label-default"><?=$data['campaign']['social_amplify_actions'][$k]?:'-'?></span>
							<?php endif ?>
							<?php if ($v): ?>
								<a href="<?=$v?>" target="_blank"><?=$v?></a>
							<?php else: ?>
								<i class="fa fa-minus text-danger"></i>
							<?php endif ?>
						<?php endif ?>
					</div>
				<?php endforeach ?>
			</div>
			<div class="col-sm-12"><hr/></div>
			<div class="col-sm-12 mt25">
				<?=nl2br(htmlentities($data['campaign']['brief']))?>
			</div>
		</div>
	</section>
</div>
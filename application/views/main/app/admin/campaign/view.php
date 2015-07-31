<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\Time $Time
 * @var app\helpers\MongoDoc $MongoDoc
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'admin-campaign-pending',
	'page-js' => array(
		'main/js/app/admin/campaign/view.js',
	)
);
?>

<!-- inner content wrapper -->
<div class="wrapper">
	<?php include(VIEWPATH.'main/app/campaign/view.php') ?>

	<div class="col-sm-12">
		<div class="panel panel-primary panel-body">
			<?php if (in_array($data['campaign']['state'], array('pending', 'rejected'))): ?>
				<form class="col-sm-1" method="post" action="<?=$Url::base('admin/campaign/approve')?>">
					<input type="hidden" name="id" value="<?=$data['campaign']['_id']?>" />
					<button class="btn btn-success" name="action" value="approve">Approve</button>
				</form>
			<?php endif ?>
			<?php if (in_array($data['campaign']['state'], array('pending', 'active'))): ?>
				<form class="col-sm-1" method="post" action="<?=$Url::base('admin/campaign/reject')?>">
					<input type="hidden" name="id" value="<?=$data['campaign']['_id']?>" />
					<button type="submit" id="x-btn-campaign-reject" class="btn btn-danger" name="action" value="reject">Reject</button>
				</form>
			<?php endif ?>
		</div>
	</div>
	<div class="col-sm-6">
		<h3>Admin - Brand</h3>
		<section class="panel bordered  post-comments">
			<div>
				<div class="media p15">
					<?php foreach ($MongoDoc::get($data['campaign'], 'comments.admin_brand', array()) as $c): ?>
						<div class="media">
							<div class="comment">
								<div class="pull-right">
									<span class="label label-<?=$c['user'] == 'brand'? 'info': 'warning'?>"><?=$c['user']?></span>
								</div>
								<div class="comment-author h5 no-m">
									<a href="<?=$c['user'] == 'brand'? $Url::base('brand/view/'.$c['from']): '#'?>"><b><?=$c['from_username']?></b></a>
								</div>
								<div class="comment-meta small"><?=$Time::str($c['created_at'], $UserSession::get('user.timezeone'), 'd F Y, H:i')?></div>
								<p>
									<?=htmlentities($c['text'])?>
								</p>
							</div>
						</div>
						<hr />
					<?php endforeach ?>
				</div>

				<div class="panel-footer">
					<form role="form" class="form-horizontal" method="post" action="<?=$Url::base('admin/campaign/comment/brand')?>">
						<input type="hidden" name="id" value="<?=$data['campaign']['_id']?>" />
						<div class="form-group no-m">
							<div class="input-group">
								<input type="text" class="form-control input-sm no-border" name="comment">
							<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="submit">COMMENT</button>
						</span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-6">
		<h3>Admin - Influencer</h3>
		<section class="panel bordered  post-comments">
			<?php foreach ($MongoDoc::get($data['campaign'], 'comments.admin_influencer', array()) as $k=>$comments): ?>
				<div id="conversation-<?=$k?>">
					<div class="media p15">
						<?php foreach ($comments as $c): ?>
							<div class="media">
								<div class="comment">
									<div class="pull-right">
										<span class="label label-<?=$c['user'] == 'influencer'? 'color': 'warning'?>"><?=$c['user']?></span>
									</div>
									<div class="comment-author h5 no-m">
										<a href="<?=$c['user'] == 'influencer'? $Url::base('influencer/view/'.$c['from']): '#'?>"><b><?=$c['from_username']?></b></a>
									</div>
									<div class="comment-meta small"><?=$Time::str($c['created_at'], $UserSession::get('user.timezeone'), 'd F Y, H:i')?></div>
									<p>
										<?=htmlentities($c['text'])?>
									</p>
								</div>
							</div>
							<hr />
						<?php endforeach ?>
					</div>

					<div class="panel-footer">
						<form role="form" class="form-horizontal" method="post" action="<?=$Url::base('admin/campaign/comment/influencer')?>">
							<input type="hidden" name="id" value="<?=$data['campaign']['_id']?>" />
							<input type="hidden" name="influencer" value="<?=$k?>" />
							<div class="form-group no-m">
								<div class="input-group">
									<input type="text" class="form-control input-sm no-border" name="comment">
								<span class="input-group-btn">
								<button class="btn btn-default btn-sm" type="submit">COMMENT</button>
							</span>
								</div>
							</div>
						</form>
					</div>
				</div>
			<?php endforeach ?>
		</section>
	</div>

</div>

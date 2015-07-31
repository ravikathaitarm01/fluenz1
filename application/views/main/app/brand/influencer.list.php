<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 */
?>
<div class="table-responsive no-border">
	<table class="table table-bordered table-striped mg-t datatable" data-url="">
		<thead>
		<tr>
			<th>Name</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($data['influencers'] as $a): ?>
			<tr>
				<td data-search="<?=$a['name']?> <?=$a['username']?>">
					<div class="col-sm-12">
						<a href="<?=$Url::base('influencer/view/'.$a['_id'])?>"><?=$a['name']?></a><br />
						<small><?=$a['username']?></small> <br />
					</div>
				</td>
				<td data-search="">
					<button class="btn btn-danger x-influencer-list-remove" title="Remove" data-id="<?=$a['_id']?>"><i class="fa fa-minus"></i></button>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
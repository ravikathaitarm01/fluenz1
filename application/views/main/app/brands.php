<?php
/**
 * @var array $data
 * @var app\helpers\Url $Url
 * @var app\helpers\UserSession $UserSession
 */
$data['_this'] = array(
	'page-class' => 'admin-brands',
	'page-js' => array(
		'main/js/admin/brand/index.js'
	)
);
?>
		<!-- inner content wrapper -->
		<div class="wrapper">
			<div class="col-sm-3">
				<div class="col-sm-12">
					<section class="panel panel-default">
						<header class="panel-heading">
							<h5>Brands</h5>
						</header>
						<div class="panel-body">
							<div class="">
								<select id="x-select-brand" data-placeholder="Select a brand..." style="" class="col-sm-12 chosen">
									<option value=""></option>
								<?php
									foreach ($data['brands'] as $account => $brands)
									{
										$options = '';
										foreach ($brands as $b)
										{
											$options .= sprintf('<option value="%s">%s</option>', $b['_id'], $b['name']);
										}
										printf('<optgroup label="%s">%s</optgroup>', $account, $options);
									}
								?>
								</select>
							</div>
						</div>
					</section>
				</div>
			<?php if ($UserSession::get('user.role.auth') <= 100): ?>
				<div class="col-sm-12">
					<section class="panel panel-default">
						<header class="panel-heading">
							<h5>Add New Brand</h5>
						</header>
						<div class="panel-body">
							<div class="col-sm-9">
								<select id="x-select-account" data-placeholder="Select an account..." style="" class="col-sm-12 chosen">
									<option value=""></option>
									<?php
									foreach ($data['accounts'] as $a)
									{
										printf('<option value="%s">%s</option>', $a['_id'], $a['name']);
									}
									?>
								</select>
							</div>
							<div class="col-sm-3">
								<button id="x-select-account-btn-add" class="btn btn-primary" disabled><i class="fa fa-plus"></i></button>
							</div>
						</div>
					</section>
				</div>
			<?php endif; ?>
			</div>
			<div class="col-sm-9">
				<section class="panel panel-default">
					<header class="panel-heading">
						<h5>Details</h5>
					</header>
					<div class="panel-body">
						<form id="x-form-brand" class="parsley-form" action="" role="form" method="post" data-parsley-validate>
							<input type="hidden" name="id" value="">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Brand Name</label>
									<input type="text" class="form-control input-lg" name="name" placeholder="Brand name" required data-parsley-trigger="change">
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label>Description</label>
									<textarea name="description" class="form-control input-lg" rows="5" placeholder="A brief description about the brand" required data-parsley-trigger="change"></textarea>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label>Users</label>
									<select id="x-brand-users" name="users[]" class="form-control input-lg chosen" multiple data-parsley-trigger="change" data-placeholder="Assign users to this brand">
									</select>
								</div>
							</div>
							<div class="col-sm-12"><hr /></div>
							<div id="x-form-update-brand-buttons" class="col-sm-12 mt25">
								<button type="button" class="btn btn-outline btn-warning" value="update"
										data-url="<?=$Url::base('brand/update')?>"
										data-confirm-action="Proceed to update this brand's details?"
										disabled>Update</button>
							<?php if ($UserSession::get('user.role.auth') <= 50): ?>
								<button type="button" class="btn btn-outline btn-danger" value="remove"
										data-url="<?=$Url::base('brand/remove')?>"
										data-confirm-action="Proceed to remove this brand? This action is permanent."
										disabled>Remove</button>
							<?php endif; ?>
							</div>
							<div id="x-form-create-brand-buttons" class="col-sm-12 mt25" style="display: none">
								<button type="button" class="btn btn-outline btn-primary" value="create"
										data-url="<?=$Url::base('brand/create')?>"
										data-confirm-action="Proceed to create this brand?">Create</button>
								<button type="button" class="btn btn-outline btn-danger" value="cancel">Cancel</button>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>

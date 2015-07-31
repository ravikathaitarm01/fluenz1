<?php
/**
 * @var app\helpers\UserSession $UserSession
 * @var app\helpers\Url $Url
 * @var app\helpers\Alert $Alert
 * @var array $data
 */
?>
				<!-- /inner content wrapper -->
				</div>
				<!-- /content wrapper -->
				<a class="exit-offscreen"></a>
			</section>
			<!-- /main content -->
		</section>
	</div>
	<!-- footer -->

	<!-- /footer -->

<?php
if ($a = $Alert->get())
{
	printf('<div class="x-alert-once" data-type="%s" data-message="%s"></div>', $a['type'], $a['message']);
}
?>

	<!-- core scripts -->
	<script src="<?=$Url::asset_path('main/plugins/jquery-1.11.1.min.js')?>"></script>
	<script src="<?=$Url::asset_path('main/bootstrap/js/bootstrap.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/jquery.slimscroll.min.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/jquery.easing.min.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/appear/jquery.appear.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/jquery.placeholder.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/fastclick.js')?>"></script>
	<!-- /core scripts -->

	<!-- template scripts -->
	<script src="<?=$Url::asset_path('main/js/offscreen.js')?>"></script>
	<script src="<?=$Url::asset_path('main/js/main.js')?>"></script>
	<!-- /template scripts -->

	<!-- page script -->
	<!--<script src="<?=$Url::asset_path('main/plugins/switchery/switchery.js')?>"></script>-->
	<script src="<?=$Url::asset_path('main/plugins/datepicker/bootstrap-datepicker.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/bootstrap-toggle/bootstrap-toggle.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/toastr/toastr.min.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/parsley.min.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/chosen/chosen.jquery.min.js')?>"></script>
	<script src="<?=$Url::asset_path('main/plugins/datatables/jquery.dataTables.js')?>"></script>
	<!--<script src="https://cdn.datatables.net/responsive/1.0.6/js/dataTables.responsive.js"></script>-->

	<script src="<?=$Url::asset_path('main/js/bootstrap-datatables.js')?>"></script>


	<script src="<?=$Url::asset_path('main/js/app/main.js')?>"></script>
	<script src="<?=$Url::asset_path('main/js/app/header.brand.js')?>"></script>
<?php foreach ($data['_meta']['page-js'] as $js): ?>
	<script src="<?=$Url::asset_path($js)?>"></script>
<?php endforeach; ?>
	<!-- /page script -->

</body>

</html>

<?php $url = Yii::$app->basePath?>
<?php include $url.'/views/layouts/page_footer.php'; ?>
<?php include $url.'/views/layouts/template_scripts.php'; ?>
	<?php
//	$arr = explode('/', $scriptload);
//	$jsFile = '';
//	$scriptload = $arr[count($arr)-1];
//	$scriptload2 = '';
//
//	$xport = FALSE;
//	if(count($arr)-2 != -1){
//		if( $arr[count($arr)-2] == 'transaction' ){ $xport = TRUE; }
//	}
//
//	if(count($arr) > 1){ $scriptload2 = $arr[count($arr)-2].'_'.$arr[count($arr)-1]; }
//
//	if(file_exists($url."web/theme/js/pages/$scriptload.js")){
//
//		$jsFile = $url."web/theme/pages/$scriptload.js"; // include here page javascript if exist
//
//	}elseif (file_exists( $url."web/js/pages/$scriptload2.js")) {
//		if( $arr[count($arr)-2] == 'cms' || $arr[count($arr)-2] == 'faq'){
//			echo '<script src="'.$url.'js/helpers/ckeditor/ckeditor.js"></script>';
//		}
//		$jsFile = $url."pages/$scriptload2.js"; // include here page javascript if exist
//	}
//
//	$xport = false;// remove this code for active export features
//
//	if($xport){
	?>
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo ASSETS_CSS; ?><!--datatable/jquery.dataTables.css">-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo ASSETS_CSS; ?><!--datatable/buttons.dataTables.css">-->
<!--	<script type="text/javascript" language="javascript" src="--><?php //echo ASSETS_JS; ?><!--datatable/jquery.dataTables.js">-->
<!--	</script>-->
<!--	<script type="text/javascript" language="javascript" src="--><?php //echo ASSETS_JS; ?><!--datatable/dataTables.buttons.js">-->
<!--	</script>-->
<!--	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">-->
<!--	</script>-->
<!--	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js">-->
<!--	</script>-->
<!--	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js">-->
<!--	</script>-->
<!--	<script type="text/javascript" language="javascript" src="--><?php //echo ASSETS_JS; ?><!--datatable/buttons.html5.js">-->
<!--	</script>-->
<!---->
<!--	--><?php //
//	}
//	?>
<!--	<script src="--><?php //echo $jsFile; ?><!--"></script>-->

<?php include $url.'/views/layouts/template_end.php';?>
<?php $this->endPage() ?>

<?php ob_start(); ?>
<!DOCTYPE HTML>
<html>
<head><?php
error_reporting(-1);
date_default_timezone_set("Asia/Bangkok");
include_once(".require/SyncConfig.php"); 
$dlfsv4 = new StoreManagement();
?>
<title>KEM-Tools Store</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="Keywords" content="" />
<meta name="Description" content="" />
<link rel="shortcut icon" href="">
<?php $dlfsv4->IncludeScripts(); ?>
</head>
<body>
<script language="javascript">
//$(function(){
//	try { //MSIE
//		if(/(Chrome)/g.exec(navigator.userAgent)) throw new Error('does not support Internet Explorer 6, 7, or 8', 'file');
//		
//	} catch(e) {
//		$('body').empty();
//	}
//});
</script>
<?php require_once('template.php') ?>
</body>
</html>
<?php ob_end_flush(); ?>
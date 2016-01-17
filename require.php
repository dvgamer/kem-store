<?php 
date_default_timezone_set("Asia/Bangkok");
if(preg_match("(/?@)",$_SERVER['REQUEST_URI'])):
include_once(".require/SyncConfig.php");
$database = new fsDigitalLover();
$session = new Session();
list($url, $ajaxName) = explode('/require.php?@' ,$_SERVER['REQUEST_URI']);
@list($ajaxName, $comName) = explode('!' ,$ajaxName);
if($comName!=NULL) {
	if(file_exists('component/com_'.$comName.'/'.$ajaxName.'.php')) {
		include('language/'.$database->profile("language"));
		include_once('component/com_'.$comName.'/'.$ajaxName.'.php');
	} else { echo 'Component not Found.'; }
} else {
	ob_start();
	$profile = $database->query("SELECT * FROM dl_profiles LIMIT 1;");
	include('language/'.$database->profile("language"));
	if(file_exists('skin/css/mod/'.$ajaxName.'.css')) echo '<link rel="stylesheet" type="text/css" href="skin/css/mod/'.$ajaxName.'.css" />';
	if(file_exists('.require/'.$ajaxName.'.php')) include_once('.require/'.$ajaxName.'.php'); else echo $ajaxName.', not Found.';
	ob_end_flush();
} 
endif;
?>

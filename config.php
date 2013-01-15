<?php
	$core_extensions = array('silk_icons');

	//echo getcwd();
	$environment = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "127.0.0.1";
	
	switch($environment) {
		default:
			$prefs = array(
				"FILE_ROOT"   => "/opt/rush-checkin/",
				"URL_ROOT"    => "/",
				"MEDIA_ROOT"  => "/",
				"SERVER_ROOT" => "http://127.0.0.1:9000/",
				"DSN"         => "mysql://root:@localhost/dkecornell",
				"MAIL"		  => false,
				"ADS"		  => false,
				"TRACKING"    => false,
				"DEFAULT"	  => "pages"
			);
			break;
		case "dkecornell.com":
			header("Location: http://www.dkecornell.com");
			exit;
		case "www.dkecornell.com":
			$prefs = array(
				"FILE_ROOT"   => "/opt/rush-checkin/",
				"URL_ROOT"    => "/",
				"MEDIA_ROOT"  => "/",
				"SERVER_ROOT" => "http://www.dkecornell.com/",
				"DSN"         => "mysql://root:@localhost/dkecornell",
				"MAIL"		  => false,
				"ADS"		  => false,
				"TRACKING"    => false,
				"DEFAULT"	  => "pages"
			);
			break;
	}
	
	include($prefs['FILE_ROOT'].'core/src/Core.php');
	foreach($core_extensions as $name)
		include($prefs['FILE_ROOT'].'core/ext/'.$name.'.php');
	
	$routes = array();
	
	$core = Core::instance($prefs, $routes);
?>
<?php
//UserBooth snapshot save code

$picid = 0;

if(isset($_GET["id"]))
	$picid = $_GET["id"];
$snaptime = mktime();
$now = date("F j, Y, g:i a");  
$ip = $_SERVER["REMOTE_ADDR"];

$save_location = '/opt/dkecornell';

if(isset($GLOBALS["HTTP_RAW_POST_DATA"])){
	$jpg = $GLOBALS["HTTP_RAW_POST_DATA"];
	$txt = "IP: " . $ip . " | " . "Time: " . $now ;
	$filename = "$save_location/ext/userbooth/images/snap_". $picid .".jpg";
    $filetxt = "$save_location/ext/userbooth/images/snap_". $picid .".txt";
	file_put_contents($filename, $jpg);
	file_put_contents($filetxt, $txt);
	
	$pic_loc = "/ext/userbooth/images/snap_".$picid.".jpg";
	
	//$string = '';
	//foreach($_POST as $k => $v)
		
	
	//mail("steve.spagnola@gmail.com", "testing", $_SESSION['s']);
	session_start();
	
	$time = $_SESSION['s'];
	
	//mail("steve.spagnola@gmail.com", "testing", $time." -> ".$pic_loc);
	
	
	if(isset($time) && $time > 0) {
		include("$save_location/config.php");
		
		$q = "INSERT INTO rush_pictures (time, url) VALUES ('$time', '$pic_loc')";
		Core::db()->query($q);
		
		//mail("steve.spagnola@gmail.com", "testing", $time." -> ".$pic_loc);
	}
	
	//need to insert the pic id into the database!
	//include("/var/www/vhosts/dkecornell.com/httpdocs/config.php");
	
} else{
	echo "Encoded JPEG information not received.";
}
?>
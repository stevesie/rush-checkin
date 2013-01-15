<?php
	$_SERVER['HTTP_HOST'] = "www.dkecornell.com";
	$email = "";
	
	$fd = fopen("php://stdin", "r");
	while (!feof($fd)) {
	    $email .= fread($fd, 1024);
	}
	fclose($fd);
	
	include('config.php');
	
	$model = new Peoples_Model();
	$model->handle_email($email);
?>
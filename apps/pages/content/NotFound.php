<?php
	$this->t('<h2>Ooooooops!!!</h2>');
	
	$link = Core::get_pref('SERVER_ROOT').Core::get_url();
	
	$this->t('<br/><p style="margin-bottom:0px;padding-bottom:10px;"><strong class="courier error">'.$link.'</strong> does not exist or is no longer available. Sorry for any inconvenience.</p><br/>');
	
?>
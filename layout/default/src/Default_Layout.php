<?php
	class Default_Layout extends Core_View {
	
		function top_layout() {
		
		$this->set_title('Delta Kappa Epsilon (DKE) | Cornell University');
		$this->set_keywords('dke delta kappa epsilon');
		$this->set_description('The Delta Chi chapter of Delta Kappa Epsilon at Cornell University.');
		
		$this->add_head('<script type="text/javascript" src="'.Core::get_pref("MEDIA_ROOT").'layout/default/js/jquery.min.js"></script><script type="text/javascript" src="'.Core::get_pref("MEDIA_ROOT").'layout/default/js/jquery.cycle.all.2.72.js"></script><script type="text/javascript">
$(document).ready(function() {
    $(".slideshow").cycle({
		fx: "fade" // choose your transition type, ex: fade, scrollUp, shuffle, etc...
	});
});
</script>
<link href="'.Core::get_pref("MEDIA_ROOT").'layout/default/css/layout.css" media="screen" rel="stylesheet" type="text/css" />');$this->t('
    <div id="wrap">
      <div id="container">
        <div id="header">
        <div id="nav">');
        
      	$this->t('<div>'.$this->a('Home','').'</div>
      	
      	<!--<div>'.$this->a('Photos','').'</div>
      	<div>'.$this->a('Calendar','').'</div>-->
      	<div>'.$this->a('History','history').'</div>
      	<!--<div>'.$this->a('Scholarship','').'</div>-->
      	<!--<div>'.$this->a('Recruitment','rush').'</div>-->
      	<!--<div>'.$this->a('Officers','').'</div>
      	<div>'.$this->a('Contact Us','').'</div>-->');
      	
      	if(Core::user())
      		$this->t('<div>'.$this->a('Logout','user/logout').'</div>');
      	else
      		$this->t('<div>'.$this->a('Login','user/login').'</div>');
        
        $this->t('</div></div><div id="content">');}
		
		function bottom_layout() {$this->t('</div>
        <div id="footer"><p>&copy;2013 Delta Chi of Delta Kappa Epsilon<br/></p></div>
      </div>
    </div>
  ');}
		
		function spinner() {
			return $this->img_file('layout/default/img/spinner.gif','','class="spinner"');
		}
	}
?>
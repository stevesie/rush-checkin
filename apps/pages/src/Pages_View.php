<?php
	class Pages_View extends Default_Layout {
	
		function render() {
		
			
			$this->top_layout();
			$this->add_css();
		
			$mode = $this->c->get_url_command(0);
			$mode = $mode ? $mode : 'home';
			$mode = ucfirst($mode);
			 
			$file_name = Core::get_pref('FILE_ROOT').'/apps/pages/content/'.$mode.'.php';
			if(file_exists($file_name))
				include($file_name);
			else
				include(Core::get_pref('FILE_ROOT').'/apps/pages/content/NotFound.php');
			
			$this->bottom_layout();
			$this->output();
			
		}
	}
?>
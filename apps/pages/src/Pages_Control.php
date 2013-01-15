<?php
	class Pages_Control extends Core_Control {
	
		function execute() {	
			$view = new Pages_View($this);
			$view->render();
		}
	}
?>
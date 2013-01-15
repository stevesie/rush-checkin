<?php
	class Peoples_Control extends Core_Control {
		
		function execute() {
			if(!Core::user()) Core::redirect();
			
			$this->view = new Peoples_View($this);
			$this->model = new Peoples_Model($this);
			
			$mode = Core::get_post('ajax_action');
			if(Core::get_post('commit') == 'false' || !$mode)
				$mode = $this->get_url_command(1);
			
			if($mode && method_exists($this,$mode))
				$this->$mode();
			else
				$this->home();
		}
		
		function home() {
			$this->view->home();
		}
		
		function users() {
			$this->view->users();
		}
		
		function thread() {
			$thread_id = $this->get_url_command(2);
			$this->view->show_thread($thread_id);
		}
		
	}
?>
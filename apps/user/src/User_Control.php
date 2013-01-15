<?php
	class User_Control extends Core_Control {
		
		function execute() {
			$this->view = new User_View($this);
			$this->model = new User_Model($this);
			
			$mode = Core::get_post('ajax_action');
			if(Core::get_post('commit') == 'false' || !$mode)
				$mode = $this->get_url_command(1);
			
			if($mode && method_exists($this,$mode))
				$this->$mode();
			else
				$this->login();
		}
		
		function login() {
			
			$email = Core::get_post('email');
			$password = Core::get_post('password');
			
			$user_id = $this->model->login($email, $password);

			if(!$user_id)
				$this->view->login($email, $password);
			else{
				//set the session variable
				$_SESSION['user_id'] = $user_id;
				Core::redirect('peoples');
			}
		}
		
		function logout() {
			$_SESSION['user_id'] = 0;
			session_destroy();
			Core::redirect();
		}
	}
?>
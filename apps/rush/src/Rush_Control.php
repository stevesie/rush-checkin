<?php
	class Rush_Control extends Core_Control {
		
		function execute() {
			//if(!Core::user() && $this->get_url_command(1) != 'register'  && $this->get_url_command(1) != 'checkin' )
			//	Core::redirect('rush/register');
			
			//echo "MODE: $mode";
			
			$this->model = new Rush_Model($this);
			$this->view  = new Rush_View($this);
			
			//echo "pizza: ".$_POST['ajax_action'];
			
			
			$mode = Core::get_post('ajax_action');
			if(Core::get_post('commit') == 'false' || !$mode)
				$mode = $this->get_url_command(1);
				
				
			
			//if(Core::get_post('ajax_action') == 'register_ajax')
			//	$mode = 'register_ajax';
			
			if(method_exists($this,$mode))
				$this->$mode();
			else
				$this->home();
		}
		
		function pictures() {
			$this->view->pictures();
		}
		function checkin() {
			if(Core::get_post('commit') == 'true') {
				$this->model->checkin();
				Core::redirect('rush/register');
			}else{
				$rush = new Rush_Register_Object();
				$rush->load($this->get_url_command(2));
				$this->view->checkin($rush);
			}
		}
		
		function register_ajax() {
			
			$this->model->register();
			
			$out = $this->model->get_action()->json();
			echo $out;
				
			
		}
		
		function register() {
			
				$rushes = $this->model->rush_registers();
				$this->view->register($rushes);
		}
		
		function comment() {
			$this->model->comment();
			echo $this->model->get_action()->json();
		}
		
		function picture() {
			if(!session_login()->has_role('rush')) exit;
			if(Core::get_post('object_id')) {
				$this->model->upload_pic();
			}else
				$this->view->pic_prompt($this->get_url_command(2));
		}
		
		function bid() {
			if(!session_login()->has_role('rush')) exit;
			if(Core::get_post('id')) {
				$this->model->set_status(Core::get_post('id'),'Bid');
				$this->view();
			}else
				$this->view->confirm('bid',$this->get_url_command(2));
		}
		
		function abstain() {
			if(!session_login()->has_role('rush')) exit;
			if(Core::get_post('id')) {
				$this->model->set_status(Core::get_post('id'),'Abstain');
				$this->view();
			}else
				$this->view->confirm('abstain',$this->get_url_command(2));
		}
		
		function ding() {
			if(!session_login()->has_role('rush')) exit;
			if(Core::get_post('id')) {
				$this->model->set_status(Core::get_post('id'),'Ding');
				$this->view();
			}else
				$this->view->confirm('ding',$this->get_url_command(2));
		}
		
		function home() {
			$this->view->home();
			return;
			//Core::redirect('rush/register');
			$rushes = $this->model->rushes();
			$this->view->manage($rushes);
		}
		
		function authenticate() {
			if(session_login() && !session_login()->has_role('guest')) {
				Core::redirect('');
			}
			$this->model->authenticate();
			echo $this->model->get_action()->json();
		}
		
		function logout() {
			unset($_SESSION['user_id']);
			session_destroy();
			Core::redirect('');
		}
		
		function manage() {
			if(!session_login()->has_role('admin')) exit;
			$users = $this->model->all_users();
			$this->view->manage($users);
		}
		
		function edit() {
			if(!session_login()->has_role('rush')) exit;
			
			if(Core::get_post('commit') == 'true') {
				$this->model->edit();
				echo $this->model->get_action()->json();
			}else{
				$id = $this->get_url_command(2);
				$rush = new Rush_Object();
				$rush->load($id);
				$this->view->edit($rush);
			}
		}
		
		function view() {
			$id = $this->get_url_command(2);
			$rush = new Rush_Object();
			$rush->load($id);
			$this->view->show($rush);
		}
		
		//login/add/fRamu5epuwEn5D8u3ureyu2ruchex2mat8f5ejas3eperuhuma2abutUw9aspaw6
		function add() { 
			if(Core::get_post('ajax_action')) {
				$this->model->add();
				echo $this->model->get_action()->json();
			}else
				$this->view->add();
		}
		
		/*function execute() {
		
			$this->model = new Login_Model($this);
			$this->view = new Login_View($this);
			
			/*
			$ajax_action = Core::get_post('ajax_action');
			if($ajax_action == 'signup')
				header("Location: ".Core::get_pref('URL_ROOT'));
				
			$mode = $this->get_url_command(1);
			if($mode == 'home_form') {
				if(isset($_POST['signup']))
					header("Location: ".Core::get_pref('URL_ROOT')."/user/signup");
				else
					header("Location: ".Core::get_pref('URL_ROOT')."/user/login");
			}
			
			if($mode == 'logout') {
				$this->model->logout();
				header("Location: ".Core::get_pref("SERVER_ROOT"));
			}
			
			if($mode == 'login') {
				$this->model->login();
				if($this->model->get_action()->is_valid())
					header("Location: ".Core::get_pref("SERVER_ROOT"));
			}
			
			if(!$mode && session_login_id()) {
				header("Location: ".Core::get_pref('SERVER_ROOT'));
				return;
			}
		
			$view = new Login_View($this);
			
			$view->render($mode);
			
		}*/
	}
?>
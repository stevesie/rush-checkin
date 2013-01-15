<?php
	function session_login_id() {
		if(!isset($_SESSION['login_id'])) return 0;
		$id = $_SESSION['login_id'];
		if($id && !Core::get_cache('login')) {
			$user = new Login_Object();
			$user->load($id);
			Core::set_cache('login',$user);
		}
		return $id;
	}
	function session_login() {
		if(!Core::get_cache('login') && $_SESSION['login_id']) {
			$user = new Login_Object();
			$user->load_by_session();
			Core::set_cache('login',$user);
		}
		return Core::get_cache('login');
	}
	function session_user_name() {
		return Core::get_cache('user') ? Core::get_cache('user')->get('username') : '';
	}
	function session_is_admin() {
		if(!session_user_id()) return false;
		return Core::get_cache('user')->get('is_admin') == 1;
	}
?>
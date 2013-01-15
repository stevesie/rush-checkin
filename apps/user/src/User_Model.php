<?php
	class User_Model extends Core_Model {
		
		//Returns 0 for false, or the user_id
		function login($email, $password) {
			
			$q = "SELECT id FROM users WHERE email = '".mysql_escape_string($email)."' AND password = PASSWORD('".mysql_escape_string($password)."')";
			$result = Core::db()->query($q);
			
			
			if($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
				return $row['id'];
			else
				return 0;
		}
		
		function get_user() {
			
			if(!Core::get_pref("DSN") || !isset($_SESSION['user_id']) || $_SESSION['user_id'] == null)
				return null;
				
			$user = new User_Object();
			$user->load($_SESSION['user_id']);
			
			return $user;
		}
		
	}
?>
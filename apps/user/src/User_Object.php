<?php
	class User_Object extends Core_Object {
		
		protected $table_name = 'users';
		protected $app_id = 'user';
		
		function roles() {
			$roles = array();
			$q = "SELECT role_id FROM user_role_assignments WHERE user_id = ".Core::user()->id();
			$result = Core::db()->query($q);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$roles[] = $row['role_id'];
			}
			$role_ids = $this->recurse_role_children($roles);
			
			$condition = '';
			foreach($role_ids as $role)
				$condition .= 'id = '.$role.' OR ';
			$condition = substr($condition, 0, strlen($condition) - 4);
			
			$ret = array();
			
			//now convert to strings
			$q = "SELECT role FROM user_roles WHERE $condition";
			$result = Core::db()->query($q);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$ret[] = $row['role'];
			}
			return $ret;
		}
		
		function recurse_role_children($roles) {
			$starting_size = sizeof($roles);
			
			$condition = '';
			foreach($roles as $role)
				$condition .= 'parent_role = '.$role.' OR ';
			$condition = substr($condition, 0, strlen($condition) - 4);
			
			$q = "SELECT DISTINCT child_role FROM user_roles_hierarchy WHERE $condition AND child_role != parent_role";
			$result = Core::db()->query($q);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				if(!in_array($row['child_role'],$roles))
					$roles[] = $row['child_role'];
			}
			
			if(sizeof($roles) == $starting_size) return $roles;
			return $this->recurse_role_children($roles);
		}
	}
?>
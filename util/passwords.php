<?php
	function generate_db_code($table_name, $field = 'cookie') {
			while(1 == 1) {
				$code = $this->generate_password(26);
				$q = "SELECT COUNT(*) AS counter FROM $table_name WHERE $field = '$code'";
				$result = Core::db()->query($q);
				$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
				$answer = $row['counter'];
				if($answer == 0)
					return $code;
			}
		}
		
		function generate_password($length = 5) {
			$ret = "";
			$chars = "0123456789bcdfghjkmnpqrstvwxyz"; 	
			for($i = 0; $i < $length;$i++) { 
				$char = substr($chars, mt_rand(0, strlen($chars)-1), 1);
				$ret .= $char;
			}
			return $ret;
		} 
		
		function check_unique($table, $field, $value) {
			$error = 'Sorry, this is already taken.';
			$q = 'SELECT COUNT(*) AS counter FROM '.$table.' WHERE '.$field.' = "'.mysql_escape_string($value).'"';
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			return $row['counter'] == 0;
		}
?>
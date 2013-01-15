<?php
	class Rush_Object extends Core_Object {
		
		protected $table_name = 'rush_object';
		
		function fields() {
		
			//First Name
			$first_name = new Core_String('first_name','First Name');
			$first_name->required();
			$this->add_field($first_name);
			
			//Last Name
			$last_name = new Core_String('last_name','Last Name');
			$last_name->required();
			$this->add_field($last_name);
		
			//Email
			$o = new Core_String('netid','Net ID');
			$o->set_email();
			$this->add_field($o);
			
			$phone = new Core_String('phone','Phone Number');
			$phone->set_phone();
			$this->add_field($phone);
			
			$phone = new Core_String('address','Campus Address');
			$phone->set_phone();
			$this->add_field($phone);
			
			$o = new Core_String('town','Home Town');
			$this->add_field($o);
			
			$o = new Core_Set('state','Home State');
			$o->add_states();
			$this->add_field($o);
			
			
			
			$college = new Core_Set('college','College');
			$college->option('Arts & Sciences');
			$college->option('Engineering');
			$college->option('Agriculture & Life Sciences');
			$college->option('Hotel Administration');
			$college->option('Human Ecology');
			$college->option('Industrial and Labor Relations');
			$college->option('Architecture');
			$this->add_field($college);
			
			$o = new Core_String('major','Major(s)');
			$this->add_field($o);
			
			
			
			$picture = new Core_Media('picture','Profile Picture');
			$this->add_field($picture);
			
			$o = new Core_Set('status','Status');
			$o->option('Abstain');
			$o->option('Ding');
			$o->option('Bid');
			$o->locked('rush');
			$this->add_field($o);
			
			
			$o = new Core_String('contact1','Contact 1');
			$o->locked('rush');
			$this->add_field($o);
			
			$o = new Core_String('contact2','Contact 2');
			$o->locked('rush');
			$this->add_field($o);
			
			$o = new Core_String('contact3','Contact 3');
			$o->locked('rush');
			$this->add_field($o);
			
			$o = new Core_String('dinner1','Dinner 1');
			$o->locked('rush');
			$this->add_field($o);
			
			$o = new Core_String('dinner2','Dinner 2');
			$o->locked('rush');
			$this->add_field($o);
			
			$o = new Core_String('dinner3','Dinner 3');
			$o->locked('rush');
			$this->add_field($o);
			
			
			$o = new Core_Set('monday_night','Monday Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('tuesday_smoker','Tuesday Smoker Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('tuesday_night','Tuesday Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			
			$o = new Core_Set('wednesday_smoker','Wednesday Smoker Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('wednesday_night','Wednesday Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('thursday_smoker','Thursday Smoker Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('thursday_night','Thursday Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('friday_smoker','Friday Smoker Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('friday_night','Friday Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('saturday_smoker','Saturday Smoker Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('saturday_night','Saturday Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('sunday_night','Sunday Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			
			$o = new Core_Set('wine_night','Wine and Cheese Night Attendance?');
			$o->locked('rush');
			$o->add_yes_no();
			$this->add_field($o);
			

		}
		
		function num_comments() {
			$q = "SELECT COUNT(*) AS num_comments FROM rush_object_to_comments WHERE rush_id = ".$this->id();
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			return $row['num_comments'];
		}
		
		function comments() {
			
			$collection = new Core_Collection('rush_object_to_comments','Rush_Comment_Object');
			$collection->add_where('rush_id = '.$this->id());
			$collection->add_order('datetime DESC');
			$collection->build();
			return $collection;

		}
		
		
		//roles: guest, client, employee, admin
		function get_roles() {
			
			$ret = array();
			
			$q = 'SELECT roles FROM login_object_to_roles WHERE login_object_id = '.$this->id();
			$result = Core::db()->query($q);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$ret[] = $row['roles'];
			}
			
			return $ret;
		}
		
		function has_role($role) {
			$roles = $this->get_roles();
			if(in_array($role,$roles)) return true;
			return false;
		}
		
		function load_by_session(){
			$this->load(session_login_id());
		}
		
		function thumbnail() {
			if($this->get('picture'))
				return 't-'.$this->get('picture');
			return 't-i-1263673870.jpg';
		}
		
		
		
		/*
		function get_phone() {
			//if the number in the DB is 10 digits long, apply formatting, else just spit out.
			$phone = $this->get('phone');
			if(strlen($phone) == 10) {
				$first = substr($phone, 0, 3);
				$middle = substr($phone, 3, 3);
				$last = substr($phone, 6, 4);
				$phone = "($first) $middle-$last";
			}
			return $phone;
		}
		
		
		
		
		
		

		function check_new_user_code($code) {
			$q = "SELECT email FROM users_new_codes WHERE code = '$code'";
			$result = Core::db()->query($q);
			if($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				return true;
			}
			return false;	
		}
		
	
		function complete_new_account($code, $full_name, $password, $password_confirm) {
		
			if($password == 'password') $password = '';
		
			$err = new Stevesie_Error();
			
			if($full_name == '') $err->add_error('Please enter your name.');
			elseif(strlen($full_name) < 2) $err->add_error('Please enter a longer name (2 or more characters).');
			
			if($password == '') $err->add_error('Please enter a password.');
			elseif(strlen($password) < 8) $err->add_error('Please enter a longer password (8 or more characters).');
			elseif($password_confirm != $password) $err->add_error('The two passwords you entered do not match, please try again.');





			if(!$err->is_clean()) {
				return $err;
			}
			
			//good to go now!
			$q = "SELECT email FROM users_new_codes WHERE code = '$code'";
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			$email = $row['email'];
			$email = mysql_escape_string($email);
			
			if($email == '') return;
			
			$q = "DELETE FROM users_new_codes WHERE email = '$email'";
			Core::db()->query($q);
			
			$full_name = mysql_escape_string($full_name);
			$password = mysql_escape_string($password);
			
			$q = "DELETE FROM users_email_requests WHERE email = '$email'";
			$result = Core::db()->query($q);
			
			//first, we create the user
			$q = "INSERT INTO users (email, password, full_name, created, last_login) VALUES ('$email', PASSWORD('$password'), '$full_name', NOW(), NOW())";
			$result = Core::db()->query($q);
			
			//get the user id
			$q = "SELECT id FROM users WHERE email = '$email'";
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			$user_id = $row['id'];
			
			$_SESSION['user_id'] = $user_id;
			Core::set_user_id($user_id);
			
			
			
			return $err;
		}
		
		function verify_to_email($email) {
			$err = new Stevesie_Error();
		
			if($email == '' || $email == 'email') $err->add_error('Please enter your email address.');
			else if(!$this->valid_email($email)) $err->add_error('Please enter a valid email address.');
			
			if(!$err->is_clean()) {
				return $err;
			}
			
			$my_email = mysql_escape_string($email);
			
			//see if this email address exists
			
			$q = "SELECT COUNT(email) AS counter FROM users WHERE email = '$my_email'";
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			$counter = $row['counter'];
			
			if($counter == 0) {
				$err->add_error('There\'s no account for this email address.');
			}
			
			return $err;
		}
		
		function login($email, $password) {
		
			$err = new Stevesie_Error();
			if($password == 'password') $password = '';
			
			//exception for google...
			//email = google_master
			//password = b48jpvqkMh93
			
			if($email == 'google_master' && $password == 'b48jpvqkMh93') {
				$_SESSION['user_id'] = 1;
				$redir = Core::get_post('redir');
				if(isset($redir)) {
					header("Location: ".Core::get_pref("SERVER_ROOT")."/".Core::get_post('redir'));
					return;
				}
				return $err;
			}
			
		
			$err = $this->verify_to_email($email);
			if(!$err->is_clean())
				return $err;
				
			$err->set_return('clean_email');
			
			if($password == '') {
				$err->add_error('Please enter your password.');
				return $err;
			}
			
			//challenge the password now...
			$my_email = mysql_escape_string($email);
			$my_pass = mysql_escape_string($password);
			
			$q = "SELECT id FROM users WHERE email = '$my_email' AND password = PASSWORD('$my_pass')";
			$result = Core::db()->query($q);
			if($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) { //login succesful
				$user_id = $row['id'];
				$_SESSION['user_id'] = $user_id;
				$q = "UPDATE users SET last_login = NOW() WHERE id = $user_id";
				Core::db()->query($q);
				
				//$expire = time()+60*60*24*30; //expire in a month
				//setcookie("email_addr");
				
				
				//setcookie("email_addr", $email, time()+60*60*24*30;, "/", "stevesie.com");
				//setcookie("email", $email, $expire, "/peoples/", "stevesie.com");
				
				$redir = Core::get_post('redir');
				if(isset($redir)) {
					header("Location: ".Core::get_pref("SERVER_ROOT")."/".Core::get_post('redir'));
					return;
				}
				
				header("Location: ".Core::get_pref("SERVER_ROOT"));
				return null;
			}
			
			$err->add_error('This isn\'t the correct password.');
			return $err;
		}
		
		function forgot_password($email, $human) {
		
			$err = $this->verify_to_email($email);
			if(!$err->is_clean())
				return $err;
				
			if(!$human) return $err;
				
			$my_email = mysql_escape_string($email);
			//delete previous code for security reasons
			$q = "DELETE FROM users_password_requests WHERE email = '$my_email'";
			Core::db()->query($q);
				
			//generate the password and email the user
			$code = Core::generate_db_code('users_password_requests');
			
			$q = "INSERT INTO users_password_requests (code, email) VALUES ('$code', '$email')";
			Core::db()->query($q);
			
			$message = "Hello,\n\nPlease follow this link to reset your password.\n\n".Core::get_pref('SERVER_ROOT')."/login/new_password/$code\n\nThanks,\nStevesie\nhttp://www.stevesie.com";
			
			mail($email, 'Lost Password', $message, 'From: Stevesie <no_reply@stevesie.com>');
			
			$err->add_message('An email has been sent with further instructions.');
			
			return $err;
		}
		
		
		function reset_password($new, $confirm, $code) {
			
			if(!$this->verify_forgot_code($code)) return;
			
			if($new == 'password') $new = '';
		
			$err = new Stevesie_Error();
						
			if($new == '') $err->add_error('Please enter a new password.');
			elseif(strlen($new) < 8) $err->add_error('Please enter a longer password (8 or more characters).');
			
			if(!$err->is_clean()) return $err;
			
			if($confirm != $new) {
				$err->add_error('Your two passwords do not match.');
				return $err;
			}
			
			$new = mysql_escape_string($new);	
			
			
			$email_addr = $this->forgot_user_id($code);
			
			//else, we are now good go
			$q = "UPDATE users SET password = PASSWORD('$new') WHERE email = '".mysql_escape_string($email_addr)."'";
			Core::db()->query($q);
			
			$err->set_return($email_addr);
			
			$q = "DELETE FROM users_password_requests WHERE email = '".mysql_escape_string($email_addr)."'";
			Core::db()->query($q);
			
			return $err;
		}
		
		function forgot_user_id($code) {
			$q = "SELECT email FROM users_password_requests WHERE code = '$code'";
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			return $row['email'];
		}
		
		function verify_forgot_code($code) {
			$q = "SELECT COUNT(*) AS counter FROM users_password_requests WHERE code = '$code'";
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			return $row['counter'] != 0;
		}
			
		function valid_email($email) {
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
				return false;
			return true;
		}

		function valid_phone($phone) {
			$phone = preg_replace('/[^0-9]/','',$phone);
			$phone = trim($phone);
			if(strlen($phone) == 0 || strlen($phone) < 10)
				return false;
			return true;
		}
		
		*/
	}
?>
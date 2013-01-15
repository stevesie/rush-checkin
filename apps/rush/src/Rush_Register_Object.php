<?php
	class Rush_Register_Object extends Core_Object {
		
		protected $table_name = 'rush_register';
		
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
			$o->required();
			$this->add_field($o);
			
			$phone = new Core_String('phone','Phone Number');
			$phone->set_phone();
			$phone->required();
			$this->add_field($phone);
			
			$phone = new Core_String('address','Campus Address');
			$phone->required();
			
			$this->add_field($phone);			

		}

		
	}
?>
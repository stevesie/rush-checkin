<?php
	class User_View extends Default_Layout {
		
		function login($email = '', $password = '') {
			$this->top_layout();
			$this->add_css('pages');
			
			$this->t('<h2>Login</h2>');
			
			if($email != '')
				$this->t('<div id="form_errors" style="display:block;"><ul id="form_errors_ul"><li>Sorry, but there is no account for this email address or you have entered the wrong password.</li></ul></div>');
				
			if($email == '' && isset($_POST['login_form_submit']))
				$this->t('<div id="form_errors" style="display:block;"><ul id="form_errors_ul"><li>Please enter your email address and password.</li></ul></div>');
							
			$form = new Form_Lib($this);
			$this->t($form->get_form_tag('login_form','user/login',false));
	
			$this->t('<br/><p>Email:<br/><input type="email" class="input_text" name="email" value="'.htmlentities($email).'"');
			
			if($email == '') $this->t(' autofocus');
			
			$this->t('/></p>');
			
			$this->t('<br/><p>Password:<br/><input type="password" name="password" class="input_text"');
			
			$this->t('/></p>');
			
			$this->t('<br/><p style="margin-bottom:0px;padding-top:5px;padding-bottom:5px;"><input type="submit" name="login_form_submit" value="Submit" class="input_button"/> '.$this->spinner().'</p>');
			
			$this->t('</form>');
			
			$this->bottom_layout();
			$this->output();
		}
		
	}
?>
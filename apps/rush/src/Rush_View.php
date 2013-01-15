<?php
	class Rush_View extends Default_Layout {
	
		private $model;
		private $mode = 'home';
		private $errors;
		private $notice;
		private $person;
		protected $group;
		protected $page;
		private $invite;
		private $password_get;
		
		function render($mode) {
			
			$this->add_css('pages');
			if($mode == '') $mode = 'home';
			$this->$mode();
			
		}
		
		function pictures() {
		
			$this->top_layout();
			$this->add_css();
			
			//$this->t('<br/><p style="text-align:center;">'.$this->img('dke2011.png').'</p>');
			
			$rushes = $this->c->model()->rush_registers();
			
			$email_string = '';
			
			foreach($rushes->get_objects() as $rush) {
					
				//$this->t('<tr class="highlight">');
				
				$this->t('<div style="float:left; width:420px; text-align:center;">');
				
				$pic_code = $rush->get('pic_code');
				$q = "SELECT url FROM rush_pictures WHERE time = '$pic_code'";
				$result = Core::db()->query($q);
				$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				$this->t('<img src="'.$row['url'].'" /><br/>');
				
				$this->t('<strong style="font-size:1.3em;">'.$rush->get('first_name').' '.$rush->get('last_name').'</strong><br/>');
				$this->t($rush->get('netid').' | '.$rush->get('phone').' | '.$rush->get('address'));
				
				$email_string .= $rush->get('netid').'@cornell.edu, ';
				
				$this->t('<br/><br/><br/><br/>');
				
				//$this->t('<a href="'.Core::get_pref('URL_ROOT').'/rush/view/'.$rush->id().'">'.'</a>');
				
				$chckin = $this->a(silk_icon('accept').' Check In','rush/checkin/'.$rush->id().'/'.strtolower(date('l')),'class="boxy"');

				
				
				//need to grab the picture
				
				
				//$this->t('<td><a href="'.$row['url'].'" target="_blank">'.$rush->get('first_name').'</a></td>'.'<td>'.$rush->get('last_name').'</td>'.'<td>'.$rush->get('netid').'</td>'.'<td>'.$rush->get('phone').'</td>'.'<td>'.$rush->get('address').'</td>');
				
								
				$this->t('</tr>');
				
				
				
				
				$this->t('</div>');
			}
			
			//$this->t($email_string);
			
			
			$this->t('<br style="clear:both;" />');
			$this->bottom_layout();
			$this->output();
		
		}
		
		function home() {
			$this->top_layout();
			$this->add_css();
			
			$this->t('<br/><p style="text-align:center;">'.$this->img('dke2011.png').'</p>');
			
			$this->t('<br style="clear:both;" />');
			$this->bottom_layout();
			$this->output();
		}
		
		function manage($users) {
			$this->top_layout();
			$this->add_css();
			
			
			
			$this->t('<p>'.$this->a(silk_icon('user_add').' New Rush','rush/add').'</p><br/>');

			
			foreach($users->get_objects() as $rush) {
				
				$this->t('<div class="rush_div">');
				
				$this->t('<a href="'.Core::get_pref('URL_ROOT').'/rush/view/'.$rush->id().'">'.$this->img('/uploads/'.$rush->thumbnail()).'</a>');
				
				$this->t('<br/><p><a href="'.Core::get_pref('URL_ROOT').'/rush/view/'.$rush->id().'">'.$rush->get('first_name').' '.$rush->get('last_name').'</a>');
				
				$this->t('<br/>'.'<span class="comments_num">'.$rush->num_comments().' Comments</span></p>');
				
				$this->t('</div>');
			}
			

			$this->t('<br style="clear:both;" />');
			$this->bottom_layout();
			$this->output();
		}
		
		function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}
		
		
		function register($rushes) {
		
			//if(!isset($_GET['s']) || $_GET['s'] != time())
			//	Core::redirect('rush/register/?s='.time());
			
			$time = time().$this->generatePassword(10,0);
			
			
			
			$this->top_layout();
			$this->add_css();
			$this->add_js();
			
			$boxy = new Boxy_Lib($this);
			
			$this->t('<script type="text/javascript">
			
			$(function() {
  $(".boxy").boxy({modal: true, center: false, y: 50, afterShow: function() { $("#focus").focus(); } } ) });</script>');
			
			$this->t('<h2>Welcome to DKE Rush Week 2013!</h2>');
			
			/*	$this->t('<p>Fall 2010 Rush is from Thursday, September 2nd to Monday, September 5th. Please feel free to come to our house at any time and register with this form.</p>');*/
			
			//$this->t('<p>Please check back soon for our Spring 2011 Rush Schedule</p>');
			
			if(isset($_GET['t']) && $_GET['t'] == 'true')
				$this->t('<p id="confirm">Thank you for registering.</p>');
			
			
			$this->t('<div id="new_reg_form">');
			
			
			$form = new Form_Lib($this);
			
			$this->t($form->get_form_tag("register",'rush/register_ajax'));
			$this->t($form->get_action_tag("register_ajax"));
			
			$object = new Rush_Register_Object();
			$object->fields();
			$fields = $object->get_fields();
			
			session_destroy();
			session_start();
			
			$_SESSION['s'] = $time;
			
			//mail("steve.spagnola@gmail.com", "testing", $_SESSION['s']);
			
			//$this->t('Email<br/><input name="email" /><br/><br/>');

			//$this->t('<h3 class="reg">DKE Rush Week 2010</h3><br/>');
			
			//$this->t('<p style="text-align:center;" id="click_link"><a id="clickin" href="javascript:signin_form();">Click the Card to Sign In</a></p>');
			
			//$this->t('<p id="qcard" style="text-align:center;"><br/><a href="javascript:signin_form();">'.$this->media('front.png').'</a><br/><br/><br/><br/></p>');
			
			
			
			$this->t('<div id="signin_form">');
			
			$this->t('<p><a href="javascript:new_rush();">'.silk_icon('add').' Register</a></p>');
			
			
			
			$this->t('<div id="reg_div" style="display:none;"><br/>');
			
			
			
			$allow = array('first_name','last_name','netid','phone','address','town','state','college','major');
			
			$focus = true;
			
			
			foreach($fields as $field) {
				if($field->is_many()) continue;
				
				$name = $field->field_name();
				$label = $field->field_label();
				
				if(!in_array($name,$allow)) continue;
				
				
				
				if($field->is_obj()) {
					$object_id = @$_GET[get_class($field)];
					if(!$object_id) continue;
					$this->t('<input type="hidden" name="'.$name.'" value="'.$object_id.'" />');
					continue;
				}
				
				$form_node = $field->form_node('','id="form_'.$name.'"');
				$this->t($label.'<br/>'.$form_node.'<br/><br/>');
				
				if($focus) {
					$this->t('<script type="text/javascript">$("#reg_div input").focus();</script>');
					$focus = false;
				}
			}
			
			$this->t('<p>Smile for the Camera:<br/><strong style="color:green;">Please click "Allow" below and take a picture.</strong><br/><div id="flashArea" class="flashArea" style="height:400;"><p align="center">This content requires the Adobe Flash Player.<br /><a href="http://www.adobe.com/go/getflashplayer">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /><br />
    <a href=http://www.macromedia.com/go/getflash/>Get Flash</a></p>
	</div></p>'); 
			
			$this->add_file_js('ext/userbooth/swfobject.js', true);
			
			$this->t('<script type="text/javascript">
	var mainswf = new SWFObject("'.Core::get_pref('SERVER_ROOT').'ext/userbooth/userbooth.swf", "main", "700", "370", "9", "#ffffff");
	mainswf.addParam("scale", "noscale");
	mainswf.addParam("wmode", "window");
	mainswf.addParam("allowFullScreen", "true");
	mainswf.addVariable("config_file", "'.Core::get_pref('SERVER_ROOT').'ext/userbooth/xml/config_en");
	mainswf.write("flashArea");
  </script>');
			
			$this->t($form->get_errors_tag());
			$this->t($form->get_commit_tag('false'));
			
			$this->t('<input type="hidden" name="pic_code" value="'.$time.'" />');
			
			$this->t('<p><strong style="color:red;">One more step! Please click the button below!</strong></p>');
			
			$this->t('<input type="submit" value="PLEASE CLICK HERE TO SAVE" /></div></form>');
			
			$this->t('</div>');
			
			
			//if(Core::user()) {
			
				$this->t('<br/><table width="100%" cellpadding="0" cellspacing="0">');
				
				$this->t('<tr><td><strong>First Name</strong></td><td><strong>Last Name</strong></td><td><strong>Net ID</strong></td><td><strong>Phone Number</strong></td><td><strong>Campus Address</strong></td><td><strong>Tues</strong></td><td><strong>Wed</strong></td><td><strong>Thurs</strong></td><td><strong>Fri</strong></td></tr>');
				
				//$this->t('<tr><td><strong>First Name</strong></td><td><strong>Last Name</strong></td><td><strong>Net ID</strong></td><td><strong>Phone Number</strong></td><td><strong>Campus Address</strong></td></tr>');
				
				
				foreach($rushes->get_objects() as $rush) {
					
					$this->t('<tr class="highlight">');
					
					//$this->t('<a href="'.Core::get_pref('URL_ROOT').'/rush/view/'.$rush->id().'">'.'</a>');
					
					$chckin = $this->a(silk_icon('accept').' Check In','rush/checkin/'.$rush->id().'/'.strtolower(date('l')),'class="boxy"');
					
					
					$monday = $rush->get('monday') ? silk_icon('tick') : (date('l') === 'Monday' ? $chkin : '');
					$tuesday = $rush->get('tuesday') ? silk_icon('tick') : (date('l') === 'Tuesday' ? $chkin : '');
					$wednesday = $rush->get('wednesday') ? silk_icon('tick') : (date('l') === 'Wednesday' ? $chkin : '');
					$thursday = $rush->get('thursday') ? silk_icon('tick') : (date('l') === 'Thursday' ? $chkin : '');
					$friday = $rush->get('friday') ? silk_icon('tick') : (date('l') === 'Friday' ? $chkin : '');
					
					
					
					//need to grab the picture
					$pic_code = $rush->get('pic_code');
					$q = "SELECT url FROM rush_pictures WHERE time = '$pic_code'";
					
					$result = Core::db()->query($q);
					
					//if($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					
						$this->t('<td>'.$rush->get('first_name').'</td>'.'<td>'.$rush->get('last_name').'</td>'.'<td>'.$rush->get('netid').'</td>'.'<td>'.$rush->get('phone').'</td>'.'<td>'.$rush->get('address').'</td>');
					
					//}
					
					
					$this->t('<td>'.$tuesday.'</td>');
					$this->t('<td>'.$wednesday.'</td>');
					$this->t('<td>'.$thursday.'</td>');
					$this->t('<td>'.$friday.'</td>');
					
					
									
					$this->t('</tr>');
				}
				
				$this->t('</table>');
			
			//}
			
			
			$this->t('</div>');
				
			
			
			
			
  
			
			
			$this->bottom_layout();
			$this->output();
		}
		
		function checkin($rush) {
		
			if(!$this->c->get_url_command(3)) {
				Core::redirect('rush/register');
			}
			
			$form = new Form_Lib($this);
			
			$id = $this->c->get_url_command(2);
			$day = $this->c->get_url_command(3);
			//$day = substr($day, 0, strpos($day,'?');
			
			
			$o = $form->get_form_tag($action,"rush/checkin/$id",false);
			$o .= $form->get_action_tag($action);

			$o .= '<p class="light_title"><p>Check in for <strong>'.$rush->get('first_name').' '.$rush->get('last_name').'</strong>?</p><br/>';
				
			//$o .= $form->get_errors_tag();
			
			$o .= '<input type="hidden" name="object_id" value="'.$id.'" />
			<input type="hidden" name="commit" value="true" />
			<input type="hidden" name="day" value="'.$this->c->get_url_command(3).'" />
				<p><button type="submit" style="height:20px;">'.silk_icon('accept').' <span>Yes</span></button> <a href="#" class="close"><button style="height:20px;">'.silk_icon('cross').' <span>Cancel</span></button></p>
			</form>
			';
			
			echo $o;
			exit;
			
		}
		
		function pic_prompt($id) {
			
			$form = new Form_Lib($this);
			
			$o = $form->get_form_tag('picture',"rush/view/$id",false,true);
			$o .= $form->get_action_tag('picture');

			$o .= '<div style="text-align:left;"><p class="light_title"><p><strong>Select a Picture</strong></p><br/>';
			
			$o .= '<p>Browse for a file:<br/><input type="file" name="picture" /><br/><br/>or enter the URL of an image:<br/><input name="picture_url" /><br/><br/></p>';
				
			//$o .= $form->get_errors_tag();
			
			$o .= '<input type="hidden" name="object_id" value="'.$id.'" />
				<p><button type="submit" >'.silk_icon('disk').' <span>Save</span></button> <a href="#" class="close"><button>'.silk_icon('cross').' <span>Cancel</span></button></p></div>
			</form>
			';
			
			echo $o;
			exit;
		
		}
		
		function confirm($action, $id) {
			$form = new Form_Lib($this);
			
			$o = $form->get_form_tag($action,"rush/view/$id",false);
			$o .= $form->get_action_tag($action);

			$o .= '<p class="light_title"><p>Are you sure you want to <strong>'.$action.'</strong> him?</p></p><br/>';
				
			//$o .= $form->get_errors_tag();
			
			$o .= '<input type="hidden" name="id" value="'.$id.'" />
				<p><button type="submit" >'.silk_icon('accept').' <span>Yes</span></button> <a href="#" class="close"><button>'.silk_icon('cross').' <span>Cancel</span></button></p>
			</form>
			';
			
			echo $o;
			exit;
		}
		
		function edit($object) {
			
			$this->top_layout();
			
			$this->t('<p>'.$this->a(silk_icon('arrow_left').' Back','rush/view/'.$object->id()).'</p><br/>');
			
			$form = new Form_Lib($this);
			
			$this->t($form->get_form_tag("edit",'rush/view/'.$object->id()));
			$this->t($form->get_action_tag("edit"));
			

			$object->fields();
			$fields = $object->get_fields();
			
			//$this->t('Email<br/><input name="email" /><br/><br/>');

			
			//$allow = array('first_name','last_name','netid','phone','address','town','state','college','major');
			
			foreach($fields as $field) {
				if($field->is_many()) continue;
				
				$name = $field->field_name();
				$label = $field->field_label();
				
				if($name == 'picture') continue;

				$form_node = $field->form_node($object->get($name));
				$this->t($label.'<br/>'.$form_node.'<br/><br/>');
			}
			$this->t('<input type="hidden" name="object_id" value="'.$object->id().'" />');
			
			$this->t($form->get_errors_tag());
			
			$this->t('<input type="submit" value="Edit" /></form>');
			
			$this->bottom_layout();
			$this->output();
			
		}
		
		function show($rush) {
		
			$boxy = new Boxy_Lib($this);
			
			$this->top_layout();
			$this->add_css();
			
			$this->t('<p>'.$this->a(silk_icon('arrow_left').' All Rushes','rush'));
			
			if(session_login()->has_role('rush')) {
				$this->t(' | '.$this->a(silk_icon('pencil').' Edit','rush/edit/'.$rush->id()));
				$this->t(' | '.$this->a(silk_icon('picture').' Profile Picture','rush/picture/'.$rush->id(),'class="boxy"'));
				$this->t(' | '.$this->a(silk_icon('tick').' Bid','rush/bid/'.$rush->id(),'class="boxy"'));
				$this->t(' | '.$this->a(silk_icon('help').' Abstain','rush/abstain/'.$rush->id(),'class="boxy"'));
				$this->t(' | '.$this->a(silk_icon('cross').' Ding','rush/ding/'.$rush->id(),'class="boxy"'));
			}
			
			$this->t('</p><br/>');
			
			$this->t('<p style="float:left;">');
			
			$pic = $rush->get('picture');
			if($pic)
				$this->t($this->media_file('/uploads/'.$pic,'',' id="rush_pic" '));
			else
				$this->t($this->media('belushicollege.jpg','',' id="rush_pic" height="300" width="200"'));
			
			
			$this->t('</p>');
			
			$status = $rush->get('status');
			
			$this->t('<div id="info"><p id="rush_name">'.$rush->get('first_name').' '.$rush->get('last_name').' <span id="status" class="'.$status.'">'.$status.'</span></p>');
			
			$rush->fields();
			$fields = $rush->get_fields();
			
			foreach($fields as $field) {
				$name = $field->field_name();
				$label = $field->field_label();
				
				if($name == 'first_name' || $name == 'last_name' || $name == 'picture' || $name == 'status') continue;
				
				$this->t('<p><div class="label">'.$label.':</div> <div class="val">'.$rush->get($name).'</div></p>');
			}
			
			$this->add_js();
			
			$this->t('<br style="clear:both;" />');
			
			$this->t('<p id="comm">Comments <span><a href="javascript:add_comment();">'.silk_icon('add').' New Comment</a></span></p>');
			
			$this->t('<div id="add_comment" style="display:none;" >');
			
			$form = new Form_Lib($this);
			
			$this->t($form->get_form_tag("comment",'rush/view/'.$rush->id()));
			$this->t($form->get_action_tag("comment"));
			
			$this->t('<textarea name="comment"></textarea><br/>');
		
			$this->t('<input type="hidden" name="object_id" value="'.$rush->id().'" />');
			
			$this->t($form->get_errors_tag());
			
			$this->t('<input type="submit" value="Create" /></form></div>');
			
			
			$comments = $rush->comments();
			foreach($comments->get_objects() as $comment) {
								
				$this->t('<div class="comment">');
				
				$user_id = $comment->get('login_id');
				$login = new Login_Object();
				$login->load($user_id);
				$name = $login->get('first_name').' '.$login->get('last_name');
				
				$this->t('<span class="comment_author">'.$name.':</span> <span class="comment_text">'.str_replace("\n","<br/>",htmlentities($comment->get('comment'))).'</span></p>');
				
				
				$this->t('</div>');
			
			}
			
						
			$this->t('</div>');
			
			$this->t('<script type="text/javascript">
			
			$(function() {
  $(".boxy").boxy({modal: true, center: false, y: 50, afterShow: function() { $("#focus").focus(); } } ) });</script>');
			
			
			
			$this->t('<br style="clear:both;" />');
			
			$this->bottom_layout();
			$this->output();
		}
		
		function add() {
			$this->top_layout(false,'New User');
			
			$this->t('<p>'.$this->a(silk_icon('arrow_left').' Back','rush').'</p><br/>');
			
			$form = new Form_Lib($this);
			
			$this->t($form->get_form_tag("add",'rush'));
			$this->t($form->get_action_tag("add"));
			
			$this->t('<input type="hidden" name="code" value="'.$this->c->get_url_command(2).'" />');
			
			$object = new Rush_Object();
			$object->fields();
			$fields = $object->get_fields();
			
			//$this->t('Email<br/><input name="email" /><br/><br/>');

			
			$allow = array('first_name','last_name','netid','phone','address','town','state','college','major');
			
			foreach($fields as $field) {
				if($field->is_many()) continue;
				
				$name = $field->field_name();
				$label = $field->field_label();
				
				if(!in_array($name,$allow)) continue;
				
				if($field->is_obj()) {
					$object_id = @$_GET[get_class($field)];
					if(!$object_id) continue;
					$this->t('<input type="hidden" name="'.$name.'" value="'.$object_id.'" />');
					continue;
				}
				
				$form_node = $field->form_node();
				$this->t($label.'<br/>'.$form_node.'<br/><br/>');
			}
			
			$this->t($form->get_errors_tag());
			$this->t($form->get_commit_tag('false'));
			
			$this->t('<input type="submit" value="Create" /></form>');
			
			$this->bottom_layout();
			$this->output();
		}
	}
?>
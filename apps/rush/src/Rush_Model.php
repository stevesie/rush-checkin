<?php
	class Rush_Model extends Core_Model {
		
		
		function checkin() {
			$id = Core::get_post('object_id');
			$day = Core::get_post('day');
			
			$q = "UPDATE rush_register SET $day = 1 WHERE id = $id";
			Core::db()->query($q);
			
			
		}
		
		function rush_registers() {
			
			$collection = new Core_Collection('rush_register','Rush_Register_Object');
			$collection->add_order('first_name');
			$collection->add_where('id >= 128');
			$collection->build();
			return $collection;
			
		}
		
		function rushes() {
			
			$collection = new Core_Collection('rush_object','Rush_Object');
			$collection->add_order('first_name');
			$collection->build();
			return $collection;
			
		}
		
		function register() {
			
			$object = new Rush_Register_Object();
			$object->fields();
			
			$fields = $object->get_fields();
			foreach($fields as $field) {
				if($field->is_many()) continue;
					
					
					$name = $field->field_name();
					$label = $field->field_label();
					$entered = Core::get_post($name);
					
					if(!$entered && $field->is_required()) $this->action->add_error($label.' Required.');
					$object->set($name,$entered);
			}
			
			//see if they took a picture
			$pic_code = Core::get_post('pic_code');
			$q = "SELECT COUNT(*) AS counter FROM rush_pictures WHERE time = '$pic_code'";
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			
			if($row['counter'] == 0)
				$this->action->add_error('Please take a photo.');
			else
				$object->set('pic_code', $pic_code);
			
			///DAY OF THE WEEK!!!!
			$object->set('friday',1);
			
			if($this->action->is_valid()) {
			
				$object->make();
				
				
				$this->action->redirect('http://www.dkecornell.com/rush/register/?t=true');
				
			}
		
			
		}
		
		function comment() {
			
			
			
			$rush_id = Core::get_post('object_id');
			$comment = Core::get_post('comment');
			$user = session_login_id();
			
			if($comment == '') {
				$this->action->add_error('Please enter a comment.');
				return;
			}
			
			$q = "INSERT INTO rush_object_to_comments (login_id, rush_id, comment, datetime) VALUES ($user, $rush_id, '".mysql_escape_string($comment)."', NOW())";
			 Core::db()->query($q);
			
		}
		
		
		function upload_pic() {
			
			
			
			//if the URL is entered, just use this.
			if(Core::get_post('picture_url')) {
			
				$url = Core::get_post('picture_url');
				
				
			
				$uploadsDirectory = Core::get_pref("FILE_ROOT").'/uploads/';
				$file_type = substr($url, strrpos($url,'.'));
				$time = time();
				
				$file_path =  $uploadsDirectory.'i-'.$time.''.$file_type;
			    $thumb_path =  $uploadsDirectory.'t-i-'.$time.''.$file_type;
				
				//echo $file_path;
				
				copy($url, $file_path);
						
				


				
			}else{
				
				if(!is_uploaded_file($_FILES['picture']['tmp_name'])) {
				//	return;
				}
				
				if(!getimagesize($_FILES['picture']['tmp_name'])) {
				//	return;
				}
				
				$uploadsDirectory = Core::get_pref("FILE_ROOT").'uploads/';
				
				
				$file_type = substr($_FILES['picture']['name'], strrpos($_FILES['picture']['name'],'.'));
				$file_type = strtolower($file_type);
				$time = time();
				
				$file_path =  $uploadsDirectory.'i-'.$time.''.$file_type;
			    $thumb_path =  $uploadsDirectory.'t-i-'.$time.''.$file_type;
			    
			    move_uploaded_file($_FILES['picture']['tmp_name'], $file_path);
			    
			    
			    
		        	
		        //@copy($file_path, $thumb_path);
			
			}
			
			
			$picsize = @getimagesize("$file_path");
			$source_x = $picsize[0];
			$source_y  = $picsize[1];
			
			//echo $file_path;
			
			//echo "$file_path";
			
			try{
			
				switch($file_type) {
			    	case '.png':
			    		$source_id = @imagecreatefrompng("$file_path");
			    		$thumb_source_id = $source_id;//@imageCreateFromPNG("$thumb_path");
			    		break;
			    	case '.jpeg':
			    	case '.jpg':
			    		$source_id = @imagecreatefromjpeg("$file_path");
			    		$thumb_source_id = $source_id;//@imageCreateFromJPEG("$thumb_path");
			    		
			    		break;
			    	case '.gif':
			    		$source_id = @imagecreatefromgif("$file_path");
			    		$thumb_source_id = $source_id;//@imageCreateFromGIF("$thumb_path");
			    		break;
			    }
			}catch(Exception $e){
				continue;
			}
			
			$dest_x = $source_x;
			$dest_y = $source_y;
			
			$height = 300;
			$width = 200;
			$shrink = 2;
			
			if($source_y > $height || $source_x > $width) {
				//need to resize
				if($source_y/$height > $source_x/$width) {
					$ratio = $height/$source_y;
					$dest_x = $ratio*$source_x;
					$dest_y = $height;
				}else{
					$ratio = $width/$source_x;
					$dest_y = $ratio*$source_y;
					$dest_x = $width;
				}
			}
			
			
			
			
			/* Create a new image object (not neccessarily true colour) */
			
			$target_id = @imagecreatetruecolor($dest_x, $dest_y);
			$target_thumb_id = @imagecreatetruecolor($dest_x/$shrink, $dest_y/$shrink);
			
			/* resize the original picture and copy it into the just created image
			  object. Because of the lack of space I had to wrap the parameters to
			several lines. I recommend putting them in one line in order keep your
			  code clean and readable */
			
			$target_pic = @imagecopyresampled($target_id, $source_id, 0, 0, 0, 0, $dest_x, $dest_y, $source_x, $source_y);
			$target_thumb_pic = @imagecopyresampled($target_thumb_id, $thumb_source_id, 0, 0, 0, 0, $dest_x/$shrink, $dest_y/$shrink, $source_x, $source_y);
			
			/* Create a jpeg with the quality of "$jpegqual" out of the
			  image object "$target_pic".
			  This will be saved as $targetfile */
			switch($file_type) {
		    	case '.png':
		    		@imagepng($target_id, "$file_path");
		    		@imagepng($target_thumb_id, "$thumb_path");
		    		break;
		    	case '.jpeg':
		    	case '.jpg':
		    		@imagejpeg($target_id, "$file_path", 100);
		    		@imagejpeg($target_thumb_id, "$thumb_path", 100);
		    		break;
		    	case '.gif':
		    		@imagegif($target_id, "$file_path");
		    		@imagegif($target_thumb_id, "$thumb_path");
		    		break;
		    }
			
			
			
			
			
			//
			
			$object = new Rush_Object();
			$object->load(Core::get_post('object_id'));
			$object->set('picture','i-'.$time.''.$file_type);
			$object->update();
			
			Core::redirect('rush/view/'.Core::get_post('object_id'));
			
		}
		
		
		function edit() {
			
			$object = new Rush_Object();
			$object->load(Core::get_post('object_id'));
			$object->fields();
			
			
			$fields = $object->get_fields();
			foreach($fields as $field) {
				if($field->is_many()) continue;
					
				$name = $field->field_name();
				$label = $field->field_label();
				$entered = Core::get_post($name);
				
				if($name == 'picture') continue;
				
				if(!$entered && $field->is_required()) $this->action->add_error($label.' Required.');
				$object->set($name,$entered);
			}
			
			if($this->action->is_valid()) {
				$object->update();
				
				//$this->action->add_error('fc');
				$this->action->redirect(Core::get_pref('URL_ROOT').'/rush/view/'.Core::get_post('object_id'));
			}
		}
		
		function set_status($id,$status) {
			
			$rush = new Rush_Object();
			$rush->load($id);
			$rush->set('status',$status);
			$rush->update();
			
		}
		
		function add() {
			
			$object = new Rush_Object();
			$object->fields();
			
			
			
			$fields = $object->get_fields();
			foreach($fields as $field) {
				if($field->is_many()) continue;
					
					
					$name = $field->field_name();
					$label = $field->field_label();
					$entered = Core::get_post($name);
					
					if(!$entered && $field->is_required()) $this->action->add_error($label.' Required.');
					$object->set($name,$entered);
			}

			//DAY OF THE WEEK!
			
			
			
			if($this->action->is_valid()) {
			
				$object->make();
				
				$q = "SELECT MAX(id) as max_id FROM rush_object";
				$result = Core::db()->query($q);
				$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
				$this->action->redirect('view/'.$row['max_id']);
				return;
				
				
				// make a note of the current working directory, relative to root.
				$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
				// make a note of the directory that will recieve the uploaded files
				$uploadsDirectory = Core::get_pref("FILE_ROOT").'/uploads/';

			
			
			    $code = $this->generate_password(100);
			    
			    $file_path =  $uploadsDirectory.'i-'.$code.'-'.strtolower($_FILES[$fieldname]['name'][$key]);
			    $thumb_path =  $uploadsDirectory.'t-i-'.$code.'-'.strtolower($_FILES[$fieldname]['name'][$key]);
			    
			    $file_type = substr($file_path, strrpos($file_path,'.'));
			   
			    $success = true;
			    
			    switch($file_type) {
			    
			    	case '.png':
			    	case '.PNG':
			    	
			    		break;
			    	case '.jpeg':
			    	case '.jpg':
			    	case '.JPEG':
			    	case '.JPG':
			    	
			    		break;
			    		
			    	case '.gif':
			    	case '.GIF':
			    	
			    		break;
			    		
			    	default:
			    		$success = false;
			    }
			    
			    /*
			    imagecreatefromgif()
			    imagecreatefromjpeg()
			    imagecreatefrompng()
			    imagecreatefromwbmp()
			   	*/
			   	
			   	
			    
		        if($success)
		        	move_uploaded_file($_FILES[$fieldname]['tmp_name'][$key], $file_path)
		        		or $success = false;
		        	
		        if($success) {
		        	
		        	
		        	$update_flag = true;
					
		        	//make a thumbnail
		        	@copy($file_path, $thumb_path);
		        	
		        	/*@move_uploaded_file($_FILES[$fieldname]['tmp_name'][$key], $thumb_path)
		        		or $success = false;*/
		        	
					/* Get the dimensions of the source picture */
					$picsize = @getimagesize("$file_path");
					$source_x = $picsize[0];
					$source_y  = $picsize[1];
					
					try{
					
						switch($file_type) {
					    	case '.png':
					    	case '.PNG':
					    		$source_id = @imageCreateFromPNG("$file_path");
					    		$thumb_source_id = $source_id;//@imageCreateFromPNG("$thumb_path");
					    		break;
					    	case '.jpeg':
					    	case '.jpg':
					    	case '.JPEG':
					    	case '.JPG':
					    		$source_id = @imageCreateFromJPEG("$file_path");
					    		$thumb_source_id = $source_id;//@imageCreateFromJPEG("$thumb_path");
					    		break;
					    	case '.gif':
					    	case '.GIF':
					    		$source_id = @imageCreateFromGIF("$file_path");
					    		$thumb_source_id = $source_id;//@imageCreateFromGIF("$thumb_path");
					    		break;
					    }
					}catch(Exception $e){
						continue;
					}
					
					$dest_x = $source_x;
					$dest_y = $source_y;
					
					$height = $this->height;
					$width = $this->width;
					$shrink = $this->shrink;
					
					if($source_y > $height || $source_x > $width) {
						//need to resize
						if($source_y/$height > $source_x/$width) {
							$ratio = $height/$source_y;
							$dest_x = $ratio*$source_x;
							$dest_y = $height;
						}else{
							$ratio = $width/$source_x;
							$dest_y = $ratio*$source_y;
							$dest_x = $width;
						}
					}
					
					/* Create a new image object (not neccessarily true colour) */
					
					$target_id = @imagecreatetruecolor($dest_x, $dest_y);
					$target_thumb_id = @imagecreatetruecolor($dest_x/$shrink, $dest_y/$shrink);
					
					/* resize the original picture and copy it into the just created image
					  object. Because of the lack of space I had to wrap the parameters to
					several lines. I recommend putting them in one line in order keep your
					  code clean and readable */
					
					$target_pic = @imagecopyresampled($target_id, $source_id, 0, 0, 0, 0, $dest_x, $dest_y, $source_x, $source_y);
					$target_thumb_pic = @imagecopyresampled($target_thumb_id, $thumb_source_id, 0, 0, 0, 0, $dest_x/$shrink, $dest_y/$shrink, $source_x, $source_y);
					
					/* Create a jpeg with the quality of "$jpegqual" out of the
					  image object "$target_pic".
					  This will be saved as $targetfile */
					switch($file_type) {
				    	case '.png':
				    	case '.PNG':
				    		@imagepng($target_id, "$file_path");
				    		@imagepng($target_thumb_id, "$thumb_path");
				    		break;
				    	case '.jpeg':
				    	case '.jpg':
				    	case '.JPEG':
				    	case '.JPG':
				    		@imagejpeg($target_id, "$file_path", 100);
				    		@imagejpeg($target_thumb_id, "$thumb_path", 100);
				    		break;
				    	case '.gif':
				    	case '.GIF':
				    		@imagegif($target_id, "$file_path");
				    		@imagegif($target_thumb_id, "$thumb_path");
				    		break;
				    }

					$file_path = Core::get_pref('URL_ROOT').'uploads/i-'.$code.'-'.strtolower($_FILES[$fieldname]['name'][$key]);
					if($multiple) {
		        		$q = "INSERT INTO ".$this->table_name." (id, upload_time, code, photo_path) VALUES ($singuler_id, NOW(), '$code', '$file_path')";
		        	}else{
		        		//update photo_path and upload_time to the object with the singular id
		        		$q = "UPDATE ".$this->table_name." SET photo_path = '$file_path', upload_time = NOW(), code = '$code' WHERE id = $singuler_id";
		        	}
		        	Core::db()->query($q);
		        }
			
			
				
				
			}
		
		}
		
		
	}
?>
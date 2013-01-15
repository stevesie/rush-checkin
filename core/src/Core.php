<?php
	class Core {
		
		private static $instance;
		
		private static $url;
		private static $user;
		
		private static $preferences;
		private static $router;

		private static $db;
		
		private static $cache = array();
		
		function set_cache($field, $value) {
			self::$cache[$field] = $value;
		}
		
		function get_cache($field) {
			if(!isset(self::$cache[$field])) return false;
			return self::$cache[$field];
		}
		
		private function __construct($prefs) {
			spl_autoload_register('Core::autoload');
			if(isset($_SERVER['HTTP_HOST'])) {
				$host = $_SERVER['HTTP_HOST'];
				$parts = split('[.]',$host);
				if($host != "localhost" && $host != "bin" && sizeof($parts) < 3){
					header("Location: http://www.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
					return;
				}
				@session_start();
			}
			self::$preferences = $prefs;
			if($prefs["DSN"]) {
				
				self::include_ext('MDB2/MDB2.php');
				self::$db = MDB2::factory($this->get_pref("DSN"));
				if(PEAR::isError(self::$db)) die("Error while connecting to database: " . self::$db->getDebugInfo());
			}
		}
		
		function redirect($url) {
			header("Location: ".Core::get_pref("SERVER_ROOT").$url);
			exit;
		}
		
		static function autoload($class) {
			$path_arr = explode("_",strtolower($class));
			$path_size = sizeof($path_arr);
			
			$full_path = self::get_pref("FILE_ROOT")."/";
			
			if($path_arr[0] != 'core') {
				switch($path_arr[1]) {
					case 'layout':
						$full_path .= 'layout/'.$path_arr[0].'/src/'.ucfirst($path_arr[0]).'_'.ucfirst($path_arr[1]).'.php';
						break;
					case 'lib':
						$full_path .= 'lib/'.$path_arr[0].'/src/'.ucfirst($path_arr[0]).'_'.ucfirst($path_arr[1]).'.php';
						break;
					default:
						$full_path .= 'apps/'.$path_arr[0].'/src/';
						foreach($path_arr as $name) {
							$full_path .= ucfirst($name).'_';
						}
						$full_path = substr($full_path, 0, strlen($full_path)-1);
						$full_path .= '.php';
				}
			}else{
				$full_path .= 'core/src/'.$class.'.php';
			}
			if(file_exists($full_path))
				require($full_path);
		}
		
		function execute_controller($module) {
			$controller_class_name = ucfirst($module)."_Control";
			if(!class_exists($controller_class_name)) {
				$control = new Core_Control($module);
				$view_class_name = ucfirst($module).'_View';
				if(!class_exists($view_class_name))
					return false;
				$view = new $view_class_name;
				$view->set_controller($control);
				$view->render();
				return true;
			}
			$controller = new $controller_class_name;
			$controller->set_name($module);
			
			$controller->execute();
			return true;
		}
		
		function include_ext($pathway) {
			include(self::get_pref("FILE_ROOT")."/ext/$pathway");
		}
		
		function run() {
			$url = $_SERVER['REQUEST_URI'];
			$try_url = substr($url, 0, strpos($url,'?'));
			if($try_url != '')
				$url = $try_url;
			$root_preg = str_replace("/","\/",self::get_pref("URL_ROOT"));
			$url = preg_replace("/^".$root_preg."/","",$url);
			self::$url = preg_replace("/^\//","",$url);

			$url_arr = explode("/",self::$url);
			$app = $url_arr[0];
			
			$user_model = new User_Model();
			self::$user = $user_model->get_user();
			
			$success = false;
			if($app != '') $success = self::execute_controller($app);
			
			if(!$success)	
				self::execute_controller(self::get_pref('DEFAULT')); 
		}		
		
		function get_url() {
			return self::$url;
		}

		function instance($prefs) {
			if(!isset(self::$instance)) {
				self::$instance = new self($prefs);
			}
			return self::$instance;
		}
		
		private function __clone() {}

		function db() {
			return self::$db;
		}
		
		static function get_pref($key) {
			return self::$preferences[$key];
		}
		
		function get_post($key) {
			if(!isset($_POST[$key])) return false;
			return trim(stripslashes($_POST[$key]));
		}
				
		function get_post_html($key) {
			return trim(htmlspecialchars(stripslashes($_POST[$key])));
		}
		
		function user() {
			return self::$user;
		}
	}
?>
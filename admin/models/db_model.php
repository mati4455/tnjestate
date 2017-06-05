<?php
	class db {
		private static $instance = NULL;
		
		private function __construct() {
			/*** maybe set the db name here later ***/
		}
		
		public static function getInstance() {
			$config = parse_ini_file($site_path . 'includes/config.ini.php', true);
			define ('__MYSQL', serialize($config['mysql']));

			if (!self::$instance) {
				$mysql = $config['mysql'];
				$host = $mysql['host'];
				$database = $mysql['database'];
				$user = $mysql['user'];
				$pass = $mysql['pass'];

				self::$instance = new PDO("mysql:host=$host;dbname=$database", $user, $pass);;
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			}
			return self::$instance;
		}

		public static function getInstanceSQLITE() {
			if (!self::$instance) {
				self::$instance = new PDO("sqlite:/database/database.sqlite");
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			}
			return self::$instance;
		}
		
		private function __clone(){
			
		}
	} /*** end of class ***/
?>

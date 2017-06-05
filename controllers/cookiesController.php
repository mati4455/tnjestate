<?php
	Class cookiesController Extends baseController {
	
		public function index() {
			$act = $_GET['action'];
			
			if (method_exists($this, $act)) {
				$this->$act();
				return false;
			}
		}

		private function zgoda() { 
 			Core::returnJSON(
 				array ('status' => setcookie("cookies", 1, time()+3600*24*30*12, "/", DOMAIN))
 			);
		}

		private function wychodze() {
			header ('location: http://google.pl');
		}
	}
?>

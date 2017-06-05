<?php
	Class autoryzacjaController Extends baseController {
		public function index() {
			Core::disableAnalytics();
			
			$act = $_GET['action']; 

			//$this->view->css = 'logowanie';
			$this->view->js = 'sha512.js';
			
			if (method_exists($this, $act)) {
				$this->$act();
				return false;
			}
			$this->logowanie();
		}

		private function logowanie() {
			$loginModel = $this->loadModel('autoryzacja');
			$this->view->title = 'Logowanie';
			$spr = $loginModel->login_check($this->db);
			if ($spr == true) {
				header ('Location: ' . __SITE_PATH);                
			} else {
				$this->view->show('formularz');                
			}
		}

		private function zaloguj() {
			$login = $_POST['login'];
			$password = filter_var($_POST['p'], FILTER_SANITIZE_STRING);

			$loginModel = $this->loadModel('autoryzacja');
			$spr = $loginModel->login($login, $password);

			if ($spr == 1) {
				header ('Location: ' . __SITE_PATH);
			} else {
				$this->view->message =  $loginModel->error($spr);
				$this->logowanie();
			}
		}

		private function wyloguj($redirect = true) {
			$loginModel = $this->loadModel('autoryzacja');  
			$loginModel->wyloguj($redirect);
		}

		private function rejestracja() { 
			$loginModel = $this->loadModel('autoryzacja');
			$this->view->title = 'Rejestracja';
			$spr = $loginModel->login_check($this->db);

			Session::set('captcha', simple_php_captcha());

			if ($spr == false)
				$this->view->show('rejestracja');
			else
				header ('Location: ' . __SITE_PATH);                
		}

		private function rejestruj() {  
			$m = unserialize(MESSAGES);
			$loginModel = $this->loadModel('autoryzacja');  
			$odp = $loginModel->createAccount();
			$this->view->message = $odp;

			if ($odp == '<p class="bg-primary">Konto zostało utworzone! To konto wymaga aktywacji poprzez link wysłany na podany adres e-mail.</p>')
				$this->view->show('formularz'); // logowanie
			else {
				Session::set('captcha', simple_php_captcha());
				$this->view->show('rejestracja');
			}
		}

		private function przypomnijHaslo() {
			$loginModel = $this->loadModel('autoryzacja');
			$spr = $loginModel->login_check($this->db);
			if ($spr == true) {
				header ('Location: ' . __SITE_PATH);                
			} else {
				$this->view->show('odzyskiwanie');
			}
		}

		private function aktywacja() {
			$loginModel = $this->loadModel('autoryzacja');  
			$this->view->message = $loginModel->aktywujKonto();
			$this->view->show('formularz');
		}

		private function odzyskaj() {
			$loginModel = $this->loadModel('autoryzacja');  	
			$this->view->message = Core::primary_message($loginModel->linkOdzyskiwanieHasla());
			$this->view->show();
		}

		private function haslo() {
			$loginModel = $this->loadModel('autoryzacja');  
			$h = $_GET['h'];
			if ($loginModel->sprawdzHash($h)) {				
				$this->view->hash = $h;
				$this->view->show('formularz_haslo');
			} else {
				$m = unserialize(MESSAGES);
				$this->view->message = Core::primary_message($m[113]);
				$this->view->show();
			}
		}

		private function zapiszHaslo() {
			$loginModel = $this->loadModel('autoryzacja');  
			$this->view->message = Core::primary_message($loginModel->zapiszHaslo());
			$this->wyloguj(false);
			//$this->view->show('formularz');
		}

	}
?>

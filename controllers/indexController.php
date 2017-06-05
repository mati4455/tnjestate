<?php
	Class indexController Extends baseController {
	
		public function index() {
			$act = getUrl($_GET['action']);
			if (method_exists($this, $act)) {
				$this->$act();
			} else {
				$this->home();
			}
		}

		private function home() {
			$act = getUrl($_GET['action']);
			$model = $this->loadModel('search');
			$this->view->search_form = $model->getForm($_GET['action'], $_GET['id']);


			$this->view->show('index');
		}

		private function background() {
			/*
			$stmt = $this->db->prepare('SELECT `value` FROM `settings` WHERE `key` = :key');
			$stmt->bindValue(':key', 'background', PDO::PARAM_STR);
			$stmt->execute();
			$a = $stmt->fetch();
			$file = '/uploads/background.txt';
			$img = trim(file_get_contents($file));
			$url = '/uploads/img/' . $img;
			header ('location: ' . $url);
			*/
		}
	}
?> 

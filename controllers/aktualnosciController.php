<?php
	Class aktualnosciController Extends baseController {
		private $model;

		public function index() {
			$act = funkcja($_GET['action']);

			$this->model = $this->loadModel('silnik');

			if (method_exists($this, $act)) {
				$this->$act();
			} else {
				$this->listaAktualnosci();
			}
		}

		public function listaAktualnosci() {
			$this->view->show('lista');
		}

	}
?>
	

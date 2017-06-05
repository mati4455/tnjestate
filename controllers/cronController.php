<?php
	Class cronController Extends baseController {
		private $model;

		public function index() {
			if ($_GET['token'] != '382e0360e4eb7b70034fbaa69bec5786') die('dostęp zabroniony');

			$act = funkcja($_GET['action']);

			$this->model = $this->loadModel('cron');

			if (method_exists($this, $act)) {
				$this->$act();
			}
		}

		private function wyswietlenia() {
			$this->model->resetujDzienneWyswietlenia();
		}
	}
?>
	
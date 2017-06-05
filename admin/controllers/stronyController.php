<?php
	Class stronyController Extends baseController {
		private $model;

		public function index() {
			Core::loginRequired();
			$act = getUrl($_GET['action']);

			$this->model = $this->loadModel('strony');

			if (method_exists($this, $act)) {
				$this->$act();
			} else {
				$this->lista();
			}
		}

		public function logged() {
			ob_clean();
    		echo trim( logged ? '1' : '0'); exit;
    	}

    	public function lista() {
    		$this->setTitle('Strony statyczne');
    		$this->view->strony = $this->model->getPages();
    		$this->view->show('lista');
    	}

    	public function edytuj() {
    		$this->setTitle('Edycja strony');
    		$this->view->strona = $this->model->getPage($_GET['id']);
    		$this->view->show('strona');
    	}

    	public function zapisz() {    		
    		$this->setTitle('Edycja strony');
    		$this->setMessage( $this->model->savePage() );
    		$this->view->strona = $this->model->getPage($_POST['dane']['id']);
    		$this->view->show('strona');
    	}

    }
?>
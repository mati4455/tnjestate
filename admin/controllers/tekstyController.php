<?php
    Class tekstyController Extends baseController {
        private $model;
    
        public function index() {
            $act = funkcja($_GET['action']);

            $this->model = $this->loadModel('teksty');

            if (method_exists($this, $act)) {
                $this->$act();
            } else {
                $this->listaTekstow();
            }
        }

        public function listaTekstow() {
            $this->view->title = 'Teksty';
            $this->view->teksty = $this->model->getTexts();
            $this->view->show('lista');
        }

        public function zapisz() {
            $this->view->message = $this->model->saveTexts();
            $this->listaTekstow();
        }
    
    }
?>

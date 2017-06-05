<?php
    Class kontoController Extends baseController {
        private $model;
    
        public function index() {
            $act = funkcja($_GET['action']);
            $this->model = $this->loadModel('konto');

            if (method_exists($this, $act)) {
                $this->$act();
            } else {
                $this->haslo();
            }
        }

        public function haslo() {
            $this->view->title = 'Zmiana hasła';
            $this->view->js = "sha512.js";
            $this->view->show('/autoryzacja/zmiana_hasla');
        }

        public function zapisz_haslo() {
            $this->setMessage( $this->model->changePassword() );
            $this->haslo();
        }
    
    }
?>

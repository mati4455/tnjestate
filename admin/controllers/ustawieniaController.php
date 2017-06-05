<?php
    Class ustawieniaController Extends baseController {
        private $model;
    
        public function index() {
            $act = getUrl($_GET['action']);

            $this->model = $this->loadModel('settings');

            if (method_exists($this, $act)) {
                $this->$act();
            } else {
                header('location: /');
            }
        }

        private function tlo() {
            $this->setTitle('Zmiana tła');
            $this->view->form = $this->model->backgroundForm();
            $this->view->show('background');
        }

        private function zapisz_tlo() {            
            $this->setMessage(Core::info_message($this->model->saveBackground()));
            $this->tlo();
        }
    
    }
?>

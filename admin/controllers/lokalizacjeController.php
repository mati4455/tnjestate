<?php
    Class lokalizacjeController Extends baseController {
        private $model;
    
        public function index() {
            $act = funkcja($_GET['action']);

            $this->model = $this->loadModel('lokalizacje');

            if (method_exists($this, $act)) {
                $this->$act();
            } else {
                $this->listaLokalizacji();
            }
        }

        public function listaLokalizacji() {
            $this->view->title = 'Lokalizacje';
            $this->view->dane = $this->model->getLocations();
            $this->view->show('lista');
        }

        public function osiedle() {
            if ($_GET['id'] == 'zapisz') {
                $this->setMessage($this->model->saveDistrict());
            } else if ($_GET['id'] == 'usun') {
                $this->setMessage($this->model->deleteDistrict($_GET['i']));
                $this->listaLokalizacji(); return;
            }

            $this->view->miasta = $this->model->getCities();
            $this->view->osiedle = $this->model->getDistrict($_GET['id']);
            $this->view->show('form_district');
        }

        public function miasto() { 
            if ($_GET['id'] == 'zapisz') {
                $this->setMessage($this->model->saveCity());
            } else if ($_GET['id'] == 'usun') {
                $this->setMessage($this->model->deleteCity($_GET['i']));
                $this->listaLokalizacji(); return;
            }

            $this->view->miasto = $this->model->getCity($_GET['id']);
            $this->view->show('form_city');
        }

        public function dodaj() {
            $this->view->dane = Core::mapPOST('dane');
            $this->view->show('formularz');
        }

        public function edytuj() {
            $this->view->dane = $this->model->getLocation($_GET['id']);
            $this->view->show('formularz');
        }

        public function zapisz() {
            $this->view->message = $this->model->saveLocations();
            $this->listaLokalizacji();
        }


    
    }
?>

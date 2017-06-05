<?php
    Class agentController Extends baseController {
        private $model;
    
        public function index() {
            $act = funkcja($_GET['action']);

            $this->model = $this->loadModel('agent');

            if (method_exists($this, $act)) {
                $this->$act();
            } else {
                $this->lista();
            }
        }

        public function lista() {
            $this->view->title = 'Agenci';
            $this->view->dane = $this->model->getAgents();
            $this->view->show('lista');
        }

        public function nowy() {
            $this->view->title = 'Nowy agenta';
            $this->view->dane = Core::mapPOST('agent');
            $this->view->show('formularz'); 
        }

        public function edytuj() {
            $this->view->title = 'Edycja agenta';
            $this->view->dane = $this->model->getAgent($_GET['id']);
            $this->view->show('formularz');            
        }

        public function zapisz() {
            $this->setMessage( $this->model->save() );
            $this->lista();
        }

        public function usun() {
            $this->model->delete($_GET['id']);
            $this->lista();
        }
    
    }
?>

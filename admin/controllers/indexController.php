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
        	header('location: ' . get_url('oferty'));
            $this->view->show('index');
        }
    
    }
?>

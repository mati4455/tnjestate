<?php
    Class stronyController Extends baseController {
        private $model;
        private $texts;
        private $search;

        public function index() {
            $this->texts = unserialize(TEXTS);

            $act = getUrl($_GET['action']);

            $this->model = $this->loadModel('silnik');
            $this->search = $this->loadModel('search');

            if (method_exists($this, $act)) {
                $this->$act();
            } else {
                $this->loadPage();
            }
        }
        
        public function logged() {
            ob_clean();
            echo trim( logged ? '1' : '0'); exit;
        }

        public function error404() {
            $this->view->show('/error404/error404');
        }   

        public function loadPage() {
            $page = $_GET['action'];
            $data = $this->model->getPage($page);

            $this->setMeta($data->title, $data->description, $data->keywords);
            $prep = $data->tresc;

            $this->view->class = $data->klasa;

            preg_match_all("^\[(.*?)\]^", $prep, $elements, PREG_PATTERN_ORDER);

            foreach ($elements[1] as $k => $v) {
                $fun = 'get_' . $v;
                $prep = str_replace('['.$v.']', $this->$fun(), $prep);
            }

            $this->view->page = $prep;

            Session::set('captcha', simple_php_captcha());

            $this->view->show('strona');
        }


        public function wyslij_oferte() {
            if ($this->model->zapisz_oferte())
                $this->setMessage(Core::primary_message($this->texts['oferta_wyslana']));
            else 
                $this->setMessage(Core::error_message($this->texts['wystapil_problem']));
            
            $this->view->show();
        }

        public function wyslij_zapytanie() {            
            if ($this->model->wyslij_wiadomosc())
                $this->setMessage(Core::primary_message($this->texts['wiadomosc_wyslana']));
            else 
                $this->setMessage(Core::error_message($this->texts['wystapil_problem']));
            
            $this->view->show();
        }


        /*
         * getters
         */
        public function get_kontakt_form() {
            return $this->model->getContantForm();
        }

        public function get_zglos_oferte() {
            return $this->model->getOffertForm($this->search);
        }
    }
?>
    

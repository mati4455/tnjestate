<?php
	Class ofertyController Extends baseController {
		private $model;

		public function index() {
			Core::loginRequired();
			$act = getUrl($_GET['action']);

			$this->model = $this->loadModel('oferty');
			$this->view->oferta = new Oferta($this->db);


			if (method_exists($this, $act)) {
				$this->$act();
			} else {
				$this->listaOfert();
			}
		}
		public function logged() {
			ob_clean();
    		echo trim( logged ? '1' : '0'); exit;
    	}

		public function listaOfert() {
			$this->view->tytul = 'Wszystkie oferty';
			$this->view->kat = $data == '';

			$data = $_GET['rodzaj'] .'-'. $_GET['typ'] .'-'. $_GET['lokalizacja'];

			$warunek = $this->model->przygotujZapytanie($data);
			$iloscElementow = $this->model->iloscElementow($warunek);
			$oferty = $this->view->oferty = $this->model->pobierzOferty($warunek, $iloscElementow);
			$this->view->nawigacja = $this->model->nawigacja($iloscElementow);
			
			$this->view->czyPuste = empty($oferty) ? $this->texts['brak_wynikow'] : '';

			$this->view->show('lista');
		}

		public function dodaj() {			
			Core::loginRequired();
			Session::set('captcha', simple_php_captcha());

			$this->view->title = 'Dodaj ofertę';
			$this->view->dane = Core::mapPOST('dane');
			$this->view->lang = Core::mapPOST('lang');

			$this->view->show('formularz');
		}

		public function edytuj() {
			Core::loginRequired();
			Session::set('captcha', simple_php_captcha());

			$this->view->title = 'Edytuj ofertę';
			$this->view->dane = $this->model->pobierzOferte($_GET['id']);
			$this->view->lang = $this->model->pobierzTlumaczenia($_GET['id']);
			$this->view->img_form = $this->model->formularzZdjec($_GET['id']);
			$this->view->images = $this->model->getImages($_GET['id']);

			$this->view->show('formularz');
		}

		public function zapisz() {
			Core::loginRequired();
			$odp = $this->model->zapiszOferte();

			if (is_numeric($odp)) {
				$this->setMessage(Core::info_message('Zapisano!'));
				$_GET['id'] = $odp;
				header('location: ' . get_url('oferty/edytuj/' . $odp));
				$this->edytuj();
			} else {
				$this->view->message = $odp;
				$this->dodaj();
			}
		}
 
		public function zapisz_zdjecia() {
			$this->model->zapiszZdjecia();
		}

		private function zapisz_kolejnosc() {
			$this->model->zapiszKolejnosc();
		}
		
		private function usunZdjecie() {
			$this->model->usunZdjecie($_GET['id']);
		}

		private function usunOferte() {
			$this->model->deleteOffer($_GET['id']);
			$this->setMessage(Core::info_message('Oferta została usunięta'));
			$this->listaOfert();
		}
	}
?>
	

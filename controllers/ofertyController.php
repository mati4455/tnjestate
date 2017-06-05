<?php
	Class ofertyController Extends baseController {
		private $model;
		private $texts;
		private $oferta;
		private $temp_title = '';

		public function index() {
			$act = getUrl($_GET['action']);

			$this->texts = unserialize(TEXTS);
			$this->model = $this->loadModel('oferty');

			$m = $this->loadModel('search');
			$this->view->search_form = $m->getForm($_GET['action'], $_GET['id']);
			$this->view->types_menu = $this->model->getTypesMenu();
			$this->view->oferta = $this->oferta = new Oferta($this->db);

			if (method_exists($this, $act)) {
				$this->$act();
			} else {
				$this->oferty();
			}
		}
		public function logged() {
			ob_clean();
    		echo trim( logged ? '1' : '0'); exit;
    	}

		public function oferty($data = '') {
			$this->view->kat = $data == '';

			$data = empty($data) ? $_GET['action'] : $data;

			$warunek = $this->model->przygotujZapytanie($data);
			$iloscElementow = $this->model->iloscElementow($warunek);
			$oferty = $this->view->oferty = $this->model->pobierzOferty($warunek, $iloscElementow);
			$this->view->nawigacja = $this->model->nawigacja($iloscElementow);
			
			$this->view->czyPuste = empty($oferty) ? $this->texts['brak_wynikow'] : '';

			$this->setTitle( empty($this->temp_title) ? $this->texts['wynik_wyszukiwania'] : $this->temp_title );

			$this->view->show('lista');
		}


		public function wyswietl() {
			$id = $_GET['id'];
			$dane = $this->model->pobierzOferte($id);
			$this->view->oferta_full = $this->oferta->getFullOffer($dane);

			$this->setMeta($dane->title, $dane->tresc, $dane->keywords);

			if (empty($dane)) {
				$this->view->message = $this->texts['brak_wynikow'];
				$this->view->show();
			} else 
				$this->view->show('oferta');
		}

		public function ofertyStale() {
			$this->oferty( $this->model->prepareData() );
		}

		public function sprzedaz() {
			$this->setTitle( $this->temp_title =ucfirst( $this->texts['sprzedaz']));
			$this->ofertyStale();
		}

		public function wynajem() { 
			$this->setTitle( $this->temp_title = ucfirst( $this->texts['wynajem']) );
			$this->ofertyStale(); 
		}

		public function specjalne() { 
			$this->setTitle( $this->temp_title = ucfirst( $this->texts['tylkounas']));
			$_GET['wyr'] = 1; 
			$this->oferty('-'.$this->model->getIdFromName($_GET['id'])); 
		}
	}
?>
	

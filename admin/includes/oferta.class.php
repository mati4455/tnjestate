<?php
Class Oferta {
	private $texts;
	private $categories;
	private $locations;
	private $types;
	private $db;

	public function __construct($db) {
		$this->texts = unserialize(TEXTS);
		$this->categories = unserialize(CATEGORIES);
		$this->types = unserialize(TYPES);
		$this->locations = unserialize(LOCATIONS);
		$this->db = $db;
	}

	/**
	 * prepare film to embed on website
	 * @param  Object $v Object with all information about film
	 * @return Object    Object with all confirmed and prepared information about film
	 */
	private function prepareEmbed($v) {
		$m = new stdClass();
		$folder = ($v->id % 100 + 1);

		$m->image = empty($v->url) ? EMPTY_IMAGE : IMAGES_URL . 'min/' . $folder .'/' . $v->url;

		/* date */
		$m->id = $v->id; 
		$m->rodzaj = $this->categories[$v->rodzaj_id]->title;
		$m->rodzaj_id = $v->rodzaj_id;
		$m->typ = $this->types[$v->typ_id]->title;
		$m->typ_id = $v->typ_id;
		$m->kategoria = $m->rodzaj . ' / ' . $m->typ;
		$m->lokalizacja = $this->locations[$v->lokalizacja_id]->miasto;
		$m->lokalizacja_id = $v->lokalizacja_id;
		$m->dzielnica = $this->locations[$v->lokalizacja_id]->osiedle;
		if (empty($m->dzielnica)) $m->dzielnica = '-';
		$m->lok = trim($this->locations[$v->lokalizacja_id]->title);
		$m->specjalna = $v->specjalna > 0;
		$m->ulica = $v->ulica;
		$m->metraz = $v->metraz;
		$m->pietro = $v->pietro == 0 ? $this->texts['parter'] : $v->pietro;
		$m->pieter = $v->pieter == 0 ? '-' : $v->pieter;
		$m->pokoje = $v->pokoje;
		$m->cena_full = $v->cena;
		$m->cena = Core::sep1000($v->cena);
		$m->tytul = $v->title;
		$m->opis = $v->tresc;

		$m->agent = $v->imie_nazwisko;
		$m->telefon = $v->telefon;
		$m->licencja = $v->licencja;

		/* links */
		$m->link =  get_url('oferty/edytuj/' . $v->id);

		/**
		 * clear cache
		 */
		unset($categories, $locations, $types);

		return $m;
	}


	public function getListElement($d, $kategoria) {
		$m = $this->prepareEmbed($d);
		if (!$kategoria) 
			$m->kategoria = empty($_GET['id']) ? $m->typ : '';
		if (!empty($m->kategoria)) $m->kategoria = '<div class="k">'.$m->kategoria.'</div>';

		$html = <<<EOF
			<div class="e">
				<a href="{$m->link}" title="{$this->texts['wiecej_info']}">
					<div class="row">
						<div class="col-xs-5 col-sm-5 col-md-4">
							<img class="img-responsive lazy" data-src="{$m->image}" />
						</div>
						<div class="col-xs-7 col-sm-7 col-md-8">
							<div class="f"><span>{$this->texts['lokalizacja']}:</span> $m->lokalizacja</div>
							<div class="f"><span>{$this->texts['dzielnica']}:</span> $m->dzielnica</div>
							<div class="f"><span>{$this->texts['ulica']}:</span> $m->ulica</div>
							<div class="f"><span>{$this->texts['metraz']}:</span> $m->metraz m<sup>2</sup></div>
							<div class="f"><span>{$this->texts['liczba_pokoi']}:</span> $m->pokoje</div>
							<div class="f"><span>{$this->texts['cena']}:</span> <span class="price">$m->cena</span></div>
							{$m->kategoria}
						</div>
					</div>
				</a>
			</div>
EOF;

		return $html;
	}

	public function getFullOffer($d) {
		$m = $this->prepareEmbed($d);
		$gallery = $this->getGallery($d->id);
		$html = <<<EOF
			<div class="oferta row">
				<div class="col-xs-12 col-sm-6">
					<div class="head">
						<div class="pull-left">{$m->lok}, {$m->ulica}</div>
						<div class="price b pull-right">{$m->cena}</div>
						<div class="clearboth"></div>
					</div>
					{$gallery}
				</div>
				<div class="col-xs-12 col-sm-6 ">
					<h3 class="subtitle">{$this->texts['info_podstawowe']}</h3>
					<div class="e">
						<div class="f"><span>{$this->texts['typ_nieruchomosci']}:</span> $m->rodzaj</div>
						<div class="f"><span>{$this->texts['metraz']}:</span> $m->metraz m<sup>2</sup></div>
						<div class="f"><span>{$this->texts['pietro']}:</span> $m->pietro</div>
						<div class="f"><span>{$this->texts['pieter']}:</span> $m->pieter</div>
						<div class="f"><span>{$this->texts['liczba_pokoi']}:</span> $m->pokoje</div>
					</div>

					<h3 class="subtitle">{$this->texts['opis_nieruchomosci']}</h3>
					<div>{$m->opis}</div>
					
					<h3 class="subtitle">{$this->texts['kontakt']}</h3>
					<div>{$m->agent}</div>
					<div><span class="orange glyphicon glyphicon-earphone"></span> {$m->telefon}</div>
					<div>{$this->texts['licencja']}: {$m->licencja}</div>
				</div>
			</div>
EOF;

		return $html;
	}

	private function getGallery($id) {
		$images = $this->getImages($id);
		$url = IMAGES_URL;
		$folder = ($id % 100 + 1);
		$first_url = empty($images[0]->url) ? EMPTY_IMAGE : $url . 'max/' . $folder  . '/' . $images[0]->url;
		$html = '<div class="gallery" data-url="'.$url.'">
			<div class="preview row">
				<div class="nav">
					<a href="#" class="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
					<a href="#" class="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
				</div>
				<div class="col-xs-12">
					<div class="loader"></div>
					<img class="img-responsive" src="'.$first_url.'" />
				</div>
			</div>
			<div class="thumbnails row">
		';

		foreach ($images as $k => $v) {
			$klasa = $k % 3 == 0 ? ' clearboth' : '';
			$img = $url . 'min/' . $folder  . '/' . $v->url;
			$full_img = $url . 'max/' . $folder .'/' . $v->url;
			$image = '<div class="img col-xs-4 '.$klasa.'">
				<a class="colorbox" href="'.$full_img.'" data-url="'.$folder.'/'.$v->url.'"><img class="img-responsive lazy" data-src="'.$img.'" /></a>
			</div>';
			$html .= $image;

		}

		$html .= '</div></div>';
		return $html;
	}

	private function getImages($id) {		
		$stmt = $this->db->prepare('SELECT url FROM zdjecia WHERE oferta_id = :id ORDER BY kolejnosc ASC');
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_CLASS);
	}
	
}
?>
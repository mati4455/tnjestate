<?php
	class SilnikModel extends baseModel {

		public function getLocations() {
			$stmt = $this->db->prepare('SELECT o.id, l.miasto, o.osiedle, CONCAT(l.miasto, " ", o.osiedle) as title FROM lokalizacje l
				INNER JOIN `osiedla` o ON l.city_id = o.city_id
				ORDER BY l.miasto ASC, o.osiedle ASC');
			$stmt->execute();
			$t = $stmt->fetchAll(PDO::FETCH_CLASS);
			$r = Array();
			foreach ($t as $k => $v) {
				$r[$v->id] = $v;
			}
			//usort($r);
			unset($t);
			return $r;
		}


		public function getCategories() {
			$stmt = $this->db->prepare('SELECT p.*, pt.title FROM `rodzaje` p
		        INNER JOIN `rodzaje_tlumaczenia` pt ON p.id = pt.rodzaj_id
		        WHERE p.type = :type AND pt.language_code = :lang');
			$stmt->bindValue(':type', CATEGORY_ID, PDO::PARAM_INT);
			$stmt->bindValue(':lang', LANG, PDO::PARAM_STR);
			$stmt->execute();
			$t = $stmt->fetchAll(PDO::FETCH_CLASS);
			$r = Array();
			foreach ($t as $k => $v) {
				$r[$v->id] = $v;
			}
			unset($t);
			return $r;
		}

		public function getTypes() {
			$stmt = $this->db->prepare('SELECT p.*, pt.title FROM `rodzaje` p
		        INNER JOIN `rodzaje_tlumaczenia` pt ON p.id = pt.rodzaj_id
		        WHERE p.type = :type AND pt.language_code = :lang');
			$stmt->bindValue(':type', TYPE_ID, PDO::PARAM_INT);
			$stmt->bindValue(':lang', LANG, PDO::PARAM_STR);
			$stmt->execute();
			$t = $stmt->fetchAll(PDO::FETCH_CLASS);
			$r = Array();
			foreach ($t as $k => $v) {
				$r[$v->id] = $v;
			}
			unset($t);
			return $r;
		}

		public function getTexts() {
			$lang = constant('LANG');

			$tmp = ($lang == 'pl') ? 't_pl' : 't_pl, t_' . $lang;
			$query = 'SELECT id, short, ' . $tmp .' FROM teksty';

			$stmt = $this->db->prepare($query);
			$stmt->execute();

			$return = Array();
			foreach ($stmt->fetchAll() as $k => $v) {
				//$return[$v[1]]['pl'] = $v[2];
				//if ($lang != 'pl') $return[$v[1]][$lang] = empty($v['t_' . $lang]) ? $v[2] : $v['t_' . $lang];
				$return[$v[1]] = empty($v['t_' . $lang]) ? $v[2] : $v['t_' . $lang];
			}
			return $return;
		}

		public function getMenu($id) {
			$stmt = $this->db->prepare('SELECT p.*, pt.title, ps.* FROM `menu` p
		        INNER JOIN `menu_tlumaczenia` pt ON p.id = pt.rodzaj_id
		        INNER JOIN `strony` ps On p.strona_id = ps.strona_id
		        WHERE p.type = :type AND pt.language_code = :lang ORDER BY p.kolejnosc ASC');
			$stmt->bindValue(':type', $id, PDO::PARAM_INT);
			$stmt->bindValue(':lang', LANG, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function getPage($id) {
			$stmt = $this->db->prepare('SELECT p.*, pt.title, pt.tresc, pt.keywords, pt.description FROM `strony` p
		        INNER JOIN `strony_tlumaczenia` pt ON p.strona_id = pt.strona_id
		        WHERE p.short = :id AND pt.language_code = :lang');
			$stmt->bindValue(':id', $id, PDO::PARAM_STR);
			$stmt->bindValue(':lang', LANG, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
		}

		public function zapisz_oferte() {
			$dane = Core::mapPOST('dane');
			$texts = unserialize(TEXTS);
			$categories = unserialize(CATEGORIES);
			$types = unserialize(TYPES);
			$locations = unserialize(LOCATIONS);

			$rodzaj = $categories[$dane->rodzaj]->title;
			$typ = $types[$dane->typ]->title;
			$lokalizacja = $locations[$dane->lokalizacja]->title;

			$temat = 'Formularz zgłoszenia oferty';
			$email = $dane->email;
			$wiadomosc = nl2br($dane->wiadomosc);

			$data = date("Y-m-d H:i:s");
			$data_mail = date('d-m-Y H:i:s');

			$tresc = <<<EOF
				<div style="line-height: 2em">
					<div><h3>Szczegóły oferty:</h3></div>
					<div><strong>Data zgłoszenia: </strong>{$data_mail}</div>
					<div><strong>{$texts['rodzaj_transakcji']}: </strong>{$rodzaj}</div>
					<div><strong>{$texts['typ_nieruchomosci']}: </strong>{$typ}</div>
					<div><strong>{$texts['lokalizacja']}: </strong>{$lokalizacja}</div>
					<div><strong>{$texts['imie']}: </strong>{$dane->imie}</div>
					<div><strong>{$texts['email']}: </strong>{$email}</div>
					<div><strong>{$texts['telefon']}: </strong>{$dane->telefon}</div>
					<div><strong>{$texts['wiadomosc']}: </strong><div style="line-height: 1.5em">{$wiadomosc}</div></div>
				</div>
EOF;

			unset($dane, $texts, $categories, $types, $locations);

			if (mempty($email, $wiadomosc)) return false;
			if (!Core::checkCaptcha()) return false;

			$stmt = $this->db->prepare('INSERT INTO zgloszenia VALUES(null, :email, :tresc, :data)');
			$stmt->bindValue(':email', $email, PDO::PARAM_STR);
			$stmt->bindValue(':tresc', $tresc, PDO::PARAM_STR);
			$stmt->bindValue(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			return Core::sendMail(EMAIL, $temat, $tresc);
		}

		public function wyslij_wiadomosc() {
			$dane = Core::mapPOST('dane');
			$texts = unserialize(TEXTS);

			$temat = 'Formularz kontaktowy';
			$email = $dane->email;
			$wiadomosc = nl2br($dane->wiadomosc);

			$data_mail = date('d-m-Y H:i:s');

			$tresc = <<<EOF
				<div style="line-height: 2em">
					<div><h3>Szczegóły zapytania:</h3></div>
					<div><strong>Data zgłoszenia: </strong>{$data_mail}</div>
					<div><strong>{$texts['email']}: </strong>{$email}</div>
					<div><strong>{$texts['wiadomosc']}: </strong><div style="line-height: 1.5em">{$wiadomosc}</div></div>
				</div>
EOF;

			unset($dane, $texts);

			if (mempty($email, $wiadomosc)) return false;

			return Core::sendMail(EMAIL, $temat, $tresc);
		}

		public function getOffertForm($search) {
			$texts = unserialize(TEXTS);

 			$select_category = $search->getSelectList('dane[rodzaj]', CATEGORIES, $texts['rodzaj_transakcji']);
            $select_type = $search->getSelectList('dane[typ]', TYPES, $texts['typ_nieruchomosci']);
            $select_location = $search->getSelectList('dane[lokalizacja]', LOCATIONS, $texts['lokalizacja']);
            $url = get_lang_url('strony/wyslij-oferte');

			$html = <<<EOF
			<div class="offert-box">
				<form action="{$url}" method="post">
				<div class="form-group">{$select_category}</div>
					<div class="form-group">{$select_type}</div>
					<div class="form-group">{$select_location}</div>
					<div class="form-group">
						<input type="text" class="form-control" name="dane[imie]" placeholder="{$texts['imie']}" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="dane[email]" placeholder="{$texts['email']}" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="dane[telefon]" placeholder="{$texts['telefon']}" />
					</div>
					<div class="sep20"></div>
					<div class="form-group">
						<textarea class="form-control" name="dane[wiadomosc]" placeholder="{$texts['wiadomosc']}"></textarea>
					</div>
					<div class="form-group row-no-padding">
						<div class="col-xs-2">
							<img class="img-responsive" src="{$_SESSION['captcha']['image_src']}" />
						</div>
						<div class="col-xs-9 col-xs-offset-1">
							<input type="text" class="form-control" name="captcha" placeholder="{$texts['przepisz_kod']}" />
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">{$texts['wyslij']}</button>
					</div>
				</form>
			</div>
EOF;
			return $html;
		}

		public function getContantForm() {
			$texts = unserialize(TEXTS);
            $url = get_lang_url('strony/wyslij-zapytanie');
            $send = mb_strtoupper($texts['wyslij'], 'UTF-8');

			$html = <<<EOF
			<div class="kontakt-form">
				<h3>{$texts['napisz_do_nas']}</h2>
				<form action="{$url}" method="post">
					<div class="form-group">
						<input type="text" class="form-control" name="dane[email]" placeholder="{$texts['email']}" />
					</div>
					<div class="form-group">
						<textarea class="form-control" rows="5" name="dane[wiadomosc]" placeholder="{$texts['wiadomosc']}"></textarea>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-contant">{$send}</button>
					</div>
				</form>
			</div>
EOF;
			return $html;
		}
	}
?>

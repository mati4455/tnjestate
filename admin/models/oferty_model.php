<?php
	class OfertyModel extends baseModel {	

		public function deleteOffer($id) {
			$this->deleteImages($id);

			$stmt = $this->db->prepare('DELETE FROM oferty_tlumaczenia WHERE oferta_id = :id');			
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();

			$stmt = $this->db->prepare('DELETE FROM oferty WHERE id = :id');			
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();
		}

		public function deleteImages($id) {
			$folder = ($id % 100 + 1);
			$stmt = $this->db->prepare('SELECT url FROM zdjecia WHERE oferta_id = :id');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$dane = $stmt->fetchAll(PDO::FETCH_CLASS);
			foreach ($dane as $key => $value) {
				drukuj($value); echo '<br>';
				$this->deleteImage($folder, $value->url);
			}

			$stmt2 = $this->db->prepare('DELETE FROM zdjecia WHERE oferta_id = :id');
			$stmt2->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt2->execute();
			$stmt2->closeCursor();
		}

		public function deleteImage($folder, $url) {
			$min = IMAGES_URL . 'min/' . $folder  . '/' . $url;
			$max = IMAGES_URL . 'max/' . $folder  . '/' . $url;
			unlink($min);
			unlink($max);
		}

		public function getImages($id) {
			$stmt = $this->db->prepare('SELECT id, url FROM zdjecia WHERE oferta_id = :id ORDER BY kolejnosc ASC');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		/**
		 * get name of category 
		 * @param  int $id 
		 * @return string 
		 */
		public function kategoriaNazwa($id) {
			$stmt = $this->db->prepare('SELECT pt.title FROM `rodzaje` p
		        INNER JOIN `rodzaje_tlumaczenia` pt ON p.id = pt.rodzaj_id
		        WHERE p.id = :id AND pt.language_code = pl');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$tmp = $stmt->fetch();
			return $tmp[0];
		}




		/**
		 * save submitted film
		 * @return string - information after submit
		 */
		public function zapiszOferte() {
			$dane = Core::mapPOST('dane');
			$lang = Core::mapPOST('lang');
			//drukuj($lang);exit;
			
			$dane->specjalna = empty($dane->specjalna) ? 0 : 1;

			$info = '';
			$m = unserialize(MESSAGES);

			$ins = true;
			$stmt = null;
			if (empty($dane->id)) {
				$stmt = $this->db->prepare('INSERT INTO oferty					
					VALUES(null, :age, :rod, :typ, :spe, :lok, :uli, :met, :pok, :pio, :pir, :cena, :lic)');
			} else {
				$ins = false;
				$stmt = $this->db->prepare('UPDATE oferty					
					SET agent_id = :age, rodzaj_id = :rod, typ_id = :typ, specjalna = :spe, 
					lokalizacja_id = :lok, 	ulica = :uli, metraz = :met, pokoje = :pok, 
					pietro = :pio, pieter = :pir, cena = :cena, licencja = :lic 
					WHERE id = :id');
				$stmt->bindValue(':id', $dane->id, PDO::PARAM_INT);
			}
			$stmt->bindValue(':age', $dane->agent_id, PDO::PARAM_INT);
			$stmt->bindValue(':rod', $dane->rodzaj_id, PDO::PARAM_INT);
			$stmt->bindValue(':typ', $dane->typ_id, PDO::PARAM_INT);
			$stmt->bindValue(':spe', $dane->specjalna, PDO::PARAM_INT);
			$stmt->bindValue(':lok', $dane->lokalizacja_id, PDO::PARAM_INT);
			$stmt->bindValue(':uli', $dane->ulica, PDO::PARAM_STR);
			$stmt->bindValue(':met', $dane->metraz, PDO::PARAM_INT);
			$stmt->bindValue(':pok', $dane->pokoje, PDO::PARAM_INT);
			$stmt->bindValue(':pio', $dane->pietro, PDO::PARAM_INT);
			$stmt->bindValue(':pir', $dane->pieter, PDO::PARAM_INT);
			$stmt->bindValue(':cena', $dane->cena, PDO::PARAM_INT);
			$stmt->bindValue(':lic', $dane->licencja, PDO::PARAM_STR);

			if ($stmt->execute()) {
				unset($_POST['dane']);
			}
			else $info = Core::error_message($m[105]);

			$tmp_id = ($ins) ? $this->db->lastInsertId('id') : $dane->id;
			$this->zapiszTeksty($tmp_id, $lang, $ins);

			return $tmp_id;
		}

		private function zapiszTeksty($id, $dane, $ins) {
			$langs = unserialize(LANGS);

			foreach ($langs as $k => $v) {
				$row = $dane->$v;

				$stmt = null;
				if ($ins) {
					$stmt = $this->db->prepare('INSERT INTO oferty_tlumaczenia 
						VALUES(null, :ofid, :lang, :title, :tresc, :keys) ');
					$stmt->bindValue(':lang', $v, PDO::PARAM_STR);
					$stmt->bindValue(':ofid', $id, PDO::PARAM_INT);
				} else {
					$stmt = $this->db->prepare('UPDATE oferty_tlumaczenia
						SET title = :title, tresc = :tresc, keywords = :keys
						WHERE id = :id');
					$stmt->bindValue(':id', $row['id'], PDO::PARAM_INT);
				}

				$tresc = stripslashes(nl2br($row['tresc']));
				$title = stripslashes($row['title']);
				$keywords = trim($row['keywords']);

				$stmt->bindValue(':title', $title, PDO::PARAM_STR);
				$stmt->bindValue(':keys', $keywords, PDO::PARAM_STR);
				$stmt->bindValue(':tresc', $tresc, PDO::PARAM_STR);
				$stmt->execute();
				$stmt->closeCursor();
			}			
		}


		public function przygotujZapytanie($data) {
			$p = explode('-', $data);
			$war1 = (is_numeric($p[0]) && $p[0] > 0) ? 'rodzaj_id = ' . $p[0] .' AND ' : '';
			$war2 = (is_numeric($p[1]) && $p[1] > 0) ? 'typ_id = '.$p[1].' AND ' : '';
			$war3 = (is_numeric($p[2]) && $p[2] > 0) ? 'lokalizacja_id = '.$p[2].' AND ' : '';
			$war4 = $this->getBetweenQuery($p[3], $p[4], 'cena');
			$war5 = $this->getBetweenQuery($p[5], $p[6], 'metraz');
			$war6 = $this->getBetweenQuery($p[7], $p[8], 'pokoje');
			$war7 = $_GET['wyr'] == 1 ? ' specjalna = 1 AND ' : '';
			
			$warunek = 'WHERE ' . $war1 . $war2 . $war3 . $war4 . $war5 . $war6 . $war7;
			$warunek = trim(trim($warunek), 'AND');

			if ($warunek == 'WHERE') $warunek = '';
			return $warunek;
		}

		private function getBetweenQuery($from, $to, $name) {
			$left = is_numeric($from);
			$right = is_numeric($to);
			//if (!$this->checkBetween($from, $to)) $right = false;

			if (!$left && !$right) $ret = '';
			else if ($left && $right) $ret = '(' . $name . ' BETWEEN ' . $from . ' AND ' . $to .')';
			else if ($left && !$right) $ret = '(' . $name . '>=' . $from .')';
			else $ret = '(' . $name . '<=' . $to .')';

			return empty($ret) ? '' : $ret . ' AND ';
		}

		private function checkBetween($a, $b) {
			if (is_numeric($a) && is_numeric($b)) return $a >= $b;
			return true;
		}

		/**
		 * return number of offerts
		 * @return int          amount of elements
		 */
		public function iloscElementow($warunek) {
			$query_count = 'SELECT COUNT(id) as ile FROM oferty ' . $warunek;
			$stmt = $this->db->prepare($query_count);
			$stmt->execute();
			$iloscElementow = $stmt->fetch(PDO::FETCH_NUM);
			$stmt->closeCursor();

			return $iloscElementow[0];
		}

		/**
		 * get films with correct conditions
		 * @param  string  $warunek  ready for query of films
		 * @param  int $pages    	 amount of pages
		 * @param  int $per_page 	 amount of films per page
		 * @return array             all films matching to conditions
		 */
		
		public function pobierzOferty($warunek, $pages, $per_page = false) {
			if (!$per_page) $per_page = PER_PAGE_DEFAULT;

			$page = empty($_GET['page']) ? 0 : $_GET['page'] - 1;
			$start = $page * $per_page;
			$stron = ceil($pages / $per_page);

			$war = empty($warunek) ? "WHERE (pt.language_code = :lang)" : $warunek . " AND (pt.language_code = :lang) ";

			$query = 'SELECT p.*, pt.title, pt.tresc, zd.url
				FROM `oferty` p 
				INNER JOIN `oferty_tlumaczenia` pt ON p.id = pt.oferta_id 
				LEFT JOIN `zdjecia` zd ON zd.oferta_id = p.id
				' . $war .' AND (zd.kolejnosc = 0 OR zd.oferta_id IS NULL)
				ORDER BY id DESC LIMIT '.$start.', '.$per_page.' ';

			//echo $sortowanie .' ' .$query; exit;

			$stmt = $this->db->prepare($query);	
			$stmt->bindValue(':lang', LANG, PDO::PARAM_STR);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function pobierzOferte($id) {
			$query = 'SELECT * FROM oferty WHERE id = :id';
			$stmt = $this->db->prepare($query);	
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
		}

		public function pobierzTlumaczenia($id) {
			$query = 'SELECT * FROM oferty_tlumaczenia WHERE oferta_id = :id';
			$stmt = $this->db->prepare($query);	
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$d = Array();
			foreach ($stmt->fetchAll(PDO::FETCH_CLASS) as $k => $v) {
				$d[$v->language_code] = $v;
				$d[$v->language_code]->tresc = str_replace(Array('<br />', '<br>'), "\n", $v->tresc);
			}
			return $d;
		}
		
		public function formularzZdjec($id) {
			$f = new Form(get_url('oferty/zapisz-zdjecia/?oferta='.$id), 'post', 'p90 margin-auto formularz walidacja dropzone', 'img_upload', true);
			$f->newFile('file', true);
			$f->endForm();

			return $f->show();
		}

		public function zapiszZdjecia() {
			$id = $_GET['oferta'];
			$nazwaPliku = '';
			$rozszerzenia = Array('jpg', 'png', 'jpeg', 'bmp', 'gif');
			$folder = ($id % 100 + 1) . '/';

			$file = '/admin/dane.txt';$current = file_get_contents($file);
			$current .= $id . ' ' . $folder . "\n";file_put_contents($file, $current);

			if (!empty($_FILES)) {				
				$tempFile = $_FILES['file']['tmp_name'];
				$targetPath = '/uploads/images/';
				$roz = strtolower( pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) );	
				$unique_id = base_convert( time(), 10, 36 ) . md5( microtime() . uniqid() );

				$nazwaPliku = 'img-'.$id.'-'.$unique_id.'.'.$roz;
				$targetFile =  $targetPath.'max/'.$folder.$nazwaPliku;

				makeDir($targetPath.'max/'.$folder);
				makeDir($targetPath.'min/'.$folder);

				move_uploaded_file($tempFile, $targetFile);

				if (in_array($roz, $rozszerzenia)) {
					$img = new SimpleImage($targetFile);
					$img->auto_orient();
					if ($img->get_height() < $img->get_width()) {
						$img->fit_to_height(500);
						$img->save();
						$img->fit_to_height(180);
					} else {
						$img->fit_to_width(800);
						$img->save();				
						$img->fit_to_width(250);							
					}
					$img->save( $targetPath.'min/'.$folder.$nazwaPliku);
				}			

				$stmt = $this->db->prepare('INSERT INTO zdjecia (oferta_id, kolejnosc, url) VALUES(:id, 99, :sciezka)');
				$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				$stmt->bindValue(':sciezka', $nazwaPliku, PDO::PARAM_STR);

				$st = $this->db->prepare('SET @r = -1; UPDATE zdjecia SET kolejnosc = (@r:=@r+1) WHERE oferta_id=:id ORDER BY kolejnosc ASC, id ASC');
				$st->bindValue(':id', $id, PDO::PARAM_INT);

				if ($stmt->execute() && $st->execute())
					return true;
			}
			return false;
		}

		public function zapiszKolejnosc() {
			foreach ($_GET['item'] as $k => $v) {
				$stmt = $this->db->prepare('UPDATE zdjecia SET kolejnosc = :kolejnosc WHERE id = :id');
				$stmt->bindValue(':id', $v, PDO::PARAM_INT);
				$stmt->bindValue(':kolejnosc', $k, PDO::PARAM_INT);
				$stmt->execute();
			}
		}

		public function usunZdjecie($id) {
			$check = $this->db->prepare('SELECT oferta_id, url FROM zdjecia WHERE id = :id LIMIT 1');
			$check->bindValue(':id', $id, PDO::PARAM_INT);
			$check->execute();			

			$a = $check->fetch(PDO::FETCH_LAZY);

			$stmt = $this->db->prepare('DELETE FROM zdjecia WHERE id = :id LIMIT 1');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();

			return $this->deleteImage(($a->oferta_id + 1) % 100, $a->url);
		}



		public function nawigacja($ile, $per_page = false) {
			if (!$per_page) $per_page = PER_PAGE_DEFAULT;
			$page = empty($_GET['page']) ? 0 : $_GET['page'] - 1;
			$stron = ceil($ile / $per_page);
			//($item_per_page, $current_page, $total_records, $total_pages)
			return $this->paginate_function($per_page, $page, $ile, $stron);
		}

		public function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
		{
			$current_page++;
			$n = $current_page;
			$url = trim(str_replace('page='.$n, '', $_SERVER["REQUEST_URI"]), '&');
			$pos = strrpos($url, "/?");
			if ($pos === false) {
				$url .= '/?';
			}
			$url .= '&page=';
			$url = str_replace('?&', '?', $url); 

		    $pagination = '';
		    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
		        $pagination .= '<ul class="pagination">';
		        
		        $right_links    = $current_page + 1; 
		        $previous       = $current_page - 1; //previous link 
		        $next           = $current_page + 1; //next link
		        $first_link     = true; //boolean var to decide our first link
		        
		        if($current_page > 1){
		            $previous_link = ($previous==0)?1:$previous;
		            $pagination .= '<li class=""><a href="'.$url.'1" data-page="1" title="Pierwsza">&laquo;</a></li>'; //first link
		            $pagination .= '<li><a href="'.$url.$previous.'" data-page="'.$previous_link.'" title="Poprzednia">&lt;</a></li>'; //previous link
		                for($i = ($current_page-3); $i < $current_page; $i++){ //Create left-hand side links
		                    if($i > 0 && $i < $total_pages){
		                        $pagination .= '<li><a href="'.$url.$i.'" data-page="'.$i.'" title="Strona '.$i.'">'.$i.'</a></li>';
		                    }
		                }   
		            $first_link = false; //set first link to false
		        }
		        
		        if($first_link){ //if current active page is first link
		            $pagination .= '<li class=" active"><a class="disabled" href="#">'.$current_page.'</a></li>';
		        }elseif($current_page == $total_pages){ //if it's the last active link
		            $pagination .= '<li class=" active"><a class="disabled" href="#">'.$current_page.'</a></li>';
		        }else{ //regular current link
		            $pagination .= '<li class="active"><a class="disabled" href="#">'.$current_page.'</a></li>';
		        }
		                
		        for($i = $current_page+1; $i < $current_page+4 ; $i++){ //create right-hand side links
		            if($i<=$total_pages){
		                $pagination .= '<li><a href="'.$url.$i.'" data-page="'.$i.'" title="Strona '.$i.'">'.$i.'</a></li>';
		            }
		        }
		        if($current_page < $total_pages){ 
		                $next_link = ($i > $total_pages)? $total_pages : $i;
		                $pagination .= '<li><a href="'.$url.$next_link.'" data-page="'.$next_link.'" title="Następna">&gt;</a></li>'; //next link
		                $pagination .= '<li class=""><a href="'.$url.$total_pages.'" data-page="'.$total_pages.'" title="Ostatnia">&raquo;</a></li>'; //last link
		        }
		        
		        $pagination .= '</ul>'; 
		    }
		    return $pagination; //return pagination links
		}

		public function getTypesMenu() {
			$p = explode('-', $_GET['action']);
			$html = '<ul class="menu menu-types">';
			$typy = unserialize(TYPES);
			foreach ($typy as $k => $v) {
				$url = get_url('oferty/' . $_GET['action'] . '/' . $v->short);
				$active = strpos($_SERVER['REQUEST_URI'], $v->short) !== false ? 'active' : '';
				if (empty($active)) $active = $p[0] == $v->id ? 'active' : '';
				$html .= '<li class="'.$active.'"><a href="'.$url.'">'.strtoupper($v->title).'</a></li>';
			}
			$html .= '</ul>';
			return $html;
		}

		public function prepareData() {
			$a = $this->getIdFromName($_GET['action']);
			$b = $this->getIdFromName($_GET['id']);
			return $a .'-'. $b;
		}

		public function getIdFromName($name) {
			if (is_numeric($name) || empty($name)) return $name;
			$stmt = $this->db->prepare('SELECT id FROM rodzaje WHERE short = :s LIMIT 1');
			$stmt->bindValue(':s', $name, PDO::PARAM_STR);
			$stmt->execute();
			$t = $stmt->fetch();
			return $t[0];
		}

	}
?>

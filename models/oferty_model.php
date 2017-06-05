<?php
	class OfertyModel extends baseModel {	
		
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
			$dane->opis = stripslashes(nl2br($dane->opis));
			$dane->tytul = stripslashes($dane->tytul);
			$info = '';
			$m = unserialize(MESSAGES);
			
			if (empty($dane->tytul)) $info .= Core::error_message($m[100]);
			if (empty($dane->kategoria)) $info .= Core::error_message($m[101]);
			if (empty($dane->youtube) && empty($dane->dailymotion)) $info .= Core::error_message($m[102]);
			if (!Core::checkCaptcha()) $info .= Core::error_message($m[103]);

			$youtube_id = Video::getYoutubeId($dane->youtube);

			$sm = Video::checkUrl($youtube_id, 1);
			//$sd = Video::checkUrl($youtube_id, 2);
			$hd = Video::checkUrl($youtube_id, 3);

			$lg = $hd ? 3 : 1;

			$image = '1#' . $lg; 

			if ($this->czyIstniejeFilm($youtube_id)) $info .= Core::error_message($m[116]);

			$data = date("Y-m-d H:i:s");

			if (empty($info)):
				$stmt = $this->db->prepare('INSERT INTO filmy (poczekalnia, user_id, kategoria_id, tytul, opis, image, youtube, dailymotion, data)
					VALUES(0, 1, :kid, :tyt, :op, :im, :yt, :dm, :data)');
				$stmt->bindValue(':kid', $dane->kategoria, PDO::PARAM_INT);
				$stmt->bindValue(':tyt', $dane->tytul, PDO::PARAM_STR);
				$stmt->bindValue(':op', $dane->opis, PDO::PARAM_STR);
				$stmt->bindValue(':im', $image, PDO::PARAM_STR);
				$stmt->bindValue(':yt', $youtube_id, PDO::PARAM_STR);
				$stmt->bindValue(':dm', '' /*$dane->dailymotion*/, PDO::PARAM_STR);
				$stmt->bindValue(':data', $data, PDO::PARAM_STR);

				if ($stmt->execute()) {
					$info = Core::primary_message($m[104]);
					unset($_POST['dane']);
				}
				else $info = Core::error_message($m[105]);
			endif;

			return empty($info) ? '' : $info;
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
			$query = 'SELECT p.*, a.imie_nazwisko, a.telefon, pt.title, pt.tresc, pt.keywords
				FROM `oferty` p 
				INNER JOIN `oferty_tlumaczenia` pt ON p.id = pt.oferta_id 
				LEFT JOIN `agenci` a ON a.id = p.agent_id
				WHERE p.id = :id AND pt.language_code = :lang';
			$stmt = $this->db->prepare($query);	
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->bindValue(':lang', LANG, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
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
				$url = get_lang_url('oferty/' . $_GET['action'] . '/' . $v->short);
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

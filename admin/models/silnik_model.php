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

		public function getAgents() {
			$stmt = $this->db->prepare('SELECT id, imie_nazwisko, telefon FROM agenci ORDER BY imie_nazwisko ASC');
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
		        INNER JOIN `strony` ps On p.strona_id = ps.id
		        WHERE p.type = :type AND pt.language_code = :lang ORDER BY p.kolejnosc ASC');
			$stmt->bindValue(':type', $id, PDO::PARAM_INT);
			$stmt->bindValue(':lang', LANG, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS); 
		}
	}
?>


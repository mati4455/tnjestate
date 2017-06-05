<?php
	class SearchModel extends baseModel {
		/**
		 * get search form html code
		 * @param  String $category name of category {sprzedaz, wynajem, specjalne}
		 * @param  String $type     name of type {mieszkania, domy, komercyjne}
		 * @return String           html code with form
		 */
		public function getForm($category, $type) {
			$txt = unserialize(TEXTS);
			
			$p = strpos($_GET['action'], '-') !== false ? explode('-', $_GET['action']) : '';

			if (!empty($p)) {
				$category = $p[0];
				$type = $p[1];
			}

			$select_category = $this->getSelectList('rodzaj', CATEGORIES, $txt['rodzaj_transakcji'], $category);
			$select_type = $this->getSelectList('typ', TYPES, $txt['typ_nieruchomosci'], $type);
			$select_location = $this->getSelectList('lokalizacja', LOCATIONS, $txt['lokalizacja'], $p[2]);

			$html = '
			<div class="search-move pos-relative scrollspy col-xs-12 col-sm-5 col-md-5 col-lg-4">

			<button class="btn btn-primaty mobile-search visible-xs" data-toggle="collapse" data-target=".search-box">
			<span class="glyphicon glyphicon-search"></span></button>

			<div class="search-box affix">
				<h2>'. strtoupper($txt['wyszukiwanie']) .'</h2>
				<form id="search-form" action="'. get_lang_url('oferty/') .'" method="post">
					<div class="form-group">'. $select_category .'</div>
					<div class="form-group">'. $select_type .'</div>
					<div class="form-group">'. $select_location .'</div>
					<div class="form-group half">
						<input type="text" class="form-control" name="cena_od" value="'.$p[3].'" placeholder="'. $txt['cena_od'] .'" />
						<input type="text" class="form-control" name="cena_do" value="'.$p[4].'" placeholder="'. $txt['cena_do'] .'" />
					</div>
					<div class="form-group half">
						<input type="text" class="form-control" name="metraz_od" value="'.$p[5].'" placeholder="'. $txt['metraz_od'] .'" />
						<input type="text" class="form-control" name="metraz_do" value="'.$p[6].'" placeholder="'. $txt['metraz_do'] .'" />
					</div>
					<div class="form-group half">
						<input type="text" class="form-control" name="pokoje_od" value="'.$p[7].'" placeholder="'. $txt['pokoje_od'] .'" />
						<input type="text" class="form-control" name="pokoje_do" value="'.$p[8].'" placeholder="'. $txt['pokoje_do'] .'" />
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">'.strtoupper($txt['wyszukaj']).'</button>
					</div>

				</form>
			</div></div>';
			
			return $html;
		}


		public function getSelectList($name, $list, $default, $selected = '') {
			$id = $this->getIdFromName($selected);
			$html = '<select class="form-control" id='.$name.' name='.$name.'>';
			$html .= '<option value="0">'.$default.'</option>';
			foreach (unserialize($list) as $k => $v) {
				$tmp = $v->id == $id ? ' selected' : '';
				$html .= '<option value="'.$v->id.'" '.$tmp.'>'.$v->title.'</option>';
			}
			$html .= '</select>';
			return $html;
		}

		private function getIdFromName($name) {
			if (is_numeric($name)) return $name;
			$stmt = $this->db->prepare('SELECT id FROM rodzaje WHERE short = :s LIMIT 1');
			$stmt->bindValue(':s', $name, PDO::PARAM_STR);
			$stmt->execute();
			$t = $stmt->fetch();
			return $t[0];
		}
	}
?>

<?php
	class SearchModel extends baseModel {
		/**
		 * get search form html code
		 * @param  String $category name of category {sprzedaz, wynajem, specjalne}
		 * @param  String $type     name of type {mieszkania, domy, komercyjne}
		 * @return String           html code with form
		 */
		public function getForm($category, $type, $lok = '') {
			$txt = unserialize(TEXTS); 

			$select_category = Core::getSelectList('rodzaj', unserialize(CATEGORIES), RODZAJ_TRANSAKCJI, $this->getIdFromName($category));
			$select_type =  Core::getSelectList('typ', unserialize(TYPES), TYP_NIERUCHOMOSCI, $this->getIdFromName($type));
			$select_location =  Core::getSelectList('lokalizacja', unserialize(LOCATIONS), LOKALIZACJA, $this->getIdFromName($lok));

			$html = '
			<div class="search-move pos-relative scrollspy col-xs-12 col-sm-5 col-md-4 col-lg-3">

			<button class="btn btn-primaty mobile-search visible-xs" data-toggle="collapse" data-target=".search-box">
			<span class="glyphicon glyphicon-search"></span></button>
 
			<div class="search-box affix" data-offset-top="60">
				<h2>WYBIERZ OFERTY</h2>
				<form action="'.get_url('oferty/').'" method="get">
					<div class="form-group">'. $select_category .'</div>
					<div class="form-group">'. $select_type .'</div>
					<div class="form-group">'. $select_location .'</div>					
					<div class="form-group">
						<button type="submit" class="btn btn-primary">wyświetl wybrane oferty</button>
					</div>

				</form>
			</div></div>';
			
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

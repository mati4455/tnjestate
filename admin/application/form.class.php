<?php
	Class Form {
		private $formularz = '';
		public $prefix;
		private $sekcja;

		public function __construct($url, $method, $classes=null, $id=null, $file=false) {
			if ($file) $w = 'enctype="multipart/form-data"'; else $w = '';
			$this->add('<form action="'.$url.'" method="'.$method.'" id="'.$id.'" class="'.$classes.'" '.$w.'>');
			$this->sekcja = false;
		}

		public function setPrefix($prefix) {
			$this->prefix = $prefix;
		}

		public function sN($name) {
			if (!empty($this->prefix))
				return stripcslashes($this->prefix . "[" . $name . "]");
			return $name;
		}

		public function add($o) {
			$this->formularz .= "\r\n" . $o;
		}

		public function newInput($label, $name, $require=0, $val=null, $autocomplete="off", $placeholder="", $type="text", $class=null) {
			if ($require == 1) $k = 'require '; else $k = ' ';
			$pole = '<input class="form-control '.$k.$class.'" placeholder="' .$placeholder. '" autocomplete="' . $autocomplete . '" type="' .$type. '" id="' . $name . '" name="' . $this->sN($name) . '" value="' . $val . '" />';
			$field = '<div class="row">
				<label class="'.$k.' nazwa">' . $label . '</label>
				<label class="'.$class.'"></label>'.$pole.'
				<div class="clearboth"></div>
			</div>';

			$this->add($field);
			return $pole;
		}

		public function newPhone($l1, $n1, $l2, $n2, $w1, $w2) {
			$field = '
				<div class="row">
					<label class="nazwa">'.$l1.'</label>
					<div class="inline-block">
						<input class="tel" type="text" name="'.$this->sN($n1).'" value="'.$w1.'" />
						<label>'.$l2.'</label>
						<input class="wew" type="text" name="'.$this->sN($n2).'" value="'.$w2.'" />
					</div>
				</div>
			';
			$this->add($field);
			return $field;
		}

		public function newHidden($name, $value, $id=null) {
			$field = '<input type="hidden" name="'.$this->sN($name).'" value="'.$value.'" id="'.$id.'" />';
			$this->add($field);
			return $field;
		}

		public function newPassword($label, $name, $placeholder) {
			$this->newInput($label, $name, 1, null, 'off', $placeholder, 'password');
		}

		public function newSelect($label, $name, $default, $list, $selected, $id = '', $class = '') {
			$qq = '<div class="row"><label class="require">'.$label.'</label>';
			$s = '<select name="'.$this->sN($name).'" id="'.$id.'" class="'.$class.'">';
			if ($default)
				$s .= '<option value="0">'.$default.'</option>';
			foreach ($list as $key => $w) {
				if (!is_array($w)) {
					$x[0] = $key;
					$x[1] = $w;
					$w = $x;
				}
				$t = ($w[0] == $selected) ? 'selected' : '';
				$s .= '<option '.$t.' value="'.$w[0].'">'.$w[1].'</option>';
			}
			$s .= '</select>';
			$q = '</div>';
			$this->add($qq.$s.$q);
			return $s;
		}

		public function newRadio($label, $name, $default, $params, $newLine = false, $class='') {
			$pp = '<div class="row"><label class="nazwa">'.$label.'</label><div class="inline-block">';
			$p=1;
			if ($newLine) { $y = '<div>'; $u = '</div>'; } else { $v = $u = ''; }
			foreach ($params as $k => $v) {
				if ($v == $default) $t = 'checked '; else $t = '';
				$w .= $y . '<input class="'.$class.'" '.$t.' type="radio" name="'.$this->sN($name).'" id="'.$name.$p.'" value="'.$v.'" /><label for="'.$name.$p.'">'.$k.'</label>' . $u;
				$p++;
			}
			$pp2 .= '</div></div>';
			$this->add($pp . $w . $pp2);
			return $w;
		}

		public function newFeature($label, $name, $cecha, $zaznaczone) {
			$w = '<div class="row"><label class="nazwa">'.$label.'</label><div class="inline-block">';
			$params = Array('tak' => $cecha, 'nie' => 0);

			if (strpos($zaznaczone, '#'.$cecha.'#') !== false) {
				$t = ' checked'; $t2 = '';
			} else {
				$t = '';	$t2 = ' checked';
			} 
			$id1 = 'c_'.$cecha.'_1'; 
			$id2 = 'c_'.$cecha.'_2';

			$w .= '<input '.$t.'  type="radio" name="'.($name).'"  value="'.$cecha.'" id="'.$id1.'"/><label for="'.$id1.'">tak</label>';
			$w .= '<input '.$t2.' type="radio" name="'.($name).'"  value="0" id="'.$id2.'"/><label for="'.$id2.'">nie</label>';
		
			$w .= '</div><div class="clearboth"></div></div>';
			$this->add($w);
			return $w;
		}

		public function newCheckboxList($label, $name, $lista, $zaznaczone) {
			$zaznaczone = explode('#', $zaznaczone);
			$field =  
			'
			<div class="row">
				<label class="nazwa">' . $label . '</label>
				<div class="floatleft">';

			foreach ($lista as $key => $value) {

				if (in_array($key, $zaznaczone)) $t = 'checked'; else $t = '';
				$field .= '
					<div><input type="checkbox" name="'.($name).'" value="'.$key.'" '.$t.' />'.$value.'</div>
				';
			}

			$field .= '
				</div>
				<div class="clearboth"></div>
			</div>
			';

			$this->add($field);
			return $field;
		}

		public function newSingleCheckbox($label, $name, $wartosc, $selected) {
			if ($wartosc == $selected) $t = 'checked'; else $t = '';
			$field = '
				<div><input type="checkbox" id="'.($name.'_'.$wartosc).'" name="'.$this->sN($name).'" value="'.$selected.'" '.$t.' /><label class="switch_label">'.$label.'</label></div>
			';

			$this->add($field);
			return $field;
		}
		
		public function newCheckbox($label, $name, $lista, $zaznaczone, $klasa = '') {
			$zaznaczone = explode('#', $zaznaczone);
			$field =  
			'
			<div class="row '.$klasa.'">
				<label class="nazwa">' . $label . '</label>
				<div class="floatleft">';

			if (empty($lista)) return '';

			foreach ($lista as $key => $value) {

				if (in_array($value, $zaznaczone)) $t = 'checked'; else $t = '';
				$field .= '
					<div class="feature"><input type="checkbox" id="'.($name.'_'.$value).'" name="'.$this->sN($name).'[]" value="'.$value.'" '.$t.' /><label class="switch_label">'.$key.'</label></div>
				';
			}

			$field .= '
				</div>
				<div class="clearboth"></div>
			</div>
			';

			$this->add($field);
			return $field;
		}

		public function newFile($nazwa, $multi=false, $types=null) {
			if ($multi) {
				$mm = '<div class="fallback"><input name="'.$nazwa.'" type="file" multiple /></div>';
				$this->add($mm);
				return $mm;
			} else {
				$ss = '<input type="file" name="'.$nazwa.'" accept="'.$types.'" />';
				$this->add($ss);
				return $ss;
			}
		}

		public function addControls($text, $addons=null, $classes=null) {
			$this->newRow('center');
			$this->separator(20);
			$this->add( '<input type="submit" value="'.$text.'" '.$addons.' class="'.$classes.'" />' );
			$this->endRow();
			if ($this->sekcja == true) $this->endSection();
			$this->endForm();
		}

		public function endForm() {
			$this->add( '</form>' );
		}

		public function addHTML($html) {
			$this->add($html);
		}


		/* layout */
		public function newSection($h2, $highlight = false) {
			$this->sekcja = true;
			if (!empty($h2)) $h2 = '<h2>'.$h2.'</h2>';
			if ($highlight) {
				$this->add( '<div class="row section-light">'.$h2.'' );
			} else {
				$this->add( '<div class="row section">'.$h2.'' );
			}
		}

		public function addHeader($h) {
			$this->add('<h2>'.$h.'</h2>');
		}

		public function endSection() {
			$this->sekcja = false;
			$this->add( '</div>' );
		}

		public function newRow($c=null) {
			$this->add('<div class="row '.$c.'">');
		}

		public function endRow() {
			$this->add('</div>');
		}

		public function separator($height) {
			$this->add("\n" . '<div class="sep'.$height.'"></div>' . "\n");
		}

		public function info($text, $link=null) {
			if (!empty($link)) $t = ' (<a href="'.$link.'">kliknij)</a>'; else $t = '';
			$this->add('<div class="row info">'.$text.$t.'</div>');

		}

		public function show() {
			return $this->formularz;
		}

	}
?>

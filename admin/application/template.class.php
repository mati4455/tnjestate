<?php

Class Template {
	private $registry;
	private $db;
	
	private $vars = array();
	
	function __construct($registry) {
		$this->registry = $registry;
		$this->db = $registry->db;
	}

	public function __set($index, $value) {
        $this->vars[$index] = $value;
	}

	public function loadPage($id) {
		$stmt = $this->db->prepare('SELECT opis_pl FROM strony_statyczne WHERE id = :id');
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$odp = $stmt->fetch();
		echo $odp['opis_pl'];
	}

	public function getStyle($css3) {
		$url = __SITE_PATH;
		$styles = $generated = '';
		$link = $url . 'assets/css/website_styles.css';
		$css2 = array_merge(unserialize(__CSS), explode(' ', $css3));


		$folder = $url . 'assets/cache/';
		$options = array(
			'compress' => true, 
			'cache_dir' => $folder, 
			'cache_method' => 'serialize',
			'sourceMap' => SOURCE_MAP
		);
		$parser = new Less_Parser($options);
		$less_files = Array();

		if (is_array($css2)) {
			foreach ($css2 as $v) {
				if (empty($v)) continue;
				if (strpos($v, '/') !== false) {
					$css = $v;
				} else {
					$less = $url . 'assets/less/' . $v . '.less';
					//$css = $url . 'assets/css/' . $v . '.css';
					if (file_exists($less))
						$less_files[$less] = false;
						
				}
				/*if (file_exists($css) || strpos($css, 'http') !== false)
					$generated .= file_get_contents($css, FILE_USE_INCLUDE_PATH);*/
					//$styles .= '<link rel="stylesheet" href="'.$css.'" />' . "\r\n\t";
			}			

			$css_file_name = Less_Cache::Get( $less_files, $options );
			//$compiled = file_get_contents( $folder.$css_file_name );

			//file_put_contents($link, $compiled);
			$styles .= '<link rel="stylesheet" href="'.$folder.$css_file_name.'" />' . "\r\n\t";
		}
		return $styles;
	}

	public function getJS($js3) {
		$url = __SITE_PATH;
		$javascripts = $generated = '';
		$link = $url . 'assets/js/all_scripts.js';
		$js2 = array_merge(explode(" ", $js3), unserialize(__JS));
		if (is_array($js2)) {
			foreach ($js2 as $k => $v) {
				if (empty($v)) continue;
				if (strpos($v, '/') !== false)
					$p = $v;
				else
					$p = $url . 'assets/js/' . $v;	

				if (!is_numeric($k) && (file_exists($p) || strpos($p, 'http') !== false)) {
					$generated .= file_get_contents($p, FILE_USE_INCLUDE_PATH);
					$javascripts .= '<script src="' . $p . '"></script>' . "\r\n\t";
				}
				if (is_numeric($k)) {
					$javascripts .= '<script src="' . $p . '"></script>' . "\r\n\t";					
				}
			}
		}
		$generated = str_replace(array("\n", "\t", "\n\n", '  ', '    ', '    '), '', $generated);
		file_put_contents($link, $generated);
		//$javascripts .= '<script src="' . $link . '"></script>' . "\r\n\t";

		return $javascripts;
	}

	public function getGoogle() {
		$google_id = GOOGLE_ANALYTICS;

		$skrypt = <<<EOF
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', '$google_id', 'auto');
		  ga('send', 'pageview');

		</script>
EOF;
		return empty($google_id) || !defined('GOOGLE_ANALYTICS') ? '' : $skrypt;
	}
/*
	public function getContent($name) {
		$router = $this->registry->router->controller;
		$path = '/views';
		$temp = Core::startsWith($name, '/') ? $name : '/' . $router . '/' .$name;
		$temp .= '.php';
		return file_get_contents($path.$temp, FILE_USE_INCLUDE_PATH);
	}*/

	public function show($nameIn=null, $header=true, $footer=true) {
		/* przypisanie zmiennych */
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}

		/* pomocniecze */
		$router = $this->registry->router->controller;
		

		/* wygenerowanie styli i javascriptu*/
		$styles = $this->getStyle($css);
		$javascripts = $this->getJS($js);
		if (!defined('ANALYTICS'))
			$google_analytics = $this->getGoogle();

		/* wyczyszczenie bufora */
		ob_clean();
		/* wyswietlenie strony */

		# header
		if ($header)	
			include __SITE_PATH . 'views' . '/header.php';
		
		# body content
		if (isSet($nameIn)) {
			$tab = explode(",", $nameIn);
			if ($tab[0] == 'message') {
				include __SITE_PATH . 'views/message.php';
			} else {
				//echo '<form></form>';
				foreach ($tab as $name) {
					if (Core::startsWith($name, '/'))
						$path = __SITE_PATH . 'views' . $name . '.php';
					else 
						$path = __SITE_PATH . 'views' . '/' . $router . '/' .$name . '.php';
				
					if (file_exists($path) == false) {
						echo 'Wystąpił problem podczas ładowania widoku strony!';
						//throw new Exception('Wystąpił problem podczas ładowania widoku strony:  '. $path);
						//return false;
					} else
						include $path;
				}
			}
		}

		# footer
		if ($footer)
			include __SITE_PATH . 'views' . '/footer.php';

		// komunikat wyswietlony - trzeba usunac
		Core::removeMessage();
	}
}

?>

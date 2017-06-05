<?php

	Abstract Class baseController {
		
		protected $registry;

		function __construct($registry) {
			$this->registry = $registry;
			$this->db = $registry->db;
			$this->view = $registry->template;
		}

		abstract function index();

		public function loadModel($name) {
			$path = MODELS_PATH . strtolower($name) . '_model.php';
			if (file_exists($path)) {
		        $modelName = $name . 'Model';
				if (!class_exists($modelName)) {
					require $path;
				}
				return new $modelName($this->db);		        
		    }
	    }

	    public function setMessage($m) {
	    	$this->view->message = $m;
	    }

	    public function setTitle($t) {
	    	$this->view->title = $t;
	    }

	    public function setDescription($t) {
	    	$this->view->description = $t;
	    }

	    public function setKeywords($t) {
	    	$this->view->keywords = $t;
	    }

	    public function setMeta($t, $d, $k) {
	    	$this->setTitle($t);
	    	$this->setDescription($d);
	    	$this->setKeywords($k);
	    }


	}

?>

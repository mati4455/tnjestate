<?php

	Abstract Class baseModel {
		protected $db;
		 
		function __construct($db) {
			$this->db = $db;
		}
		
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
	}

?>

<?php

class View {
	
	function __construct() {
		
	}

	public function render($name, $variables = array()) {
		extract($variables);
		ob_start();
		$renderedView = ob_get_clean();
		require 'views/header.php';
		require 'views/'. $name . '.php';
		require 'views/footer.php';
		return $renderedView;
	}

}
<?php

class Controller {
	
	function __construct() {
		Session::init();
		$this->view = new View();
	}

	public function loadModel($name) {
		$path = 'models/'. $name .'_model.php';

		if (file_exists($path)) {
			require $path;

			$modelName = $name . '_Model';
			$this->model = new $modelName();
		}
	}

	public static function showCart() {
		if ($_SESSION && $_SESSION['cart']) {
			// Parse the cart session variable
			$items = explode(',', $_SESSION['cart']);
			$s = (count($items) > 1) ? 's' : '';
			return '<p>You have <a href="'.URL.'index/checkout">' . count($items) . ' item' . $s . ' in your shopping cart</a></p>';
		} else {
			return '<p>You have no items in your shopping cart</p>';
		}
	}
}
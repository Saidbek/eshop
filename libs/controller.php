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
		$cart = $_SESSION['cart'];

		if (!$cart) {
			return '<p>You have no items in your shopping cart</p>';
		} else {
			// Parse the cart session variable
			$items = explode(',', $cart);
			$s = (count($items) > 1) ? 's' : '';
			return '<p>You have <a href="'.URL.'index/checkout">' . count($items) . ' item' . $s . ' in your shopping cart</a></p>';
		}
	}

	function httpResponse($ch) {
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$response = curl_exec($ch);

		if(curl_error($ch))
		{
			curl_close($ch);
			return curl_error($ch);
		}

		$header = curl_getinfo($ch);
		$header['content'] = $response;

		return $header['content'];
	}
}
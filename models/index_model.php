<?php

class Index_Model extends Model {

	function __construct() {
		parent::__construct();
	}

	function all() {
		$products = $this->db->query("SELECT * FROM products");
		return $products->fetchAll(PDO::FETCH_ASSOC);
	}

	function checkoutList() {
		$cart = $_SESSION['cart'];
		$items = explode(',', $cart);

		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}

		foreach ($contents as $id => $qty) {
			$query = $this->db->prepare("SELECT * FROM products WHERE id=?");
			$query->execute(array($id));
			$results = $query->fetchAll(PDO::FETCH_ASSOC);
			if (empty($results)) return false;

			$new_ar[] = $results;
		}

		return $new_ar;
	}

	function subtotal($array) {
		$sum = 0;

		foreach ($array as $value) {
			foreach ($value as $v) {
				$sum += $v['amount'];
			}
		}
		return number_format((float)$sum, 1, '.', '');
	}

	function total_amount($array) {
		$sum = 0;

		foreach ($array as $value) {
			foreach ($value as $v) {
				$sum += $v['amount'] + $this->tax();
			}
		}

		$total = $sum + $this->shipping();
		return number_format((float)$total, 1, '.', '');
	}

	function return_url() {
		return RETURN_URL;
	}

	function abandon_url() {
		return ABANDON_URL;
	}

	function reference_number() {
		return uniqid();
	}

	function geopay_id_token() {
		return GEOPAY_ID_TOKEN;
	}

	function locale() {
		return LOCALE;
	}

	function description() {
		return DESCRIPTION;
	}

	function tax() {
		return TAX;
	}

	function shipping() {
		return SHIPPING;
	}

	function normalized_data($params) {
		$request_uri = AUTH_URL."customer/authorizations";
		$host = parse_url($request_uri)['host'];
		$verb = 'POST';
		$encoded_string = http_build_query($params);

		$return_string = preg_replace('/%5B\d+\%5D/', "%5B%5D", $encoded_string);
		return $host."\n".$verb."\n".$request_uri."\n".$return_string;
	}

	function normalized_data_execute($transaction_number, $attributes) {
		$request_uri = AUTH_URL.'partner/payments/'.$transaction_number.'/execute';
		$host = parse_url($request_uri)['host'];
		$date = gmdate("D, j M Y G:i:s")." GMT";
		$verb = 'PUT';
		$body = json_encode($attributes);

		$normalized_string = $host."\n".$verb."\n".$request_uri."\n".$date."\n".$body;
		return $normalized_string;
	}

	function generate_signature($normalized_string) {
		return trim(base64_encode(hash_hmac('sha256', $normalized_string, SECRET_KEY, true)));
	}

	function delete($id) {
		$cart = $_SESSION['cart'];
		$items = explode(',', $cart);

		$newcart = '';
		foreach ($items as $item) {
			if ($id != $item) {
				if ($newcart != '') {
					$newcart .= ',' . $item;
				} else {
					$newcart = $item;
				}
			}
		}
		$cart = $newcart;

		$_SESSION['cart'] = $cart;
	}
}
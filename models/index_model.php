<?php

class Index_Model extends Model {

	function __construct() {
		parent::__construct();
	}

	function all() {
		$products = $this->db->query("SELECT * FROM products");
		return $products->fetchAll(PDO::FETCH_ASSOC);
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

	function save($params) {
		$_SESSION['items'] = $params;
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
}
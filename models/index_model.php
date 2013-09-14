<?php

class Index_Model extends Model {
	
	function __construct() {
		parent::__construct();
	}

	function all() {
		$products = $this->db->query("SELECT * FROM products");
		return $products->fetchAll(PDO::FETCH_ASSOC);
	}

}
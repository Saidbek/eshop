<?php

class Model {

	function __construct() {
		$this->db = new Database();
	}

	function generate_signature($normalized_string) {
		return trim(base64_encode(hash_hmac('sha256', $normalized_string, SECRET_KEY, true)));
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

	function geopay_id_token() {
		return GEOPAY_ID_TOKEN;
	}
}
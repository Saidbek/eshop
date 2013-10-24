<?php

class Model {

	function __construct() {
		$this->db = new Database();
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

	function normalized_data_auth($params) {
		$request_uri = AUTH_URL."customer/authorizations";
		$uri = parse_url($request_uri);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'].':'.$uri['port'];
		$verb = 'POST';
		$encoded_string = http_build_query($params);

		$return_string = preg_replace('/%5B\d+\%5D/', "%5B%5D", $encoded_string);
		return $host."\n".$verb."\n".$request_uri."\n".$return_string;
	}

	function normalized_data_execute($transaction_number, $attributes) {
		$request_uri = AUTH_URL.'partner/payments/'.$transaction_number.'/execute';
		$uri = parse_url($request_uri);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'].':'.$uri['port'];
		$date = gmdate("D, j M Y H:i:s")." GMT";
		$verb = 'PUT';
		$body = json_encode($attributes);

		return $host."\n".$verb."\n".$request_uri."\n".$date."\n".$body;
	}

	function normalized_data_create($params) {
		$request_uri = AUTH_URL.'partner/payments';
		$uri = parse_url($request_uri);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'].':'.$uri['port'];
		$verb = 'POST';
		$date = gmdate("D, j M Y H:i:s")." GMT";
		$body = json_encode($params);

		return $host."\n".$verb."\n".$request_uri."\n".$date."\n".$body;
	}

	function normalized_data_show($transaction_number) {
		$request_uri = AUTH_URL.'partner/payments/'.$transaction_number;
		$uri = parse_url($request_uri);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'].':'.$uri['port'];
		$date = gmdate("D, j M Y H:i:s")." GMT";
		$verb = 'GET';

		return $host."\n".$verb."\n".$request_uri."\n".$date."\n";
	}

	function normalized_data_put($transaction_number) {
		$request_uri = AUTH_URL.'partner/payments/'.$transaction_number.'/cancel';
		$uri = parse_url($request_uri);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'].':'.$uri['port'];
		$date = gmdate("D, j M Y H:i:s")." GMT";
		$verb = 'PUT';

		return $host."\n".$verb."\n".$request_uri."\n".$date."\n";
	}

	function generate_signature($normalized_string) {
		return trim(base64_encode(hash_hmac('sha256', $normalized_string, SECRET_KEY, true)));
	}
}
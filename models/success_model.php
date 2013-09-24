<?php

class Success_Model extends Model {

	function __construct() {
		parent::__construct();
	}

	function normalized_data_execute($transaction_number, $attributes) {
		$request_uri = AUTH_URL.'partner/payments/'.$transaction_number.'/execute';
		$uri = parse_url($request_uri);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'].':'.$uri['port'];
		$date = gmdate("D, j M Y H:i:s")." GMT";
		$verb = 'PUT';
		$body = json_encode($attributes);

		$normalized_string = $host."\n".$verb."\n".$request_uri."\n".$date."\n".$body;
		return $normalized_string;
	}
}
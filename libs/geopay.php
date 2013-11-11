<?php

define('GEOPAY_URL', 'http://localhost:3009/');
define('RETURN_URL', 'http://eshop/success');
define('ABANDON_URL', 'http://eshop/index/fail');
define('GEOPAY_ID_TOKEN', '5LAHEH0K6G15D83G33ED');
define('SECRET_KEY', '8vIIiB0wEu6VnPPi/Pk8IpkkOeUR2a/snU85YfGK');
define('LOCALE', 'en');
define('DESCRIPTION', 'Svetofor Shopping Cart');
define('TAX', 2);
define('SHIPPING', 3);


class GeoPay
{
	public function geopay_signature($params)
	{
		ksort($params);
		$normalized_string = $this->normalized_data_auth($params);
		return $this->generate_signature($normalized_string);
	}

	public function send_create_request()
	{
		$url = GEOPAY_URL . 'partner/payments';
		$normalized_string = $this->normalized_data_create($url, $_POST);
		$content = json_encode($_POST);
//	$response = $this->http_request($normalized_string, $url, HTTP_METH_POST, $content);
		$response = $this->curl_request($normalized_string, $url, "POST", $content);
		return $response;
	}

	public function send_execute_request($id, $transaction_amount)
	{
		$url = GEOPAY_URL . 'partner/payments/' . $id . '/execute';
		$params = array('amount' => $transaction_amount);
		$normalized_string = $this->normalized_data_execute($url, $params);
		$content = json_encode($params);
//	$response = $this->http_request($normalized_string, $url, HTTP_METH_PUT, $content);
		$response = $this->curl_request($normalized_string, $url, "PUT", $content);
		return $response;
	}

	public function send_show_request($id)
	{
		$url = GEOPAY_URL . 'partner/payments/' . $id;
		$normalized_string = $this->normalized_data_show($url);
//		$response = $this->http_request($normalized_string, $url, HTTP_METH_GET);
		$response = $this->curl_request($normalized_string, $url, "GET");
		return $response;
	}

	public function send_cancel_request($id)
	{
		$url = GEOPAY_URL . 'partner/payments/' . $id . '/cancel';
		$normalized_string = $this->normalized_data_cancel($url);
//		$response = $this->http_request($normalized_string, $url, HTTP_METH_PUT);
		$response = $this->curl_request($normalized_string, $url, "PUT");
		return $response;
	}

	private function normalized_data_execute($url, $attributes)
	{
		$uri = parse_url($url);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'] . ':' . $uri['port'];
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$verb = 'PUT';
		$body = json_encode($attributes);

		return $host . "\n" . $verb . "\n" . $url . "\n" . $date . "\n" . $body;
	}

	private function normalized_data_auth($params)
	{
		$request_uri = GEOPAY_URL . 'customer/authorizations';
		$uri = parse_url($request_uri);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'] . ':' . $uri['port'];
		$encoded_string = http_build_query($params);
		$return_string = preg_replace('/%5B\d+\%5D/', "%5B%5D", $encoded_string);
		return $host . "\n" . 'POST' . "\n" . $request_uri . "\n" . $return_string;
	}

	private function normalized_data_create($url, $params)
	{
		$uri = parse_url($url);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'] . ':' . $uri['port'];
		$verb = 'POST';
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$body = json_encode($params);

		return $host . "\n" . $verb . "\n" . $url . "\n" . $date . "\n" . $body;
	}

	private function normalized_data_show($url)
	{
		$uri = parse_url($url);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'] . ':' . $uri['port'];
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$verb = 'GET';

		return $host . "\n" . $verb . "\n" . $url . "\n" . $date . "\n";
	}

	private function normalized_data_cancel($url)
	{
		$uri = parse_url($url);
		$host = empty($uri['port']) ? $uri['host'] : $uri['host'] . ':' . $uri['port'];
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$verb = 'PUT';

		return $host . "\n" . $verb . "\n" . $url . "\n" . $date . "\n";
	}

	private function generate_signature($normalized_string)
	{
		return trim(base64_encode(hash_hmac('sha256', $normalized_string, SECRET_KEY, true)));
	}

	private function http_request($normalized_string, $url, $verb, $content = NULL)
	{
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$geopay_signature = $this->generate_signature($normalized_string);
		$request = new HTTPRequest($url, $verb);
		if ($verb == HTTP_METH_POST) {
			$request->setRawPostData($content);
		} elseif ($verb == HTTP_METH_PUT) {
			$request->setPutData($content);
		}
		$request->setHeaders(array('authorization' => 'GeoPay ' . GEOPAY_ID_TOKEN . ':' . $geopay_signature, "date" => $date, "Content-Type" => "application/json; charset=utf-8", "Accept" => "application/json"));
		$request->send();
		return $request->getResponseBody();
	}

	private function curl_request($normalized_string, $url, $verb, $content = NULL)
	{
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$geopay_signature = $this->generate_signature($normalized_string);
		$ch = curl_init($url);
		$redirects = 0;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization: GeoPay ' . GEOPAY_ID_TOKEN . ':' . $geopay_signature . '', 'date: ' . $date . '', "Content-Type: application/json; charset=utf-8", "Accept:application/json"));

		//Can be PUT/POST/PATCH
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code == 301 || $http_code == 302) {
			list($header) = explode("\r\n\r\n", $response, 2);
			$matches = array();
			preg_match('/(Location:|URI:)(.*?)\n/', $header, $matches);
			$url = trim(array_pop($matches));
			$url_parsed = parse_url($url);
			if (isset($url_parsed)) {
				curl_setopt($ch, CURLOPT_URL, $url);
				$redirects++;
				return $this->curl_request($normalized_string, $url, $verb, $content = NULL);
			}
		}
		if (curl_error($ch)) {
			curl_close($ch);
			return curl_error($ch);
		}

		$header = curl_getinfo($ch);
		$header['content'] = $response;
		return json_decode($header['content'], true);
	}
}
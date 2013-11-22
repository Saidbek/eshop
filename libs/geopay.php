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
	private $error = array(
		3101 => 'Аккаунт не активный',
		3102 => 'Ошибка при сохранении данных транзакции',
		3103 => 'Аккаунт не найден',
		3104 => 'Ошибка при сохранении ожидаемого перевода',
		3105 => 'Ошибка при вычислении комиссии',
		3106 => 'Клиент не подтвердил платеж',
		3107 => 'Клиент отменин платеж',
		3108 => 'Не удалось отправить СМС клиенту',
		3109 => 'Не удалось отменить предыдущий ожидаемый платеж',
		3110 => 'Не удалось снять средства со счета клиента',
		3111 => 'Платеж отменен партнером',
		3112 => 'Сотовый оператор не подтвердил валидность номера телефона',
		3113 => 'Сотовый оператор не доступен',
		3114 => 'Ошибка при проверке получателя',
		3115 => 'Проблема с переводом',
		3121 => 'Запрашиваемая сумма не является числом',
		3122 => 'Запрашиваемая сумма должна быть больше или равно нулю',
		3123 => 'Не достаточно средств на балансе клиента',
		3124 => 'Запрашиваемая сумма больше лимита перевода',
		3125 => 'При увеличении баланса клиента на запрашиваемую сумму, баланс превысит лимит',
		3126 => 'Запрашиваемая сумма превысит лимит пополнение счета партнера',
		3127 => 'Ошибка при сохранении перевода',

		3201 => 'Неверный запрос',
		3202 => 'Неверная транзакция для отмены',
		3203 => 'Незавершенный платеж не возможно отменить',
		3204 => 'Ошибка при сохранении ожидаемого перевода',
		3205 => 'Ошибка при инициации отмены платежа',
		3206 => 'Ожидаемый перевод не возможно отменить',
		3207 => 'Ошибка начала перевода',
		3208 => 'Неизвестная ошибка',
		3221 => 'Запрашиваемая сумма не является числом',
		3222 => 'Запрашиваемая сумма должна быть больше или равно нулю',
		3223 => 'Не достаточно средств на балансе клиента',
		3224 => 'Запрашиваемая сумма больше лимита перевода',
		3225 => 'При увеличении баланса клиента на запрашиваемую сумму, баланс превысит лимит',
		3226 => 'Запрашиваемая сумма превысит лимит пополнение счета партнера',
		3227 => 'Ошибка при сохранении перевода',

		4001 => 'Ошибка с аккаунтом партнера',
		4002 => 'Клиент не отвечает',
		4003 => 'Данный маркер устройства не может быть использован',
		4004 => 'Аккаунт не найден',
		4005 => 'Аккаунт заблокирован',
		4006 => 'Не удалось отправить СМС клиенту',
		4007 => 'Ошибка при инициации перевода',
		4008 => 'Платеж отменен партнером',
		4009 => 'Код авторизации или ПИН код неверный',
		4010 => 'Ошибка при сохранении перевода',
		4011 => 'Партнер не завершил платеж',
		4012 => 'Ошибка при отмене платежа',
		4013 => 'Проблема при авторизации партнера',
		4014 => 'Сумма транзакции не совпадает',
		4015 => 'Ошибка при проведении перевода',
		4016 => 'Неверный запрос',
		4017 => 'Неизвестная ошибка',
		4018 => 'Дупликация запроса',
		4021 => 'Запрашиваемая сумма не является числом',
		4022 => 'Запрашиваемая сумма должна быть больше или равно нулю',
		4023 => 'Не достаточно средств на балансе клиента',
		4024 => 'Запрашиваемая сумма больше лимита перевода',
		4025 => 'Ошибка при сохранении перевода',

		4101 => 'Неверная дата запроса',
		4102 => 'Неверный идентификатор партнера',
		4103 => 'Нет секретного ключа партнера',
		4104 => 'Электронная подпись неверна'

	);

	function __construct()
	{
		if (!function_exists('curl_init')) echo '<p><b>Error!</b> CURL is not supported ...</p>';
	}

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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization: GeoPay ' . GEOPAY_ID_TOKEN . ':' . $geopay_signature . '', 'date: ' . $date . '', "Content-Type: application/json; charset=utf-8", "Accept:application/json"));

		//Can be PUT/POST/PATCH
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

		$response = curl_exec($ch);

		if (curl_error($ch)) {
			curl_close($ch);
			return curl_error($ch);
		}

		$header = curl_getinfo($ch);
		$header['content'] = $response;
		return json_decode($header['content'], true);
	}

	function error($code)
	{
		if (!empty($code)) return $code . ': ' . $this->error[$_GET['error_code']];
	}
}
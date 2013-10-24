<?php

class Redemption extends Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->view->render('redemption/index');
	}

	function create()
	{
		$url = AUTH_URL . 'partner/payments';
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$normalized_string = $this->model->normalized_data_create($_POST);
		$content = json_encode($_POST);
		$geopay_signature = $this->model->generate_signature($normalized_string);

		$request = new HTTPRequest($url, HTTP_METH_POST);
		$request->setRawPostData($content);
		$request->setHeaders(array('authorization' => 'GeoPay ' . $this->model->geopay_id_token() . ':' . $geopay_signature, "date" => $date, "Content-Type" => "application/json; charset=utf-8", "Accept" => "application/json"));

		# Send our request
		$request->send();

		# Get the response
		$response = $request->getResponseBody();

		if (strpos($response, 'success') !== FALSE) {
			Session::destroy();
			header('Location: index/success');
		} else {
			Session::destroy();
			header('Location: index/fail');
		}
	}
}
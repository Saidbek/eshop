<?php

class Redemption extends Controller
{
	public $transaction_number;

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
		$request->send();

		$response = $request->getResponseBody();
		$this->setValue(json_decode($response)->transaction_number);

		if (strpos($response, 'pending') !== FALSE) {
			$this->view->render('redemption/pending', array('response' => $response, 'transaction_number' => $this->getValue()));
		} else {
			$this->view->render('index/fail');
		}
	}

	function show($id)
	{
		$url = AUTH_URL . 'partner/payments/' . $id;
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$normalized_string = $this->model->normalized_data_show($id);
		$geopay_signature = $this->model->generate_signature($normalized_string);

		$request = new HTTPRequest($url, HTTP_METH_GET);
		$request->setHeaders(array('authorization' => 'GeoPay ' . $this->model->geopay_id_token() . ':' . $geopay_signature, "date" => $date, "Content-Type" => "application/json; charset=utf-8", "Accept" => "application/json"));
		$request->send();
		$response = $request->getResponseBody();

		if (strpos($response, 'approved_pending') !== FALSE) {
			$this->view->render('redemption/show', array('response' => $response, 'transaction_number' => $id));
		} else {
			$this->view->render('redemption/pending', array('response' => $response, 'transaction_number' => $id));
		}
	}

	function execute($id)
	{
		$url = AUTH_URL . 'partner/payments/' . $id . '/execute';
		$attrs = array('amount' => $_GET["amount"]);
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$normalized_string = $this->model->normalized_data_execute($id, $attrs);
		$content = json_encode($attrs);
		$geopay_signature = $this->model->generate_signature($normalized_string);

		$request = new HTTPRequest($url, HTTP_METH_PUT);
		$request->setPutData($content);
		$request->setHeaders(array('authorization' => 'GeoPay ' . $this->model->geopay_id_token() . ':' . $geopay_signature, "date" => $date, "Content-Type" => "application/json; charset=utf-8", "Accept" => "application/json"));
		$request->send();
		$response = $request->getResponseBody();

		if (strpos($response, 'success') !== FALSE) {
			$this->view->render('index/success');
		} else {
			$this->view->render('index/fail');
		}
	}

	function cancel($id)
	{
		$url = AUTH_URL . 'partner/payments/' . $id . '/cancel';
		$date = gmdate("D, j M Y H:i:s") . " GMT";
		$normalized_string = $this->model->normalized_data_put($id);
		$geopay_signature = $this->model->generate_signature($normalized_string);

		$request = new HTTPRequest($url, HTTP_METH_PUT);
		$request->setHeaders(array('authorization' => 'GeoPay ' . $this->model->geopay_id_token() . ':' . $geopay_signature, "date" => $date, "Content-Type" => "application/json; charset=utf-8", "Accept" => "application/json"));
		$request->send();
		$response = $request->getResponseBody();

		if (strpos($response, 'success') !== FALSE) {
			$this->view->render('index/success');
		} else {
			$this->view->render('index/fail');
		}
	}

	function pending()
	{
		$this->view->render('redemption/pending');
	}

	function setValue($transaction_number)
	{
		$this->transaction_number = $transaction_number;
	}

	function getValue()
	{
		return $this->transaction_number;
	}
}
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
		$geopay = new GeoPay();
		$response = $geopay->send_create_request();
		$this->setValue($response['transaction_number']);

		if ($response['status'] == 'pending') {
//			HTTPRequest
//			$this->view->render('redemption/pending', array('response' => $response, 'transaction_number' => $this->getValue()));
//			CURL
			$this->view->render('redemption/pending', array('response' => $response, 'transaction_number' => $response["transaction_number"]));
		} else {
			$this->view->render('index/fail');
		}
	}

	function show($id)
	{
		$geopay = new GeoPay();
		$response = $geopay->send_show_request($id);

		if ($response['status'] == 'approved_pending') {
			$this->view->render('redemption/show', array('response' => $response, 'transaction_number' => $id));
		} else {
			$this->view->render('redemption/pending', array('response' => $response, 'transaction_number' => $id));
		}
	}

	function execute($id)
	{
		$geopay = new GeoPay();
		$transaction_amount = $_GET["amount"];
		$response = $geopay->send_execute_request($id, $transaction_amount);

		if ($response['status'] == 'success') {
			$this->view->render('index/success');
		} else {
			$this->view->render('index/fail');
		}
	}

	function cancel($id)
	{
		$geopay = new GeoPay();
		$response = $geopay->send_cancel_request($id);

		if ($response['status'] == 'success') {
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
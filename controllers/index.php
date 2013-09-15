<?php

class Index extends Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->view->products = $this->model->all();
		$this->view->render('index/index');
	}

	function add($id) {
		Session::set('cart', $id);
		header('Location: ../checkout');
	}

	function checkout() {
		$checkout_list = $this->model->checkoutList();
		if ($checkout_list) {
			$this->view->items = $checkout_list;
			$this->view->total_amount = $this->model->total_amount($checkout_list);
		}
		$this->view->render('index/checkout');
	}

	function destroy($id) {
		$this->model->delete($id);
		header('Location: ../checkout');
	}
	
}
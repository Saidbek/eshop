<?php

class Index extends Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->view->products = $this->model->all();
		$this->view->render('index/index');
	}
	
}
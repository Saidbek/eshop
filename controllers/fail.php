<?php

class Fail extends Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->view->query_string = $_SERVER['QUERY_STRING'];
		$this->view->render('fail/index');
	}

}
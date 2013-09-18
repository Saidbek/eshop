<?php

class Success extends Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->view->render('success/index');
	}

}
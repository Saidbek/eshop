<?php

class Help extends Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->view->msg = 'Help page';
		$this->view->render('help/index');
	}
	
}
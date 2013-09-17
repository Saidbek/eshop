<?php

class Session {

	public static function init() {
		session_start();
	}

	public static function set($key, $value) {
		if ($_SESSION[$key]) {
			$items = explode(',', $_SESSION[$key]);			
			if (!in_array($value, $items)) {
				$_SESSION[$key] .= ',' . $value;				
			}
		} else {
			$_SESSION[$key] = $value;
		}
	}

}
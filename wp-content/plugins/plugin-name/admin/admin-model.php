<?php
/**
 * Class that acts as a Controller for the admin functions.
 */
class Admin_Controller {

	public function main() {
		include_once 'partials/main.html.php';
	}

	public function categories() {
		include_once 'partials/categories.html.php';
	}

	public function about() {
		include_once 'partials/about.html.php';
	}

}
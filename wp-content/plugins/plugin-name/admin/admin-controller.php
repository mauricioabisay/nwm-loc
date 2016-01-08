<?php
/**
 * Class that acts as a Controller for the admin functions.
 */
class Admin_Controller {
	
	public function main() {
	       include_once 'partials/main.html.php';
	}

	public function categories() {
                require_once 'models/categories-model.php';
                $categories_model = new Categories_Model();
                $results = $categories_model->get();
                include_once 'partials/categories.html.php';
	}

        public function categories_add() {
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST["name"])) {
                                $name = $_POST["name"];
                        } else {
                                $name = '';
                        }
                        if (isset ($_POST['description'])) {
                                $description = $_POST['description'];
                        } else {
                                $description = '';
                        }
                        require_once 'models/categories-model.php';
                        $categories_model = new Categories_Model();
                        $categories_model->create(array(
                                'name' => $name,
                                'description' => $description,
                                'category' => '0'
                        ));
                        $results = $categories_model->get();
                        include_once 'partials/categories.html.php';
                } else {
                        include_once 'partials/categories_add.html.php';
                }
        }

	public function about() {
		include_once 'partials/about.html.php';
	}

}
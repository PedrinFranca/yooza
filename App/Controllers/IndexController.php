<?php

namespace App\Controllers;
 
//os recursos do miniframework

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		
		$this->render('index');
	}
	public function create_account() {
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		
		$this->render('create_account');
	}

	

}


?>
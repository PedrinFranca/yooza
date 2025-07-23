<?php

namespace App\Controllers;
 
//os recursos do miniframework

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function chat_list() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		
		$this->render('chat_list');
	}

	

}


?>
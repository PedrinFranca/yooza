<?php

namespace App\Controllers;
 
//os recursos do miniframework

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function chat_list() {
		$this->validaAutenticacao();
		// echo "<pre>";
		// print_r($_SESSION);
		// echo "</pre>";
		
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$user = Container::getModel('User');
			if($_POST['username'] != ''){
				$user->__set('id', $_SESSION['id']);
				$user->__set('username', $_POST['username']);
	
				$users = $user->getAll();
				foreach($users as $key => $user){
	
					echo "
					<div class=\"card-user-search row justify-content-center\" onclick=\"initRelation({$user['id']})\">
						<div class=\"col-12 row justify-content-end\">
							{$user['name']}
						</div>
						<div class=\"col-12 row justify-content-end\">
							{$user['username']}
						</div>
					</div>
					";
	
				}
			}
			exit;
		}

		if(isset($_GET['id_to']) && $_GET['id_to'] != ''){

			// echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
			$user = Container::getModel('User');
			$user->__set('id', $_GET['id_to']);

			$user_chat = $user->getUserBy('id');

			$this->view->user_chat = $user_chat;

			// echo "<pre>";
			// print_r($user_chat);
			// echo "</pre>";

		}
		

		$this->render('chat_list');

		if(isset($_GET['modal']) && $_GET['modal'] == 'search'){
			$this->renderModal('search');




		}
	}

	public function validaAutenticacao(){
		session_start();

		// if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['name']) || $_SESSION['name'] == '' || !isset($_SESSION['username']) || $_SESSION['username'] == ''){
		if(!isset($_SESSION['id']) || $_SESSION['id'] == ''){
			header('Location: /?login=not_auth');   
		} 
	}

	

}


?>
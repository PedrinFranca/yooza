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
		$relation = Container::getModel('Relations');
		$relation->__set('user1', $_SESSION['id']);
		$relations = $relation->getRelation();
		$this->view->relations = $relations;
		
		
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$action = $_POST['action'] ?? "";
			switch($action){
				case 'get_user_to_search':
					$user = Container::getModel('User');
					if($_POST['username'] != ''){
						$user->__set('id', $_SESSION['id']);
						$user->__set('username', $_POST['username']);
			
						$users = $user->getAll();
						foreach($users as $key => $user){
			
							echo "
							<div class=\"card-user-search row justify-content-center\" onclick=\"openChat({$user['id']})\">
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
					break;


				case 'get_new_messages':
					$message = Container::getModel('Message');
					$message->__set('from', $_SESSION['id']);
					$message->__set('to', $_GET['id_to']);
					$messages = $message->getAll();


					foreach($messages as $key => $message) { 
						$echoTrash = "";
						if($_SESSION['id'] == $message['from']) {
							echo '
								<div class="row justify-content-end align-items-center pl-5 pr-5 pt-2 pb-2">

									<div class="message-i col-5 w-auto">
							';

							$echoTrash = "
								<span class=\"message-icon\">
									<form action=\"/message\" method=\"POST\">
										<input type=\"hidden\" name=\"action\" value=\"del\">
										<input type=\"hidden\" name=\"id\" value=\"{$message['id']}\">
										<input type=\"hidden\" name=\"id_to\" value=\"{$_GET['id_to']}\">
										<i class=\"fa-solid fa-trash-can fa-lg\" onclick=\"this.closest('form').submit()\" style=\"cursor: pointer;\"></i>
									</form>
								</span>
							";

							
						} else {

							echo '
								<div class="row justify-content-start align-items-center pl-5 pr-5 pt-2 pb-2">

									<div class="message-he col-5 w-auto">
								';
						}
						echo "
										<div class=\"message-msg\">
											<p>
												{$message['message']}
											</p>
										</div>
										<div class=\"message-footer\">
											{$echoTrash}
											<span class=\"message-data align-self-end\">
												{$message['data']}
											</span>
										</div>
									</div>
								</div>";
					}  
					exit;
					break;

				case 'get_new_relations':
					$relation = Container::getModel('Relations');
					$relation->__set('user1', $_SESSION['id']);
					$relations = $relation->getRelation();

					foreach($relations as $key => $relation) {
						if($relation['user_1'] == $_SESSION['id']) {
							echo "
								<div class=\"col-md-12 mb-3\" onclick=\"openChat(".$relation['id_u2'].");\">
									<div class=\"chat-card row justify-content-center pb-5\">
										<div class=\"col-8 align-self-start d-flex justify-content-center\">". ($relation['surname_2'] ?? $relation['username_u2']). "</div>
							";
						} else {
							echo "
								<div class=\"col-md-12 mb-3\" onclick=\"openChat(".$relation['id_u'].");\">
									<div class=\"chat-card row justify-content-center pb-5\">
										<div class=\"col-8 align-self-start d-flex justify-content-center\">". ($relation['surname_1'] ?? $relation['username_u']). "</div>
							";
						}
						echo "
										<span class=\"col-8 d-flex justify-content-start\">". (strlen($relation['last_message']) >= 45 ? substr($relation['last_message'], 0, 45) .'...' : $relation['last_message']) ." </span>
										<span class=\"col-4 d-flex justify-content-end\">".$relation['data_last_message']."</span>
									</div>
								</div>
						";
					} 
					
					exit;
					break;


				default:
					break;

			}
		}

		if(isset($_GET['id_to']) && $_GET['id_to'] != ''){

			
			$user = Container::getModel('User');
			$message = Container::getModel('Message');
			

			
			$user->__set('id', $_GET['id_to']);
			$user_chat = $user->getUserBy('id');

			$message->__set('from', $_SESSION['id']);
			$message->__set('to', $_GET['id_to']);
			$messages = $message->getAll();


			$this->view->messages = $messages;
			$this->view->user_chat = $user_chat[0];


		}

		$this->render('chat_list');

		if(isset($_GET['modal']) && $_GET['modal'] == 'search'){
			$this->renderModal('search');
		}
	}

	public function send_msg(){
		$this->validaAutenticacao();

		$message = Container::getModel('Message');
		$relation = Container::getModel('Relations');
		$relation->__set('user1', $_SESSION['id']);
		
		$message->__set('from', $_SESSION['id']);
		$message->__set('to', $_POST['id_to']);
		$message->__set('type_msg', 1);
		$message->__set('message', $_POST['msg']);
		
		$message->send_msg();
		
		$relation->__set('user1', $_SESSION['id']);
		$relation->__set('user2', $_POST['id_to']);
		$lastId = $relation->getRelationBy2()['id'] ?? "";
		// echo "<pre>";
		// print_r($relation->getRelationBy2() ?? "Nada");
		// echo "</pre>";
		if(!$relation->getRelationBy2()){
			$lastId = $relation->createRelation();
		}
		$relation->__set('id', $lastId);
		$relation->update_at();
		header("Location: /chat_list?id_to=".$_POST['id_to']);

		
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
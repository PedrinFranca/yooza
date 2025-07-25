<?php

namespace App\Controllers;
 
//os recursos do miniframework

use MF\Controller\Action;
use MF\Model\Container;

class MsgController extends Action {


    public function message(){
        $this->validaAutenticacao();
        $action = $_POST['action'] ?? "";
        $message = Container::getModel('Message');
        switch($action){
            case 'del':
                $message->__set('id', $_POST['id']);
                if($message->getMessageBy('id')[0]['from'] == $_SESSION['id']){
                    $message->del_message();
                    header("Location: /chat_list?id_to={$_POST['id_to']}");
                } else {
                    header('Location: /?login=not_auth');   
                }
                break;
        }

    }

	public function validaAutenticacao(){
		session_start();
		if(!isset($_SESSION['id']) || $_SESSION['id'] == ''){
			header('Location: /?login=not_auth');   
		} 
	}

}


?>
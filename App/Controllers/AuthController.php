<?php

namespace App\Controllers;
 
//os recursos do miniframework

use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {

	public function login() {
		
		echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        $user = Container::getModel('User');

        $user->__set('email', $_POST['email']);
        $user->__set('password', $_POST['password']);
        
        $user->login();

        if(!is_null($user->__get('id')) && !is_null($user->__get('name'))){
            session_start();
            $_SESSION['name'] = $user->__get('name');
            $_SESSION['id'] = $user->__get('id');
            $_SESSION['email'] = $user->__get('email');
            header("Location: /chat_list");
        } else {
            header('Location: /?login=error');
        }
	}
	
	
	public function logoff() {
        session_destroy();
        header("Location: /");
    }   

}


?>
<?php

namespace App\Controllers;
 
//os recursos do miniframework

use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		
		$this->render('index');
	}

	public function create_account() {
		$this->view->usuario = array(
                'name' => "",
                'email' => "",
                'username' => "",
                'password' => ""
            );
		
		$this->render('create_account');
	}

	public function register() {

        $user = Container::getModel('User');

        $user->__set('name', $_POST['name']);
        $user->__set('email', $_POST['email']);
        $user->__set('password', $_POST['password']);
        $user->__set('username', $_POST['username']);
        
        if($user->validedRegister()['return'] && count($user->getUserBy('email')) == 0 && count($user->getUserBy('username')) == 0){
            $user->__set('password', password_hash($_POST['password'], PASSWORD_BCRYPT));
            $user->register();
            $this->render('register-success');
        } else {
            $this->view->usuario = array(
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'username' => $_POST['username'],
                'password' => $_POST['password']
            );
			if(count($user->getUserBy('email')) > 0){
				header('Location: /create_account?error=user_exist');
			}
			if(count($user->getUserBy('username')) > 0){
				header('Location: /create_account?error=username_exist');
			}

            switch ($user->validedRegister()['error']){
                case 'password':
                    header('Location: /create_account?error=password');
                    break;
                case 'username':
                    header('Location: /create_account?error=username');
                    break;
            }
        }

	}
	
	
	

}


?>
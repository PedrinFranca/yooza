<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);
		$routes['create_account'] = array(
			'route' => '/create_account',
			'controller' => 'indexController',
			'action' => 'create_account'
		);
		$routes['register'] = array(
			'route' => '/register',
			'controller' => 'indexController',
			'action' => 'register'
		);
		$routes['chat_list'] = array(
			'route' => '/chat_list',
			'controller' => 'AppController',
			'action' => 'chat_list'
		);
		$routes['login'] = array(
			'route' => '/login',
			'controller' => 'AuthController',
			'action' => 'login'
		);
		$routes['logoff'] = array(
			'route' => '/logoff',
			'controller' => 'AuthController',
			'action' => 'logoff'
		);
		$routes['send_msg'] = array(
			'route' => '/send_msg',
			'controller' => 'AppController',
			'action' => 'send_msg'
		);
		$routes['message'] = array(
			'route' => '/message',
			'controller' => 'MsgController',
			'action' => 'message'
		);
		
		$this->setRoutes($routes);
	}

}

?>
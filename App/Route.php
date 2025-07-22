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
		
		$this->setRoutes($routes);
	}

}

?>
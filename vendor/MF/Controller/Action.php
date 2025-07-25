<?php

namespace MF\Controller;

abstract class Action {

	protected $view;

	public function __construct() {
		$this->view = new \stdClass();
	}

	protected function isAjax() {
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

	protected function validaAutenticacao(){
		session_start();
		if(!isset($_SESSION['id']) || $_SESSION['id'] == ''){
			header('Location: /?login=not_auth');   
		} 
	}


	protected function render($view, $layout = 'layout') {
		$this->view->page = $view;

		if(file_exists("../App/Views/".$layout.".phtml")) {
			require_once "../App/Views/".$layout.".phtml";
		} else {
			$this->content();
		}
	}
	
	protected function renderModal($modal) {
		$this->view->modal = $modal;

		if(file_exists("../App/Views/modal/".$modal.".phtml")) {
			require_once "../App/Views/modal/".$modal.".phtml";
		}
	}

	protected function content() {
		$classAtual = get_class($this);

		$classAtual = str_replace('App\\Controllers\\', '', $classAtual);

		$classAtual = strtolower(str_replace('Controller', '', $classAtual));

		require_once "../App/Views/".$classAtual."/".$this->view->page.".phtml";
	}
}

?>
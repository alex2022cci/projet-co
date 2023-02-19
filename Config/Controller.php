<?php

/**
 * Controller
 **/
class Controller
{

	public $request;  				// Objet Request 
	private $vars     = array();	// Variables à passer à la vue
	public $layout    = 'default';  // Layout à utiliser pour rendre la vue
	private $rendered = false;		// Si le rendu a été fait ou pas ?


	/**
	 * Constructeur
	 * @param $request Objet request de notre application
	 **/
	function __construct($request = null)
	{
		$this->Session = new Session();
		$this->Form = new Form($this);

		if ($request) {
			$this->request = $request; 	// On stocke la request dans l'instance	
			require ROOT . DS . 'Config' . DS . 'hook.php';
		}
	}


	/**
	 * Permet de rendre une vue
	 * @param $view Fichier à rendre (chemin depuis view ou nom de la vue) 
	 **/
	public function render($view)
	{
		if ($this->rendered) {
			return false;
		}
		extract($this->vars);
		if (strpos($view, '/') === 0) {
			$view = ROOT . DS . 'Template' . $view . '.php';
		} else {
			$view = ROOT . DS . 'Template' . DS . $this->request->controller . DS . $view . '.php';
		}

		ob_start();
		require($view);
		$content_for_layout = ob_get_clean();
		require ROOT . DS . 'Template' . DS . 'layout' . DS . $this->layout . '.php';
		$this->rendered = true;
	}


	/**
	 * Permet de passer une ou plusieurs variable à la vue
	 * @param $key nom de la variable OU tableau de variables
	 * @param $value Valeur de la variable
	 **/
	public function set($key, $value = null)
	{
		if (is_array($key)) {
			$this->vars += $key;
		} else {
			$this->vars[$key] = $value;
		}
	}

	/**
	 * Permet de charger un model
	 **/
	function loadModel($name)
	{
		if (!isset($this->$name)) {
			$file = ROOT . DS . 'Src' . DS . 'Model' . DS . $name . '.php';
			require_once($file);
			$this->$name = new $name();
			if (isset($this->Form)) {
				$this->$name->Form = $this->Form;
			}
		}
	}

	/**
	 * Permet de gérer les erreurs 404
	 **/
	function e404($message)
	{
		header("HTTP/1.0 404 Not Found");
		$this->set('message', $message);
		$this->render('/errors/404');
		die();
	}

	/**
	 * Permet d'appeller un controller depuis une vue
	 **/
	function request($controller, $action)
	{
		$controller .= 'Controller';
		require_once ROOT . DS . 'Src' . DS . 'Controller' . DS . $controller . '.php';
		$c = new $controller();
		return $c->$action();
	}

	/**
	 * Redirect
	 **/
	function redirect($url, $code = null)
	{
		if ($code == 301) {
			header("HTTP/1.1 301 Moved Permanently");
		}
		header("Location: " . Router::url($url));
	}
}

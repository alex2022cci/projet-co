<?php
class Conf
{
	static $debug = 1;

	static $databases = array(
		// informations de connexion à la base de données par défaut
		'default' => array(
			'host'		=> 'localhost',
			'database'	=> 'projet-co',
			'login'		=> 'root',
			'password'	=> ''
		)
	);
}

// Configuration du routeur
// Nota : Le routeur est une fonctionnalité qui permet de mapper les URLs du site web à des actions spécifiques dans l'application.

Router::prefix('', '');
Router::connect('', 'index');

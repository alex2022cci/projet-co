<?php
class Form
{
	/**
	 * la classe Form est utilisée pour générer des champs de formulaire HTML.
	 */


	public $controller; // propriété publique qui stocke l'instance de contrôleur en cours d'utilisation.
	public $errors;     // propriété publique qui contient les erreurs éventuelles associées à chaque champ de formulaire.

	public function __construct($controller)
	/**
	 * la méthode publique __construct est appelée lorsqu'une nouvelle instance de Form est créée.
	 * Elle prend le paramètre $controller.
	 */
	{
		$this->controller = $controller;
	}

	public function input($name, $label, $options = array())
	/**
	 * La méthode publique input prend trois paramètres : le nom du champ, le label à afficher, et un tableau d'options facultatif qui peut contenir des attributs HTML supplémentaires à ajouter au champ.
	 * La méthode commence par initialiser deux variables, $error et $classError, qui stockent respectivement l'erreur (s'il y en a une) et une chaîne vide.
	 * Si un message d'erreur est associé au champ, la variable $error est initialisée avec ce message et la variable $classError est initialisée avec la chaîne " error" (un espace suivi du mot "error") pour que le champ soit stylisé avec une couleur d'erreur en CSS.
	 */
	{
		$error = false;
		$classError = '';
		if (isset($this->errors[$name])) {
			$error = $this->errors[$name];
			$classError = ' error';
		}
		if (!isset($this->controller->request->data->$name)) {
			$value = '';
		} else {
			$value = $this->controller->request->data->$name;
		}
		if ($label == 'hidden') {
			return '<input type="hidden" name="' . $name . '" value="' . $value . '">';
		}
		$html = '<div class="clearfix' . $classError . '">
					<label for="input' . $name . '">' . $label . '</label>
					<div class="input">';
		$attr = ' ';
		foreach ($options as $k => $v) {
			if ($k != 'type') {
				$attr .= $k . '="' . $v . '"';
			}
		}
		if (!isset($options['type']) && !isset($options['options'])) {
			$html .= '<input type="text" id="input' . $name . '" name="' . $name . '" value="' . $value . '"' . $attr . '>';
		} elseif (isset($options['options'])) {
			$html .= '<select id="input' . $name . '" name="' . $name . '">';
			foreach ($options['options'] as $k => $v) {
				$html .= '<option value="' . $k . '" ' . ($k == $value ? 'selected' : '') . '>' . $v . '</option>';
			}
			$html .= '</select>';
		} elseif ($options['type'] == 'textarea') {
			// input textarea
			$html .= '<textarea id="input' . $name . '" name="' . $name . '"' . $attr . '>' . $value . '</textarea>';
		} elseif ($options['type'] == 'checkbox') {
			// input checkbox
			$html .= '<input type="hidden" name="' . $name . '" value="0"><input type="checkbox" name="' . $name . '" value="1" ' . (empty($value) ? '' : 'checked') . '>';
		} elseif ($options['type'] == 'file') {
			// input file
			$html .= '<input type="file" class="input-file" id="input' . $name . '" name="' . $name . '"' . $attr . '>';
		} elseif ($options['type'] == 'password') {
			// input password
			$html .= '<input type="password" id="input' . $name . '" name="' . $name . '" value="' . $value . '"' . $attr . '>';
		}
		if ($error) {
			$html .= '<span class="help-inline">' . $error . '</span>';
		}
		$html .= '</div></div>';
		return $html;
	}
}

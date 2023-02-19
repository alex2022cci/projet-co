<?php
class Session
{

	public function __construct()
	{
		if (!isset($_SESSION)) {
			session_start();
		}
	}

	public function setFlash($message, $type = 'success')
	{
		$_SESSION['flash'] = array(
			'message' => $message,
			'type'	=> $type
		);
	}

	public function flash()
	{
		if (isset($_SESSION['flash']['message'])) {
			$html = '<div class="alert-message ' . $_SESSION['flash']['type'] . '"><p>' . $_SESSION['flash']['message'] . '</p></div>';
			$_SESSION['flash'] = array();
			return $html;
		}
	}

	public function write($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public function read($key = null)
	{
		if ($key) {
			if (isset($_SESSION[$key])) {
				return $_SESSION[$key];
			} else {
				return false;
			}
		} else {
			return $_SESSION;
		}
	}

	public function isLogged()
	{
		return isset($_SESSION['User']->role);
	}

	public function user($key)
	{
		if ($this->read('User')) {
			if (isset($this->read('User')->$key)) {
				return $this->read('User')->$key;
			} else {
				return false;
			}
		}
		return false;
	}
}

<?php
if ($this->request->prefix == 'admin') {
	$this->layout = 'admin';
	if (!$this->Session->isLogged() || $this->Session->user('role') != 'admin') {
		$this->redirect('users/login');
	}
}

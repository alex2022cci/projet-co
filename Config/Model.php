<?php
class Model
{

	static $connections = array();

	public $conf = 'default';
	public $table = false;
	public $db;
	public $primaryKey = 'id';
	public $id;
	public $errors = array();
	public $form;
	public $validate = array();

	/**
	 * Permet d'initialiser les variables du Model
	 **/
	public function __construct()
	{
		// Nom de la table
		if ($this->table === false) {
			// $this->table = strtolower(get_class($this)) . 's';

			// Regex permettant de synchroniser le nom de la classe avec celui de la table
			$className = get_class($this);
			// echo $className;
			// echo '<br>';
			$this->table = preg_replace('/([a-z])([A-Z])/', '$1_$2', $className) . 's';
			// echo $this->table;
			// echo '<br>';
			$this->table = strtolower($this->table);
			// echo $this->table;
			// echo '<br>';

			// nota :
			// preg_replace est une fonction qui permet de remplacer des parties d'une chaîne de caractères en utilisant une expression régulière.
			// $1_$2 est la chaîne de remplacement. $1 représente le contenu du premier groupe de l'expression régulière (une minuscule), tandis que $2 représente le contenu du deuxième groupe (une majuscule).
			// La chaîne de remplacement est donc une minuscule suivie d'un tiret bas suivie d'une majuscule.
			// $className est la chaîne d'entrée sur laquelle sera effectué le remplacement.
		}

		// Connection à la base ou récupération de la précédente connection
		$conf = Conf::$databases[$this->conf];
		if (isset(Model::$connections[$this->conf])) {
			$this->db = Model::$connections[$this->conf];
			return true;
		}
		try {
			$pdo = new PDO(
				'mysql:host=' . $conf['host'] . ';dbname=' . $conf['database'] . ';',
				$conf['login'],
				$conf['password'],
				array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
			);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

			Model::$connections[$this->conf] = $pdo;
			$this->db = $pdo;
		} catch (PDOException $e) {
			if (Conf::$debug >= 1) {
				die($e->getMessage());
			} else {
				die('Impossible de se connecter à la base de donnée');
			}
		}
	}

	/**
	 * Permet de valider des données
	 * @param $data données à valider 
	 **/
	function validates($data)
	{
		$errors = array();
		foreach ($this->validate as $k => $v) {
			if (!isset($data->$k)) {
				$errors[$k] = $v['message'];
			} else {
				if ($v['rule'] == 'notEmpty') {
					if (empty($data->$k)) {
						$errors[$k] = $v['message'];
					}
				} elseif (!preg_match('/^' . $v['rule'] . '$/', $data->$k)) {
					$errors[$k] = $v['message'];
				}
			}
		}
		$this->errors = $errors;
		if (isset($this->Form)) {
			$this->Form->errors = $errors;
		}
		if (empty($errors)) {
			return true;
		}
		return false;
	}



	/**
	 * Permet de récupérer plusieurs enregistrements
	 * @param $req Tableau contenant les éléments de la requête
	 **/
	public function find($req = array())
	{
		$sql = 'SELECT ';

		if (isset($req['fields'])) {
			if (is_array($req['fields'])) {
				$sql .= implode(', ', $$req['fields']);
			} else {
				$sql .= $req['fields'];
			}
		} else {
			$sql .= '*';
		}

		$sql .= ' FROM ' . $this->table . ' as ' . get_class($this) . ' ';

		// Liaison
		if (isset($req['join'])) {
			foreach ($req['join'] as $k => $v) {
				$sql .= 'LEFT JOIN ' . $k . ' ON ' . $v . ' ';
			}
		}

		// Construction de la condition
		if (isset($req['conditions'])) {
			$sql .= 'WHERE ';
			if (!is_array($req['conditions'])) {
				$sql .= $req['conditions'];
			} else {
				$cond = array();
				foreach ($req['conditions'] as $k => $v) {
					if (!is_numeric($v)) {
						// $v = '"'.mysql_escape_string($v).'"';
						$v = $this->db->quote($v);
					}

					$cond[] = "$k=$v";
				}
				$sql .= implode(' AND ', $cond);
			}
		}

		if (isset($req['order'])) {
			$sql .= ' ORDER BY ' . $req['order'];
		}


		if (isset($req['limit'])) {
			$sql .= ' LIMIT ' . $req['limit'];
		}

		$pre = $this->db->prepare($sql);
		$pre->execute();
		return $pre->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Alias permettant de retrouver le premier enregistrement
	 **/
	public function findFirst($req)
	{
		return current($this->find($req));
	}

	/**
	 * Récupère le nombre d'enregistrement
	 **/
	public function findCount($conditions)
	{
		$res = $this->findFirst(array(
			'fields' => 'COUNT(' . $this->primaryKey . ') as count',
			'conditions' => $conditions
		));
		return $res->count;
	}

	/**
	 * Permet de récupérer un tableau indexé par primaryKey et avec name pour valeur
	 **/
	function findList($req = array())
	{
		if (!isset($req['fields'])) {
			$req['fields'] = $this->primaryKey . ',name';
		}
		$d = $this->find($req);
		$r = array();
		foreach ($d as $k => $v) {
			$r[current($v)] = next($v);
		}
		return $r;
	}

	/**
	 * Permet de supprimer un enregistrement
	 * @param $id ID de l'enregistrement à supprimer
	 **/
	public function delete($id)
	{
		$sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id";
		$this->db->query($sql);
	}


	/**
	 * Permet de sauvegarder des données
	 * @param $data Données à enregistrer
	 **/
	public function save($data)
	{
		$key = $this->primaryKey;
		$fields =  array();
		$d = array();
		foreach ($data as $k => $v) {
			if ($k != $this->primaryKey) {
				$fields[] = "$k=:$k";
				$d[":$k"] = $v;
			} elseif (!empty($v)) {
				$d[":$k"] = $v;
			}
		}
		if (isset($data->$key) && !empty($data->$key)) {
			$sql = 'UPDATE ' . $this->table . ' SET ' . implode(',', $fields) . ' WHERE ' . $key . '=:' . $key;
			$this->id = $data->$key;
			$action = 'update';
		} else {
			$sql = 'INSERT INTO ' . $this->table . ' SET ' . implode(',', $fields);
			$action = 'insert';
		}
		$pre = $this->db->prepare($sql);
		$pre->execute($d);
		if ($action == 'insert') {
			$this->id = $this->db->lastInsertId();
		}
	}
}

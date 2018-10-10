<?php
/**
 * DB class
*/
class myPdo {
	const HOST = 'localhost';
	const USER = 'user';
	const BASE = 'test';
	const PASSWORD = '123';
	
	private $pdo;
	
	function __construct() {
		$dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::BASE . ';charset=utf8';

		$opt = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		];
		$this->pdo = new PDO($dsn, self::USER, self::PASSWORD, $opt);
	}
	
	function search($tbl, $pole, $search) {
		$where = [];
		$vals = [];
		if (!is_array($search))
			$search = ['id' => $search];
		foreach ($search as $key => $val) {
			$where[] = "$key = ?";
			$vals[] = $val;
		}
		return $this->query("SELECT $pole FROM $tbl WHERE " . implode(' and ', $where) . ' LIMIT 1', $vals)->fetchColumn();
	}


	function insert($tbl, $arr) {
		$this->query('INSERT INTO ' . $tbl . ' (' . implode(',', array_keys($arr)) . ') values (' . implode(',', array_fill(0, count($arr), '?')) . ')', array_values($arr));
		return $this->pdo->lastInsertId();
	}


	function query($query, $vars = []) {
		try {
			$q = $this->pdo->prepare($query);
		} catch (PDOException $e) {
			file_put_contents('error.log', date('Y-m-d H:i:s ') . $query . "\n". print_r($vars, true) . "\n", FILE_APPEND);
			die();
		}

		try {
			$q->execute($vars);
		} catch (PDOException $e) {
			file_put_contents('error.log', date('Y-m-d H:i:s ') . $query . "\n". print_r($vars, true) . "\n" . $e->getMessage() . "\n", FILE_APPEND);
		}
		return $q;
	}
}

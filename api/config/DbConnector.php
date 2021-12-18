<?php

class DbConector {
	private $connection = null;

	public function __construct() {
		$host = '';
		$port = '';
		$db   = '';
		$user = '';
		$password = '';


		try {
			$this->connection = new PDO(
				"mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
				$user,
				$password
			);
		} catch (\PDOException $e) {
			exit($e->getMessage());
		}
	}

	public function getConnection() {
		return $this->connection;
	}
}

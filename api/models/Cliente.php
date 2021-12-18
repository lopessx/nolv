<?php

include_once __DIR__ . '/../config/DbConnector.php';
include_once __DIR__ . '/Model.php';

class Cliente extends Model {
	public static function get($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('SELECT * FROM tbl_clientes WHERE id = ?');
			$stmt->execute([$params['id']]);
			$result = $stmt->fetch();

			return $result;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function getClientByEmail($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('SELECT * FROM tbl_clientes WHERE email = ?');
			$stmt->execute([$params['email']]);
			$result = $stmt->fetch();

			return $result;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function insert($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('INSERT INTO tbl_clientes (nome,email,telefone) VALUES (?,?,?)');
			$stmt->execute([$params['nome'], $params['email'], $params['telefone']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function update($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('UPDATE tbl_clientes SET nome=?, email=?, telefone=? WHERE id = ?');
			$stmt->execute([$params['nome'], $params['email'], $params['telefone'], $params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function delete($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('DELETE FROM tbl_clientes WHERE id = ?');
			$stmt->execute([$params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}

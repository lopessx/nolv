<?php

include_once __DIR__ . '/../config/DbConnector.php';
include_once __DIR__ . '/Model.php';

class Vendedor extends Model {
	public static function get($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('SELECT * FROM tbl_vendedores WHERE id = ?');
			$stmt->execute([$params['id']]);
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
			$stmt = $connection->prepare('INSERT INTO tbl_vendedores (nome,tbl_clientes_id) VALUES (?,?)');
			$stmt->execute([$params['nome'], $params['clienteId']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function update($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('UPDATE tbl_vendedores SET nome=?,tbl_clientes_id=? WHERE id = ?');
			$stmt->execute([$params['nome'], $params['clienteId'], $params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function delete($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('DELETE FROM tbl_vendedores WHERE id = ?');
			$stmt->execute([$params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}

<?php

include_once __DIR__ . '/../config/DbConnector.php';
include_once __DIR__ . '/Model.php';

class Compra extends Model {
	public static function get($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('SELECT * FROM tbl_compras WHERE id = ?');
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
			$stmt = $connection->prepare('INSERT INTO tbl_compras (preco_total,tbl_forma_pagamento_id,tbl_clientes_id,data_hora) VALUES (?,?,?,?)');
			$stmt->execute([$params['precoTotal'], $params['idFormaPagamento'], $params['clienteId'], $params['dataHora']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function update($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('UPDATE tbl_compras SET preco_total=?,tbl_forma_pagamento_id=?,tbl_clientes_id=?,data_hora=? WHERE id = ?');
			$stmt->execute([$params['precoTotal'], $params['idFormaPagamento'], $params['clienteId'], $params['dataHora'], $params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function delete($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('DELETE FROM tbl_compras WHERE id = ?');
			$stmt->execute([$params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}

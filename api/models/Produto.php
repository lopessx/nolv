<?php

include_once __DIR__ . '/../config/DbConnector.php';
include_once __DIR__ . '/Model.php';

class Produto extends Model {
	public static function get($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('SELECT * FROM tbl_produtos WHERE id = ?');
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
			$stmt = $connection->prepare('INSERT INTO tbl_produtos (tbl_vendedores_id,nome,preco,versao,tamanho,url_download) VALUES (?,?,?,?,?,?)');
			$stmt->execute([$params['vendedor_id'], $params['nome'], $params['preco'], $params['versao'], $params['tamanho'], $params['urlDownload']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function update($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('UPDATE tbl_produtos SET tbl_vendedores_id=?,nome=?,preco=?,versao=?,tamanho=?,url_download=? WHERE id = ?');
			$stmt->execute([$params['vendedor_id'], $params['nome'], $params['preco'], $params['versao'], $params['tamanho'], $params['urlDownload'], $params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function delete($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('DELETE FROM tbl_produtos WHERE id = ?');
			$stmt->execute([$params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}

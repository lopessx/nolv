<?php

include_once __DIR__ . '/../config/DbConnector.php';
include_once __DIR__ . '/Model.php';

class Avaliacao extends Model {
	public static function get($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('SELECT * FROM tbl_avaliacoes WHERE id = ?');
			$stmt->execute([$params['id']]);
			$result = $stmt->fetch();

			return $result;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Get the comments for specific product
	 *
	 * @param array $params
	 *
	 * @return boolean|array
	 */
	public static function getAvaliacaoByProductId($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('SELECT * FROM tbl_avaliacoes WHERE tbl_produtos_id = ?');
			$stmt->execute([$params['produtoId']]);
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
			$stmt = $connection->prepare('INSERT INTO tbl_avaliacoes (tbl_produtos_id,comentario,avaliacao) VALUES (?,?,?)');
			$stmt->execute([$params['produtoId'], $params['comentario'], $params['avaliacao']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function update($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('UPDATE tbl_avaliacoes SET tbl_produtos_id=?,comentario=?,avaliacao=? WHERE id = ?');
			$stmt->execute([$params['produto_id'], $params['comentario'], $params['avaliacao'], $params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public static function delete($params) {
		$connection = new DbConector();
		$connection = $connection->getConnection();

		try {
			$stmt = $connection->prepare('DELETE FROM tbl_avaliacoes WHERE id = ?');
			$stmt->execute([$params['id']]);

			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}

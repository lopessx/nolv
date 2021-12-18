<?php

include_once __DIR__ . '/../models/Avaliacao.php';
include_once __DIR__ . '/Controller.php';

class AvaliacaoController extends Controller {
	public function processGetRequest($params) {
		$action = $params['action'];
		switch ($action) {
			case 'getComments':
				$result = $this->getCommentsForProduct($params['produtoId']);

				return json_encode($result);

				break;
			default:
				return json_encode(['result' => false, 'message' => 'invalid request']);

				break;
		}
	}

	public function processPostRequest($params) {
		$action = $params['action'];
		switch ($action) {
			default:
				return json_encode(['result' => false, 'message' => 'invalid request']);

				break;
		}
	}

	public function processPutRequest($params) {
		$action = $params['action'];
		switch ($action) {
			default:
				return json_encode(['result' => false, 'message' => 'invalid request']);

				break;
		}
	}

	public function processDeleteRequest($params) {
		$action = $params['action'];
		switch ($action) {
			default:
				return json_encode(['result' => false, 'message' => 'invalid request']);

				break;
		}
	}

	private function getCommentsForProduct($produtoId) {
		// TODO necessário pensar em como formatar múltiplas linhas
		$params = [
			'produtoId' => $produtoId,
		];

		$result = Avaliacao::getAvaliacaoByProductId($params);

		if (isset($result['id'])) {
			$params = [
				'id' => $result['id'],
				'produtoId' => $result['tbl_produtos_id'],
				'comentario' => $result['comentario'],
				'avaliacao' => $result['avaliacao'],
			];

			return ['result' => true, 'comentarios' => $params];
		} else {
			return ['result' => false];
		}
	}
}

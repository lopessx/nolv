<?php

include_once __DIR__ . '/../models/Vendedor.php';
include_once __DIR__ . '/Controller.php';

class VendedorController extends Controller {
	public function processGetRequest($params) {
		$action = $params['action'];
		switch ($action) {
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
}

<?php

include_once __DIR__ . '/../models/Cliente.php';
include_once __DIR__ . '/Controller.php';

class ClienteController extends Controller {
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
			case 'getDetails':
				$result = $this->searchClientByEmail($params['email']);

				return json_encode($result);

				break;
			case 'register':
				$result = $this->registerClient($params['nome'], $params['email'], $params['telefone']);

				return json_encode($result);

				break;
			default:
				return json_encode(['result' => false, 'message' => 'invalid request']);

				break;
		}
	}

	public function processPutRequest($params) {
		$action = $params['action'];
		switch ($action) {
			case 'updateDetails':
				$result = $this->updateClient($params);

				return json_encode($result);

				break;
			default:
				return json_encode(['result' => false, 'message' => 'invalid request']);

				break;
		}
	}

	public function processDeleteRequest($params) {
		$action = $params['action'];
		switch ($action) {
			case 'delete':
				$result = $this->deleteClient($params['id']);

				return json_encode($result);

				break;
			default:
				return json_encode(['result' => false, 'message' => 'invalid request']);

				break;
		}
	}

	private function registerClient($nome, $email, $telefone) {
		$params = [
			'nome' => $nome,
			'email' => $email,
			'telefone' => $telefone,
		];

		$result = Cliente::insert($params);

		return ['result' => $result];
	}

	private function searchClientByEmail($email) {
		$params = [
			'email' => $email,
		];

		$result = Cliente::getClientByEmail($params);

		if (isset($result['id'])) {
			$params = [
				'id' => $result['id'],
				'nome' => $result['nome'],
				'email' => $result['email'],
				'telefone' => $result['telefone'],
			];

			return ['result' => true, 'cliente' => $params];
		} else {
			return ['result' => false];
		}
	}

	private function searchClientById($id) {
		$params = [
			'id' => $id,
		];

		$result = Cliente::get($params);


		$params = [
			'id' => $id,
			'nome' => $result['nome'],
			'email' => $result['email'],
			'telefone' => $result['telefone'],
		];

		return $params;
	}

	private function updateClient($request) {
		$id = $request['id'];

		$client = $this->searchClientById($id);
		$nome = $client['nome'];
		$email = $client['email'];
		$telefone = $client['telefone'];

		if (isset($request['nome'])) {
			$nome = $request['nome'];
		}

		if (isset($request['email'])) {
			$email = $request['email'];
		}

		if (isset($request['telefone'])) {
			$telefone = $request['telefone'];
		}

		$params = [
			'id' => $id,
			'nome' => $nome,
			'email' => $email,
			'telefone' => $telefone,
		];

		$result = Cliente::update($params);

		return ['result' => $result];
	}

	private function deleteClient($id) {
		$params = [
			'id' => $id,
		];

		$result = Cliente::delete($params);

		return ['result' => $result];
	}
}

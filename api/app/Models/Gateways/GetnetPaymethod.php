<?php

namespace App\Models\Gateways;

class GetnetPaymethod {
	public static function payBoleto($bolData, $amount) {
		$postUrl = 'https://api-sandbox.getnet.com.br/';
		$getnetId = config('auth.getnetId');
		$getnetSecret = config('auth.getnetSecret');
		$authString = base64_encode($getnetId . ':' . $getnetSecret);
		$amount = number_format($amount, 2, '', '');
		$name = strip_tags($bolData['name']);
		$documentId = preg_replace('/\D/', '', $bolData['cpf']);
		$zipcode = preg_replace('/\D/', '', $bolData['slip']['zipCode']);
		$street = strip_tags($bolData['slip']['street']);
		$district = strip_tags($bolData['slip']['district']);
		$state = strip_tags($bolData['slip']['state']);
		$complement = strip_tags($bolData['slip']['complement']);
		$number = strip_tags($bolData['slip']['number']);
		$city = strip_tags($bolData['slip']['city']);

		$header = [
			'authorization: Basic ' . $authString,
			'Content-type: application/x-www-form-urlencoded',
		];

		$postfields = [];
		$postfields['scope'] = 'oob';
		$postfields['grant_type'] = 'client_credentials';

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $postUrl . 'auth/oauth/v2/token',
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_POSTFIELDS => http_build_query($postfields),
			CURLOPT_RETURNTRANSFER => true,
		]);

		$response = json_decode(curl_exec($ch));
		$info = curl_getinfo($ch);

		curl_close($ch);

		$authToken = $response->access_token;

		$headerPost = [
			'accept: application/json, text/plain, */*',
			'authorization: Bearer ' . $authToken,
			'content-type: application/json; charset=utf-8"',
		];

		$bodyPost = [
			'seller_id' => '46b445bc-9111-43ff-9cd5-9202db4cf93f',
			'amount' => $amount,
			'order' => [
				'order_id' => uniqid(),
			],
			'boleto' => [
				'expiration_date' => date('d/m/Y', strtotime(' + 5 days')),
				'instructions' => 'Não receber após o vencimento',
			],
			'customer' => [
				'name' => $name,
				'document_type' => 'CPF',
				'document_number' => $documentId,
				'billing_address' => [
					'street' => $street,
					'number' => $number,
					'complement' => $complement,
					'district' => $district,
					'city' => $city,
					'state' => $state,
					'postal_code' => $zipcode,
				],
			],
		];

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $postUrl . 'v1/payments/boleto',
			CURLOPT_HTTPHEADER => $headerPost,
			CURLOPT_POSTFIELDS => json_encode($bodyPost),
			CURLOPT_RETURNTRANSFER => true,
		]);

		$response = curl_exec($ch);
		$info = curl_getinfo($ch);

		curl_close($ch);

		return ['result' => json_encode($response), 'info' => $info, 'auth' => $authToken];
	}
}

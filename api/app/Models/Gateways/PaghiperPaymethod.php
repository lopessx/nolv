<?php

namespace App\Models\Gateways;

class PaghiperPaymethod {
	// TODO add listener URL

	public static function payBoleto($bolData, $amount) {
		$urlBol= 'https://api.paghiper.com/';

		$apiKey = trim(config('auth.paghiperKey'));
		$listenerUrl = '';
		$amount = number_format($amount, 2, '', '');
		$name = strip_tags($bolData['name']);
		$documentId = preg_replace('/\D/', '', $bolData['cpf']);

		$header = [
			'Content-Type: application/json',
			'Accept: application/json',
		];

		$body = [
			'apiKey' => $apiKey,
			'order_id' => 'bol_' . uniqid(),
			'payer_email' => $bolData['email'],
			'payer_name' => $name,
			'payer_cpf_cnpj' => $documentId,
			'days_due_date' => 5,
			'type_bank_slip' => 'boletoA4',
			'notification_url' => $listenerUrl,
			'items' => [
				[
					'description' => 'Nolv compra',
					'quantity' => '1',
					'item_id' => '1',
					'price_cents' => $amount,
				],
			],
		];

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $urlBol . 'transaction/create/',
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => json_encode($body),
			CURLOPT_RETURNTRANSFER => true,
		]);

		$result = json_decode(curl_exec($ch));
		$info = curl_getinfo($ch);

		if (isset($result->create_request->bank_slip->url_slip)) {
			return ['result' => true, 'url' => $result->create_request->bank_slip->url_slip];
		} else {
			return false;
		}
	}

	public static function payPix($pixData, $amount) {
		$urlPix = 'https://pix.paghiper.com/';

		$apiKey = config('paghiperKey');
		$listenerUrl = '';
		$amount = number_format($amount, 2, '', '');

		$header = [
			'Content-Type: application/json',
			'Accept: application/json',
		];
		$body = [
			'apiKey' => $apiKey,
			'order_id' => 'pix_' . uniqid(),
			'payer_email' => $pixData['email'],
			'payer_name' => $pixData['name'],
			'payer_cpf_cnpj' => $pixData['cpf'],
			'days_due_date' => 5,
			'notification_url' => $listenerUrl,
			'items' => [[
				'item_id' => '01',
				'description' => 'Nolv Compra',
				'quantity' => '1',
				'price_cents' => $amount,
			]],
		];

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => 'POST',

			CURLOPT_URL => $urlPix . 'invoice/create/',

			CURLOPT_HTTPHEADER => $header,

			CURLOPT_POSTFIELDS => json_encode($body),

			CURLOPT_RETURNTRANSFER => true,
		]);

		$result = json_decode(curl_exec($ch));
		$info = curl_getinfo($ch);

		return true;
	}
}

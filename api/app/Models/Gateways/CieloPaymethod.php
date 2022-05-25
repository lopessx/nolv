<?php

namespace App\Models\Gateways;

class CieloPaymethod {
	public static function payCreditCard($cardData, $amount) {
		$queryUrl = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/';
		$postUrl = 'https://apisandbox.cieloecommerce.cielo.com.br/';
		$cardNum = preg_replace('/\D/', '', $cardData['number']);
		$splitExp = explode('/', $cardData['expDate']);
		$expDate = $splitExp[0] . '/' . '20' . $splitExp[1];
		$cvv = preg_replace('/\D/', '', $cardData['cvv']);
		$name = strip_tags($cardData['name']);

		$merchantId = config('auth.merchantId');
		$merchantSecret = config('auth.merchantSecret');
		$orderId = uniqid('nlv_');
		$bin = substr($cardNum, 0, 6);
		$amount = number_format($amount, 2, '', '');

		$header = [
			'Content-type: application/json',
			'MerchantId: ' . $merchantId,
			'MerchantKey: ' . $merchantSecret,
		];

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_URL => $queryUrl . '1/cardBin/' . $bin,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_RETURNTRANSFER => true,
		]);

		$response = json_decode(curl_exec($ch));
		$info = curl_getinfo($ch);

		curl_close($ch);

		$provider = $response->Provider;

		if ($provider === 'MASTERCARD') {
			$provider = 'MASTER';
		}

		$body = [
			'MerchantOrderId' => $orderId,
			'Payment' => [
				'Type' => 'CreditCard',
				'Amount' => $amount,
				'Installments' => 1,
				'SoftDescriptor' => 'Nolv_Sale',
				'CreditCard' => [
					'CardNumber' => $cardNum,
					'Holder' => $name,
					'ExpirationDate' => $expDate,
					'SecurityCode' => $cvv,
					'Brand' => $provider,
				],
			],
		];

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => 'POST',

			CURLOPT_URL => $postUrl . '1/sales/',

			CURLOPT_HTTPHEADER => $header,

			CURLOPT_POSTFIELDS => json_encode($body),

			CURLOPT_RETURNTRANSFER => true,
		]);

		$result = json_decode(curl_exec($ch));
		$info = curl_getinfo($ch);

		curl_close($ch);

		if (isset($result->Payment->Status)) {
			return $result->Payment->Status;
		} else {
			return $result->message;
		}
	}
}

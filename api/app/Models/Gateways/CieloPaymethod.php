<?php

namespace App\Models\Gateways;

class CieloPaymethod {
	public static function payCreditCard($cardData, $amount) {
		$queryUrl = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/';
		$postUrl = 'https://apisandbox.cieloecommerce.cielo.com.br/';

		$merchantId = config('MERCHANT_ID');
		$merchantSecret = config('MERCHANT_SECRET');
		$orderId = uniqid('nlv_');
		$bin = substr($cardData->number, 0, 6);
		$amount = number_format($amount, 2, '', '');

		$header = [
			'Content-type: application/json',
			'MerchantId: ' . $merchantId,
			'MerchantKey: ' . $merchantSecret,
		];

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_URL => $queryUrl . '1/card/' . $bin,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_RETURNTRANSFER => true,
		]);

		$response = json_decode(curl_exec($ch));
		$info = curl_getinfo($ch);

		curl_close($ch);

		$provider = $response->Provider;

		$body = [
			'MerchantOrderId' => $orderId,
			'Payment' => [
				'Type' => 'CreditCard',
				'Amount' => $amount,
				'Installments' => 1,
				'SoftDescriptor' => 'Nolv_Sale',
				'CreditCard' => [
					'CardNumber' => $cardData->number,
					'Holder' => $cardData->name,
					'ExpirationDate' => $cardData->expDate,
					'SecurityCode' => $cardData->cvv,
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

		if (isset($result->Payment) && ($result->Payment->Status === 1 || $result->Payment->Status === 2)) {
			return true;
		} else {
			return false;
		}
	}
}

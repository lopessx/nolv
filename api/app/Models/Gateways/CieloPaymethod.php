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
		/*
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

				if (isset($response->Provider)) {
					$provider = $response->Provider;
				} else {
					// TODO fallback with regex?
					CieloPaymethod::getProviderRegex($bin);
				}

				if ($provider === 'MASTERCARD') {
					$provider = 'MASTER';
				} */

		$provider = CieloPaymethod::getProviderRegex($cardNum);

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

	public static function getProviderRegex($cardNum) {
		// Stores regex for Card Bin Tests
		$bin = [
			// elo
			'/(4011|431274|438935|451416|457393|4576|457631|457632|504175|627780|636297|636368|636369|(6503[1-3])|(6500(3[5-9]|4[0-9]|5[0-1]))|(6504(0[5-9]|1[0-9]|2[0-9]|3[0-9]))|(650(48[5-9]|49[0-9]|50[0-9]|51[1-9]|52[0-9]|53[0-7]))|(6505(4[0-9]|5[0-9]|6[0-9]|7[0-9]|8[0-9]|9[0-8]))|(6507(0[0-9]|1[0-8]))|(6507(2[0-7]))|(650(90[1-9]|91[0-9]|920))|(6516(5[2-9]|6[0-9]|7[0-9]))|(6550(0[0-9]|1[1-9]))|(6550(2[1-9]|3[0-9]|4[0-9]|5[0-8]))|(506(699|77[0-8]|7[1-6][0-9))|(509([0-9][0-9][0-9])))/',
			// hipercard
			'/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
			// diners
			'/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
			// discover
			'/^6(?:011|5[0-9]{2})[0-9]{12}$/',
			// jcb
			'/^(?:2131|1800|35\d{3})\d{11}$/',
			// aura
			'/^50[0-9]{14,17}$/',
			// amex
			'/^3[47][0-9]{13}$/',
			// mastercard
			'/^5[1-5]\d{14}$|^2(?:2(?:2[1-9]|[3-9]\d)|[3-6]\d\d|7(?:[01]\d|20))\d{12}$/',
			// visa
			'/^4[0-9]{12}(?:[0-9]{3})?$/',
		];

		// Test the cardNumber bin
		for ($c = 0; $c < count($bin); $c++) {
			if ($c > 10) {
				return false;
			}
			if (preg_match($bin[$c], $cardNum) == 1) {
				switch ($c) {
			case 0:
				return 'Elo';

				break;
			case 1:
				return 'Hipercard';

				break;
			case 2:
				return 'Diners';

				break;
			case 3:
				return 'Discover';

				break;
			case 4:
				return 'JCB';

				break;
			case 5:
				return 'Aura';

				break;
			case 6:
				return 'Amex';

				break;
			case 7:
				return 'Master';

				break;
			case 8:
				return 'Visa';

				break;
		}
			}
		}
	}
}

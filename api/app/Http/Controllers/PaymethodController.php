<?php

namespace App\Http\Controllers;

use App\Models\Gateways\CieloPaymethod;
use App\Models\Gateways\PaghiperPaymethod;
use App\Models\Paymethod;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymethodController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	public function get(Request $request) {
		try {
			$paymethods = Paymethod::where('active', 1)->get();

			return response(['success' => true, 'paymethods' => $paymethods]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getOne(Request $request) {
		try {
			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function store(Request $request) {
		try {
			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function update(Request $request) {
		try {
			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function delete(Request $request) {
		try {
			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}


	public function capturePayment(Request $request, $orderId) {
		DB::beginTransaction();

		try {
			$order = Order::find($orderId);
			$paymethod = Paymethod::find($order->paymethod_id);
			$result = null;

			switch ($paymethod->type) {
				case 'card':
					$result = CieloPaymethod::payCreditCard($request->paymentData['card'], $request->amount);

					break;
				case 'pix':
					$result = PaghiperPaymethod::payPix($request->paymentData, $request->amount);

					break;
				case 'boleto':
					$result = PaghiperPaymethod::payBoleto($request->paymentData, $request->amount);

					break;
				default:
					throw new Exception('Paymethod not supported');

					break;
			}

			DB::commit();

			return response(['success' => true, 'result' => $result]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode(), 'trace' => $e->getTrace()], 404);
		}
	}
}

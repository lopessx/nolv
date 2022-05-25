<?php

namespace App\Http\Controllers;

use App\Models\Gateways\CieloPaymethod;
use App\Models\Gateways\GetnetPaymethod;
use App\Models\Gateways\PaghiperPaymethod;
use App\Models\Paymethod;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Store;
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
					$result = CieloPaymethod::payCreditCard($request->paymentData['card'], $order->total);

					if ($result === 1 || $result === 2) {
						$order->status_id = 4;
						$productsSold = ProductOrder::where('order_id', $order->id)->get(['product_id']);
						foreach ($productsSold as $key => $product) {
							$tempProduct = Product::where('id', $product->product_id)->first(['store_id', 'price']);
							$store = Store::find($tempProduct->store_id)->first();
							$store->balance += $tempProduct->price;
							$store->save();
						}
					} else {
						$order->status_id = 2;
					}

					$order->save();

					break;
				case 'pix':
					$result = PaghiperPaymethod::payPix($request->paymentData, $order->total);

					break;
				case 'boleto':
					$result = PaghiperPaymethod::payBoleto($request->paymentData, $order->total);
					// $result = GetnetPaymethod::payBoleto($request->paymentData, $order->total);

					if (!isset($result['url']) || empty($result['url'])) {
						$order->status_id = 2;
					}

					$order->save();

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

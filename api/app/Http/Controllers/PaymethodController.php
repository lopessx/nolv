<?php

namespace App\Http\Controllers;

use App\Models\Paymethod;
use App\Models\ProductOrder;
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

	public function proccessPayment(Request $request) {
		DB::beginTransaction();

		try {
			$products = $request->products;
			$productsSale = [];

			$sale = new Order();
			$sale->total = $request->total;
			$sale->paymethod_id = $request->paymethodId;
			$sale->client_id = $request->clientId;
			$sale->save();

			foreach ($products as $key => $product) {
				$order = new ProductOrder();
				$order->product_id = $product->id;
				$order->sales_id = $sale->id;
				$order->save();

				$productsSale[] = $order;
			}

			DB::commit();

			return response(['success' => true, 'sales' => $sale, 'order' => $sale]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

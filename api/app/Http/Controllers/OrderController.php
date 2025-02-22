<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
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
			$query = Order::query();

			if ($request->exists('client') && !empty($request->client)) {
				$query->where('client_id', $request->client);
			}

			if ($request->exists('paymethod') && !empty($request->paymethod)) {
				$query->where('paymethod_id', $request->paymethod);
			}

			if ($request->exists('status') && !empty($request->status)) {
				$query->where('status_id', $request->status);
			}

			$pagination = $query->paginate(5);

			return response(['success' => true, 'pagination' => $pagination]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getOne(Request $request, $id) {
		try {
			$order = Order::findOrFail($id);

			return response(['success' => true, 'order' => $order]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function store(Request $request) {
		DB::beginTransaction();

		try {
			$products = $request->products;
			$productsSale = [];

			$order = new Order();
			$order->status_id = 1;
			$order->total = $request->total;
			$order->paymethod_id = $request->paymethodId;
			$order->client_id = $request->clientId;
			$order->save();

			foreach ($products as $key => $product) {
				$productOrder = new ProductOrder();
				$productOrder->product_id = $product['id'];
				$productOrder->order_id = $order->id;
				$productOrder->save();

				$productsSale[] = $productOrder;
			}

			DB::commit();

			return response(['success' => true, 'order' => $order]);
		} catch (Exception $e) {
			DB::rollBack();

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

	public function getOrdersClient(Request $request, $id) {
		try {
			$orders = Order::where('client_id', $id)->get();

			return response(['success' => true, 'orders' => $orders]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getProductsClient(Request $request, $id) {
		try {
			$orders = Order::where('client_id', $id)->get(['id', 'status_id']);
			$ordersArr = [];

			foreach ($orders as $key => $order) {
				if ($order->status_id == 4) {
					$ordersArr[] = $order->id;
				}
			}

			$productOrders = ProductOrder::whereIn('order_id', $ordersArr)->get(['product_id']);
			$productOrdersArr = [];

			foreach ($productOrders as $key => $productOrder) {
				$productOrdersArr[] = $productOrder->product_id;
			}

			$productOrdersArr = array_unique($productOrdersArr);

			$products = Product::whereIn('id', $productOrdersArr)->get();

			return response(['success' => true, 'products' => $products]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller {
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
			$query = Store::query();

			if ($request->exists('name') && !empty($request->name)) {
				$query->where('name', 'like', '%' . $request->name . '%');
			}

			if ($request->exists('order') && !empty($request->order)) {
				$query->orderBy(
					$request->get('sortBy', 'name'),
					$request->get('sortOrder', ($request->order == 2) ? 'desc' : 'asc')
				);
			}

			$pagination = $query->paginate(5);

			return response(['success' => true, 'pagination' => $pagination]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getOne(Request $request, $storeId) {
		try {
			$store = Store::where('id', $storeId)->first();

			return response(['success' => true, 'store' => $store]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function store(Request $request) {
		DB::beginTransaction();

		try {
			$store = new Store();
			$store->client_id = $request->clientId;
			$store->balance = 0;
			$store->name = $request->name;

			$store->save();

			DB::commit();

			return response(['success' => true, 'store' => $store]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function update(Request $request, $storeId) {
		DB::beginTransaction();

		try {
			$store = Store::where('id', $storeId)->first();

			if ($request->exists('clientId')) {
				$store->client_id = $request->clientId;
			}

			if ($request->exists('balance')) {
				$store->balance = $request->balance;
			}

			if ($request->exists('name')) {
				$store->name = $request->name;
			}

			if ($request->exists('imgPath')) {
				$store->img_path = $request->imgPath;
			}

			$store->save();

			DB::commit();

			return response(['success' => true, 'store' => $store]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function delete(Request $request, $storeId) {
		DB::beginTransaction();

		try {
			$store = Store::where('id', $storeId)->first();

			$store->delete();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getClientStore($clientId) {
		try {
			$store = Store::where('client_id', $clientId)->first();

			if ($store) {
				$products = Product::where('store_id', $store->id)->get();

				return response(['success' => true, 'store' => $store, 'products' => $products]);
			} else {
				return response(['success' => false]);
			}
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

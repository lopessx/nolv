<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Exception;
use Illuminate\Http\Request;

class ProductsController extends Controller {
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
			$products = Products::all();

			return response(['success' => true, 'products' => $products]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getOne(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$product = Products::findOrFail($id);


			return response(['success' => true, 'product' => $product]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function store(Request $request) {
		try {
			$product = new Products();
			$product->store_id = filter_var($request->storeId, FILTER_SANITIZE_NUMBER_INT);
			$product->category_id = filter_var($request->categoryId, FILTER_SANITIZE_NUMBER_INT);
			$product->name = filter_var($request->name, FILTER_SANITIZE_STRING);
			$product->price = filter_var($request->price, FILTER_SANITIZE_NUMBER_FLOAT);
			$product->file_path = filter_var($request->path, FILTER_SANITIZE_STRING);
			$product->save();


			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function update(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$product = Products::findOrFail($id);
			$product->store_id = filter_var($request->storeId, FILTER_SANITIZE_NUMBER_INT);
			$product->category_id = filter_var($request->categoryId, FILTER_SANITIZE_NUMBER_INT);
			$product->name = filter_var($request->name, FILTER_SANITIZE_STRING);
			$product->price = filter_var($request->price, FILTER_SANITIZE_NUMBER_FLOAT);
			$product->file_path = filter_var($request->path, FILTER_SANITIZE_STRING);
			$product->save();

			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function delete(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			Products::findOrFail($id)->delete();

			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

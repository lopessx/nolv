<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
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
			$products = Product::all();

			return response(['success' => true, 'products' => $products]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getOne(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$product = Product::findOrFail($id);
			$product->category_name = $product->category->name;
			$product->language_name = $product->language->name;
			$product->store_name = $product->store->name;
			$product->os_name = $product->os->name;
			$product->images;

			return response(['success' => true, 'product' => $product]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function store(Request $request) {
		DB::beginTransaction();

		try {
			$product = new Product();
			$product->store_id = filter_var($request->storeId, FILTER_SANITIZE_NUMBER_INT);
			$product->category_id = filter_var($request->categoryId, FILTER_SANITIZE_NUMBER_INT);
			$product->operational_system_id = filter_var($request->osId, FILTER_SANITIZE_NUMBER_INT);
			$product->language_id = filter_var($request->languageId, FILTER_SANITIZE_NUMBER_INT);
			$product->name = strip_tags($request->name);
			$product->price = preg_replace('/,/', '.', $request->price);
			$product->version = $request->version;
			$product->file_path = strip_tags($request->path);
			$product->description = $request->description;

			$product->save();

			DB::commit();

			return response(['success' => true, 'product' => $product]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function storeImages(Request $request) {
		DB::beginTransaction();

		try {
			$images = $request->allFiles();
			$paths = [];
			$i = 0;
			$product = Product::where('id', $request->productId)->first();

			foreach ($images as $image) {
				$filename = preg_replace('/\s/', '-', $image->getClientOriginalName());
				Storage::disk('public')->putFileAs('/imgs/' . $request->productId . '/', $image, $filename);
				$paths[] = '/imgs/' . $request->productId . '/' . $filename;

				$productImage = new ProductImage();
				$productImage->product_id = $request->productId;
				$productImage->position = $i;
				$productImage->path = '/imgs/' . $request->productId . '/' . $filename;

				$productImage->save();

				$i++;
			}

			if (empty($product->main_image_path)) {
				$product->main_image_path = $paths[0];
				$product->save();
			}

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function uploadProduct(Request $request) {
		DB::beginTransaction();

		try {
			$files = $request->allFiles();
			$paths = [];

			foreach ($files as $file) {
				$filename = preg_replace('/\s/', '-', $file->getClientOriginalName());
				Storage::disk('private')->putFileAs('/files/' . $request->productId . '/', $file, $filename);
				$paths[] = '/files/' . $request->productId . '/' . $filename;

				$product = Product::where('id', $request->productId)->first();
				$product->file_path = '/files/' . $request->productId . '/' . $filename;

				$product->save();
			}

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function update(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$product = Product::findOrFail($id);
			$product->store_id = filter_var($request->storeId, FILTER_SANITIZE_NUMBER_INT);
			$product->category_id = filter_var($request->categoryId, FILTER_SANITIZE_NUMBER_INT);
			$product->name = strip_tags($request->name);
			$product->price = filter_var($request->price, FILTER_SANITIZE_NUMBER_FLOAT);
			$product->file_path = strip_tags($request->path);
			$product->save();

			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function delete(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			Product::findOrFail($id)->delete();

			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

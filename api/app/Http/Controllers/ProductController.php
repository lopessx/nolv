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
			$product->name = filter_var($request->name, FILTER_SANITIZE_STRING);
			$product->price = filter_var($request->price, FILTER_SANITIZE_NUMBER_FLOAT);
			$product->version = $request->version;
			$product->file_path = filter_var($request->path, FILTER_SANITIZE_STRING);
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

			foreach ($images as $image) {
				$extension = $image->getClientOriginalName();
				$filename  = 'image-' . time() . '.' . $extension;
				Storage::disk('public')->putFileAs('/imgs/', $image, $filename);
				$paths[] =   Storage::url('/imgs/' . $filename);

				$productImage = new ProductImage();
				$productImage->product_id = $request->productId;
				$productImage->position = $i;
				$productImage->path = Storage::url('/imgs/' . $filename);

				$productImage->save();

				$i++;
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
				$extension = $file->getClientOriginalName();
				$filename  = $request->productName . $extension;
				Storage::disk('public')->putFileAs('/files/', $file, $filename);
				$paths[] =   url(Storage::url('files/' . $filename));

				$product = Product::where('id', $request->id)->first();
				$product->file_path = url(Storage::url('files/' . $filename));

				$product->save();
			}

			DB::commit();

			return response(['success' => true, 'files' => $files]);
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
			Product::findOrFail($id)->delete();

			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

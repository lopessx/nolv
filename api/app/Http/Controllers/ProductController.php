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
			$query = Product::query();

			if ($request->exists('category') && !empty($request->category)) {
				$query->where('category_id', $request->category);
			}

			if ($request->exists('minPrice') && $request->minPrice > 0) {
				$query->where('price', '>', $request->minPrice);
			}

			if ($request->exists('maxPrice') && $request->maxPrice > 0) {
				$query->where('price', '<', $request->maxPrice);
			}

			if ($request->exists('search') && !empty($request->search)) {
				$query->where('name', 'like', '%' . $request->search . '%');
			}

			if ($request->exists('order') && !empty($request->order)) {
				$query->orderBy(
					$request->get('sortBy', 'price'),
					$request->get('sortOrder', ($request->order == 2) ? 'desc' : 'asc')
				);
			}

			$pagination = $query->paginate(1);

			return response(['success' => true, 'pagination' => $pagination]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getOne(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$product = Product::findOrFail($id);
			// $product->category_id = $product->category->id;
			// $product->language_id = $product->language->id;
			$product->store_name = $product->store->name;
			// $product->os_id = $product->os->id;
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
			$product = Product::where('id', $request->productId)->first();
			$files = $request->allFiles();
			$file = $files[0];
			$paths = [];

			if (!empty($product->file_path)) {
				Storage::disk('private')->delete($product->file_path);
			}


			$filename = preg_replace('/\s/', '-', $file->getClientOriginalName());
			Storage::disk('private')->putFileAs('/files/' . $request->productId . '/', $file, $filename);
			$paths[] = '/files/' . $request->productId . '/' . $filename;

			$product->file_path = $filename;


			$product->save();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function update(Request $request, $id) {
		DB::beginTransaction();

		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$product = Product::findOrFail($id);

			if ($request->exists('categoryId') == true) {
				$product->category_id = $request->categoryId;
			}

			if ($request->exists('languageId') == true) {
				$product->language_id = $request->languageId;
			}

			if ($request->exists('osId') == true) {
				$product->operational_system_id = $request->osId;
			}

			if ($request->exists('name') == true) {
				$product->name = $request->name;
			}

			if ($request->exists('description') == true) {
				$product->description = $request->description;
			}

			if ($request->exists('version') == true) {
				$product->version = $request->version;
			}

			if ($request->exists('price') == true) {
				$product->price = preg_replace('/,/', '.', $request->price);
			}

			$product->save();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function delete(Request $request, $id) {
		DB::beginTransaction();

		try {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$product = Product::findOrFail($id);
			Storage::disk('private')->deleteDirectory('/files/' . $id);
			Storage::disk('public')->deleteDirectory('/imgs/' . $id);
			$product->delete();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function deleteProductImage($imgId) {
		// TODO needs to change main_image of product if it's deleted
		DB::beginTransaction();

		try {
			$productImage = ProductImage::findOrFail($imgId);
			Storage::disk('public')->delete($productImage->path);
			$productImage->delete();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function downloadProduct(Request $request, $id) {
		try {
			$product = Product::where('id', $id)->first();
			$file = Storage::disk('private')->get('files/' . $id . '/' . $product->file_path);
			$size = Storage::disk('private')->size('files/' . $id . '/' . $product->file_path);

			return response($file, 200, ['Content-Length' => $size, 'Content-Disposition' => 'attachment', 'Content-Transfer-Encoding' => 'binary']);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

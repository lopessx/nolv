<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Exception;
use Illuminate\Http\Request;

class ProductImageController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	public function getOne(Request $request, $id) {
		try {
			$image = ProductImage::findOrFail($id);

			return response(['success' => true, 'image' => $image]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

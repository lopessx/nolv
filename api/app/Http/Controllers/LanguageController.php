<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Exception;
use Illuminate\Http\Request;

class LanguageController extends Controller {
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
			$languages = Language::all(['id as value', 'name as label']);

			return response(['success' => true, 'languages' => $languages]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

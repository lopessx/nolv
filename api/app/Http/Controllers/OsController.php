<?php

namespace App\Http\Controllers;

use App\Models\Os;
use Exception;
use Illuminate\Http\Request;

class OsController extends Controller {
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
			$os = Os::all(['id as value', 'name as label']);

			return response(['success' => true, 'operational_systems' => $os]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

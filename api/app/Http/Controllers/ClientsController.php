<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller {
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
			return response(['success' => true]);
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

	public function register(Request $request) {
		DB::beginTransaction();

		try {
			$client = new Clients();
			$client->name = $request->name;
			$client->email = $request->email;
			$client->phone = $request->phone;
			$client->save();

			DB::commit();

			return response(['success' => true, 'client' => $client], 200);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}

	public function login(Request $request) {
		try {
			$client = Clients::first(['email' => $request->email]);
			Auth::login($client);
			$client = Auth::user();

			return response(['success' => true, 'client' => $client], 200);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}
}

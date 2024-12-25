<?php

namespace App\Http\Controllers;

use App\Mail\NewLogin;
use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller {
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
			$query = Client::query();

			if ($request->exists('name') && !empty($request->name)) {
				$query->where('name', 'like', '%' . $request->name . '%');
			}

			if ($request->exists('phone') && !empty($request->phone)) {
				$query->where('phone', $request->phone);
			}

			if ($request->exists('email') && $request->email) {
				$query->where('email', $request->email);
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

	public function getOne(Request $request, $id) {
		try {
			$client = Client::findOrFail($id);

			return response(['success' => true, 'client' => $client]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function update(Request $request, $id) {
		DB::beginTransaction();

		try {
			$client = Client::findOrFail($id);

			if ($request->exists('name')) {
				$client->name = $request->name;
			}

			if ($request->exists('email')) {
				$client->email = $request->email;
			}

			if ($request->exists('phone')) {
				$client->phone = $request->phone;
			}

			$client->save();

			DB::commit();

			return response(['success' => true, 'client' => $client]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function delete(Request $request, $id) {
		DB::beginTransaction();

		try {
			$client = Client::findOrFail($id);
			$client->delete();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function register(Request $request) {
		DB::beginTransaction();

		try {
			$client = Client::where('email', $request->email)
				->first();

			if (!isset($client->email)) {
				$accessCode = random_int(100000, 999999);
				$client = new Client();
				$client->name = $request->name;
				$client->email = $request->email;
				$client->phone = $request->phone;
				$client->auth_key = Hash::make($accessCode);

				$client->save();

				DB::commit();

				Mail::to($request->email)
				->send(new NewLogin((string) $accessCode));

				return response(['success' => true], 200);
			} else {
				DB::rollBack();

				return response(['success' => false], 200);
			}
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}

	public function login(Request $request) {
		DB::beginTransaction();

		try {
			$client = Client::where('email', $request->email)->first();

			$expDate = strtotime($client->updated_at);
			$today = (time()-(60*10));

			if ($expDate > $today) {
				if (Hash::check($request->code, $client->auth_key)) {
					$client->auth_key = Hash::make(uniqid('', true));
					$client->save();

					DB::commit();

					return response(['success' => true, 'client' => $client, 'key' => base64_encode($client->auth_key)], 200);
				} else {
					return response(['success' => false], 200);
				}
			} else {
				throw new Exception('Autenthication code timeout');
			}
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}

	public function auth(Request $request) {
		DB::beginTransaction();

		try {
			$client = Client::where('email', $request->email)
				->first();

			if (empty($client)) {
				DB::rollBack();

				return response(['success' => false], 200);
			} else {
				$accessCode = random_int(100000, 999999);
				$client->auth_key = Hash::make($accessCode);

				$client->save();

				DB::commit();

				Mail::to($request->email)
				->send(new NewLogin((string) $accessCode));

				return response(['success' => true], 200);
			}
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}

	public function logout(Request $request) {
		DB::beginTransaction();

		try {
			$client = Client::where('email', $request->email)
			->first();

			if (empty($client)) {
				DB::rollBack();

				return response(['success' => false], 200);
			} else {
				$client->auth_key = Hash::make(uniqid('logout_', true));

				$client->save();

				DB::commit();

				return response(['success' => true], 200);
			}
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}
}

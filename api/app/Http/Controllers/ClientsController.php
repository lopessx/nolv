<?php

namespace App\Http\Controllers;

use App\Mail\NewLogin;
use App\Models\Clients;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
			$client = Clients::where('email', $request->email)
				->first();

			if (empty($client)) {
				$accessCode = random_int(100000, 999999);
				$expirationDate = date('Y-m-d H:i:s');
				$client = new Clients();
				$client->name = $request->name;
				$client->email = $request->email;
				$client->phone = $request->phone;
				$client->password = Hash::make($accessCode);
				$client->expiration_time = $expirationDate;

				$client->save();

				Mail::to($request->email)
					->send(new NewLogin((string) $accessCode));

				DB::commit();

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
		try {
			$client = Clients::where('email', $request->email)->first();
			/*
			$expirationDate = new DateTime($expirationDate);
			$expirationDate->add(new DateInterval('PT' . 5 . 'M'));
			*/

			if (Hash::check($request->code, $client->password)) {
				$client->password = Hash::make(uniqid('', true));
				$client->save();

				return response(['success' => true, 'client' => $client, 'key' => $client->password], 200);
			} else {
				return response(['success' => false], 200);
			}
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}

	public function auth(Request $request) {
		DB::beginTransaction();

		try {
			$client = Clients::where('email', $request->email)
				->first();

			if (empty($client)) {
				DB::rollBack();

				return response(['success' => false], 200);
			} else {
				$accessCode = random_int(100000, 999999);
				$expirationDate = date('Y-m-d H:i:s');
				$client->password = Hash::make($accessCode);
				$client->expiration_time = $expirationDate;

				$client->save();

				Mail::to($request->email)
					->send(new NewLogin((string) $accessCode));

				DB::commit();

				return response(['success' => true], 200);
			}
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}
}

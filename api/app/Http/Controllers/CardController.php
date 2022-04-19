<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Exception;
use Illuminate\Http\Request;

class CardController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	public function get(Request $request, $clid) {
		try {
			$clid = filter_var($clid, FILTER_SANITIZE_STRING);
			$cards = Card::where('client_id', $clid)->get();

			return response(['success' => true, 'cards' => $cards]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getOne(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_STRING);
			$card = Card::find($id);

			return response(['success' => true, 'card' => $card]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function store(Request $request) {
		try {
			$clid = filter_var($request->clientId, FILTER_SANITIZE_STRING);
			$token = filter_var($request->token, FILTER_SANITIZE_STRING);

			$card = new Card();
			$card->client_id = $clid;
			$card->token = $token;
			$card->save();

			return response(['success' => true, 'card' => $card]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}

	public function update(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_STRING);
			$clid = filter_var($request->clientId, FILTER_SANITIZE_STRING);
			$token = filter_var($request->token, FILTER_SANITIZE_STRING);

			$card = Card::find($id);
			$card->client_id = $clid;
			$card->token = $token;
			$card->save();

			return response(['success' => true, 'card' => $card]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}

	public function delete(Request $request, $id) {
		try {
			$id = filter_var($id, FILTER_SANITIZE_STRING);

			$card = Card::find($id);
			$card->delete();

			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}
}

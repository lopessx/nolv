<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller {
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

	public function getProductRatings($id) {
		try {
			$ratings = Rating::where('product_id', $id)->get();
			foreach ($ratings as $rating) {
				$rating->client;
			}

			return response(['success' => true, 'ratings' => $ratings]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function getClientRating($pid, $cid) {
		try {
			$rating = Rating::where([
				['client_id', '=', $cid],
				['product_id', '=', $pid],
			])->first();
			$rating->client;

			return response(['success' => true, 'rating' => $rating]);
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
		DB::beginTransaction();

		try {
			$rating = new Rating();
			$rating->product_id = $request->productId;
			$rating->rating = $request->rating;
			$rating->client_id = $request->clientId;
			$rating->comment = $request->comment;
			$rating->save();

			DB::commit();

			return response(['success' => true, 'rating' => $rating]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function update(Request $request, $id) {
		DB::beginTransaction();

		try {
			$rating = Rating::where('id', $id)->first();
			$rating->rating = $request->rating;
			$rating->comment = $request->comment;
			$rating->save();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}

	public function delete($id) {
		DB::beginTransaction();

		try {
			$rating = Rating::where('id', $id)->first();
			$rating->delete();

			DB::commit();

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 404);
		}
	}
}

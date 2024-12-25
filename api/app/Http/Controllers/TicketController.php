<?php

namespace App\Http\Controllers;

use App\Mail\NewAdminSupport;
use App\Mail\NewTicket;
use App\Models\Client;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller {
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
		DB::beginTransaction();

		try {
			$clientId = preg_replace('/\D/', '', $request->clientId);

			$client = Client::find($clientId);

			$clientName = strip_tags($client->name);
			$productName = strip_tags($request->productName);

			$ticket = new Ticket();
			$ticket->client_id = $clientId;
			$ticket->store_id = preg_replace('/\D/', '', $request->storeId);
			$ticket->status_ticket_id = 1;
			$ticket->message = strip_tags($request->message);
			$ticket->save();

			DB::commit();

			Mail::to((string) $client->email)
			->send(new NewTicket($clientName, $ticket->message, $productName));

			return response(['success' => true]);
		} catch (Exception $e) {
			DB::rollBack();

			return response(['message' => $e->getMessage(), 'code' => $e->getCode(), 'stack' => $e->getTrace()], 404);
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

	public function sendSupportMessageAdmin(Request $request) {
		try {
			$adminEmail = config('mail.from.address');
			$message = strip_tags($request->message);
			$clientId = strip_tags($request->clientId);
			$client = Client::find($clientId);

			Mail::to((string) $adminEmail)
			->send(new NewAdminSupport($client->name, $client->email, $message));

			return response(['success' => true]);
		} catch (Exception $e) {
			return response(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}
	}
}

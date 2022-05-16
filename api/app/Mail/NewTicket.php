<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTicket extends Mailable {
	use Queueable;
	use SerializesModels;

	/**
	 * The product instance.
	 *
	 * @var string
	 */
	// public $clientName;

	/**
	 * The ticket message
	 *
	 * @var string
	 */
	// public $message;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//
		$this->clientName = 'John Doe';
		$this->message = 'aspidapsojd';
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		// FIXME bug related to type
		return $this->subject('Suporte requisitado')
		->view('emails.ticket');
	}
}

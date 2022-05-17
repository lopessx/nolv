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
	public $clientName;

	/**
	 * The ticket message
	 *
	 * @var string
	 */
	public $content;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($clientName, $content) {
		$this->clientName = $clientName;
		$this->content = $content;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->subject('Suporte requisitado por ' . $this->clientName)
		->view('emails.ticket');
	}
}

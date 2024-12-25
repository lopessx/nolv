<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAdminSupport extends Mailable {
	use Queueable;
	use SerializesModels;

	/**
	 * The ticket message
	 *
	 * @var string
	 */
	public $content;

	/**
	 * The client name.
	 *
	 * @var string
	 */
	public $clientName;

	/**
	 * The client email.
	 *
	 * @var string
	 */
	public $clientEmail;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($clientName, $clientEmail, $content) {
		$this->content = $content;
		$this->clientName = $clientName;
		$this->clientEmail = $clientEmail;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->subject('Cliente ' . $this->clientName . ' entrou em contato')
		->view('emails.support');
	}
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTicket extends Mailable {
	use Queueable;
	use SerializesModels;

	/**
	 * The client name.
	 *
	 * @var string
	 */
	public $clientName;

	/**
	 * The product name
	 *
	 * @var string
	 */
	public $productName;

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
	public function __construct($clientName, $content, $productName) {
		$this->clientName = $clientName;
		$this->content = $content;
		$this->productName = $productName;
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

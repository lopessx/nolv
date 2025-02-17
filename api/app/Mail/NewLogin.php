<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLogin extends Mailable {
	use Queueable;
	use SerializesModels;

	/**
	 * The order instance.
	 *
	 * @var string
	 */
	public $code;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($code) {
		//
		$this->code = $code;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->subject('Tentativa de login')
		->view('emails.login');
	}
}

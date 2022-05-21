<?php

namespace App\Providers;

use App\Models\Client;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	/**
	 * Boot the authentication services for the application.
	 *
	 * @return void
	 */
	public function boot() {
		// TODO change password to api_key or something
		$this->app['auth']->viaRequest('', function ($request) {
			if ($request->header('Authorization')) {
				$key = explode(' ', $request->header('Authorization'));
				$user = Client::where('password', $key[1])->first();
				if (!empty($user)) {
					$request->request->add(['userid' => $user->id]);
				}

				return $user;
			}
		});
	}
}

<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate {
	/**
	 * The authentication guard factory instance.
	 *
	 * @var \Illuminate\Contracts\Auth\Factory
	 */
	protected $auth;

	/**
	 * Create a new middleware instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Factory  $auth
	 * @return void
	 */
	public function __construct(Auth $auth) {
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
		if ($request->header('Authorization')) {
			$key = base64_decode(explode(' ', $request->header('Authorization'))[1]);
			$user = Client::where('password', $key)->first();
			if (!empty($user)) {
				$request->request->add(['clientId' => $user->id]);

				return $next($request);
			} else {
				return response('Unauthorized', 401);
			}
		} else {
			return response('Unauthorized', 401);
		}
	}
}

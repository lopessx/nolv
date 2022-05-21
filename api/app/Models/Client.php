<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Client extends Model implements AuthenticatableContract, AuthorizableContract {
	use Authenticatable;
	use Authorizable;
	use HasFactory;

	public $timestamps = true;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'clients';

	// TODO refactor password attributes to access_key or something alike
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', 'phone'];

	/**
	 * The columns that are hidden from front-end view
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'created_at', 'updated_at'];
}

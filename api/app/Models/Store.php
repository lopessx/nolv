<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model {
	use HasFactory;
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'stores';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['client_id', 'name', 'balance'];

	public function products() {
		return $this->hasMany(Products::class);
	}
}

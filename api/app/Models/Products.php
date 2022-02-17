<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model {
	use HasFactory;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['store_id', 'category_id', 'name', 'price', 'file_path', 'operational_system_id', 'lang_id'];

	public function category() {
		return $this->hasOne(Category::class, 'id', 'category_id');
	}

	public function store() {
		return $this->belongsTo(Stores::class);
	}

	public function language() {
		return $this->hasOne(Language::class, 'id', 'language_id');
	}

	public function os() {
		return $this->hasOne(Os::class, 'id', 'operational_system_id');
	}

	public function images() {
		return $this->hasMany(ProductsImages::class, 'product_id', 'id');
	}
}

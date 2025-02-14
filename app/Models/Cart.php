<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
  protected $fillable = [
    'user_id',
    'product_id',
    'quantity',
  ];

  /**
   * Define relationship: A cart item belongs to a product.
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }
}

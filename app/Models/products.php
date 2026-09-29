<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class products extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'stock',
        'category_id',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(categories::class, 'category_id');
    }
}

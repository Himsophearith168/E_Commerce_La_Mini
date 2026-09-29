<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product_images extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_path',
    ];

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}

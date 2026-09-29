<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class categories extends Model
{
    //
    protected $table = 'categories';

     protected $fillable = [
        'name',
        'slug',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(products::class, 'category_id');
    }
}

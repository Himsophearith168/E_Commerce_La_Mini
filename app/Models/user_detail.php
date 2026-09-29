<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_detail extends Model
{
    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'address',
        'phone_number',
        'profile_picture',
        'gender',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

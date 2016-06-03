<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ThirdAuth extends Model
{
    protected $table = 'third_auth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'oauth_name', 'oauth_id', 'oauth_access_token', 'oauth_expires'
    ];
}

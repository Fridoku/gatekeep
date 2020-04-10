<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Token extends Model
{

    protected $attributes = [
        'name' => NULL,
        'enabled' => true,
    ];

    #Attributes for JSON Serialization
    protected $visible = ['name', 'enabled', 'user'];

    #Get user that owns this Token
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}

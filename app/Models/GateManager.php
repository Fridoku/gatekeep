<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class GateManager extends Model
{

    protected $attributes = [
        'notes' => NULL,
        'enabled' => true,
        'mac' => NULL
    ];

    #Attributes for JSON Serialization
    protected $visible = ['name', 'notes', 'enabled', 'gates'];

    #Return Gates that belong to this Gate Manager
    public function gates() {
        return $this->hasMany('\App\Models\Gate');
    }

}

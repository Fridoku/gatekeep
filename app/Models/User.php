<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class User extends Model
{

    protected $attributes = [
        'ldap_uuid' => NULL,
        'is_admin' => false,
        'password_hash' => NULL,
        'email' => NULL,
        'enabled' => true,
    ];

    #Attributes for JSON Serialization
    protected $visible = ['username', 'first_name', 'last_name', 'enabled'];

    #Returns Collection of Gates the User can access
    public function gates() {
        return $this->belongsToMany('App\Models\Gate', 'user_access_rights');
    }

    #Returns Collection of Tokens that are owned by this user
    public function tokens() {
        return $this->hasMany('App\Models\Token');
    }



    #Return array of the IDs of Gates this User can access
    public function gateIds() {
        $ids = [];
        foreach ($this->gates as $g) {
            $ids[] = $g->id;
        }
        return $ids;
    }

    #Add Gate(s) to this User
    public function addGates($gates) {
        if(is_array($gates)){
            foreach ($gates as $gate) {
                $this->gates()->attach($gate->id);
            }
        }
        else $this->gates()->attach($gates->id);
    }
    #Remove Gates from User
    public function removeGates($gates) {
        if(is_array($gates)){
            foreach ($gates as $gate) {
                $this->gates()->detach($gate->id);
            }
        }
        else $this->gates()->detach($gates->id);
    }

    #Set Password for this User
    public function setPassword($password) {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    #Verify Password for this User
    public function checkPassword($password) {
        return password_verify($password, $this->password_hash);
    }

}

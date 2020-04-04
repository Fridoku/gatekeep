<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Gate extends Model
{

    protected $attributes = [
        'notes' => NULL,
        'enabled' => true,
    ];

    #List Users that can access this Gate
    public function users() {
        return $this->belongsToMany('App\Models\User', 'user_access_rights');
    }

    #Return array of the IDs of Users that can access this Gate
    public function userIds() {
        $ids = [];
        foreach ($this->users as $u) {
            $ids[] = $u->id;
        }
        return $ids;
    }

    #Add User(s) to this Gate
    public function addUsers($users) {
        if(is_array($$users)) {
            foreach ($users as $user) {
                $this->users()->attach($user->id);
            }
        }
        else $this->users()->attach($users->id);
    }
    #Remove User(s) from this Gate
    public function removeUsers($users) {
        if(is_array($users)) {
            foreach ($users as $user) {
                $this->users()->detach($user->id);
            }
        }
        else $this->users()->detach($users->id);
    }

    #Return GateController for this Gate
    public function gateManager() {
        return $this->belongsTo('App\Models\GateManager', 'gate_manager_id');
    }


}

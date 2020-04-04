<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;

use App\Models\User;
use App\Models\Gate;

class UserController extends Controller
{


    #Return User List
    public function listUsers() {
        $this->users = User::all();
        $this->users->sortBy('username');
        return $this->quickView('admin.userList');
    }

    #Render Edit User View
    public function editUser($id=NULL) {

        $this->gates = Gate::all();

        #Check if this is for an existing Session, a new User or to start editing an existing one.
        if( empty($this->user) ) {
            if( $this->request->is('admin/user/new') || $id == 0 ) {
                $this->user = new User;
                #To create proper Submit Link set id to 0
                $this->user->id = 0;
            }
            else {
                $this->user = User::findOrFail($id);
            }
        }
        $userGateIds = $this->user->gateIds();

        $this->tokens = $this->user->tokens;


        #Warn if User was inported from LDAP
        if( $this->user->ldap_uuid != NULL ) $this->warnings[] = 'Cant edit some attributes of protected LDAP User';

        return $this->quickView('admin.editUser', ['userGateIds' => $userGateIds]);

    }

    #Disable User
    public function disableUser($id) {
        $this->user = User::findOrFail($id);
        $this->user->enabled = false;
        $this->user->save();
        $this->successes[] = 'User '.$this->user->username.' disabled.';
        if ($this->user->is_admin == true ) $this->warnings[] = 'User was set to disabled but is still admin. Please disable admin access seperately.';
        return $this->redirectWithAlerts('admin.listUsers');
    }

    #Enable User
    public function enableUser($id) {
        $this->user = User::findOrFail($id);
        $this->user->enabled = true;
        $this->user->save();
        $this->successes[] = 'User '.$this->user->username.' enabled.';
        return $this->redirectWithAlerts('admin.listUsers');

    }

    #Delete User
    public function deleteUser($id) {
        $this->user = User::findOrFail($id);
        if( empty($this->user->ldap_uuid) ) {
            $this->user->delete();
            $this->successes[] = 'User '.$this->user->username.' deleted.';
        }
        else $this->errors[] = "Can't delete protected LDAP User.";
        return $this->redirectWithAlerts('admin.listUsers');
    }

    #Save Gates the user can access
    public function saveUserGates($id) {
        $this->user = User::findOrFail($id);

        if ( $this->request->has('gate') ) {
            $ids = $this->request->input('gate');
            $ids = is_array($ids) ? $ids : array($ids);
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            if( in_array(false, $ids)) $this->errors[] = 'Invalid Values given!';
        }
        else $ids = [];

        if( empty($this->errors) ) {
            $this->user->gates()->sync($ids);
            $this->user->save();
            $this->successes[] = 'Gate access saved.';
        }
        return $this->redirectWithAlerts('admin.editUser', ['id' => $this->user->id]);
    }

    #Save User
    public function saveUser($id) {

        if ( $id == 0) $this->user = new User;

        else  $this->user = User::findOrFail($id);

        #Username should be characters and numbers only, no spaces.
        if( $this->request->filled('username')) {
            $input = $this->request->input('username');

            #Was this all for the cat? (Check if username exists)
            if($id == 0) {
                $existingUser = User::where('username', $input)->first();
                if (isset($existingUser)) {
                    $this->errors[] = 'User already exists. New User not saved.';
                    return $this->redirectWithAlerts('admin.editUser', ['id' => $existingUser->id]);
                }
            }

            if( $this->user->ldap_uuid != NULL && $input != $this->user->username) $this->errors[] ='I told you you can\'t do this! Leave my Database alone.';

            elseif( preg_match('/^\w+$/', $input) === 1 ) $this->user->username = $input;

            else $this->errors[] = 'Enter a valid Username!';
        }
        else $this->errors[] = 'Enter a username!';

        #First Name and Last name should be characters, dashes and spaces only
        if( $this->request->filled('first_name')) {
            $input = $this->request->input('first_name');

            if( $this->user->ldap_uuid != NULL && $input != $this->user->first_name) $this->errors[] ='I told you you can\'t do this! Leave my Database alone.';

            elseif( empty($input) ) $this->user->email = NULL;

            elseif( preg_match('/^((?=[^\d])[\wöäüÖÄÜß \-])+$/', $input ) === 1) $this->user->first_name = $input;

            else $this->errors[] = 'Enter a valid First Name';
        }
        elseif( !empty($this->user->first_name)) $this->user->first_name = NULL;

        if( $this->request->filled('last_name')) {
            $input = $this->request->input('last_name');

            if( $this->user->ldap_uuid != NULL && $input != $this->user->last_name) $this->errors[] ='I told you you can\'t do this! Leave my Database alone.';

            elseif( empty($input) ) $this->user->email = NULL;

            elseif( preg_match('/^((?=[^\d])[\wöäüÖÄÜß \-])+$/', $input ) === 1) $this->user->last_name = $input;

            else $this->errors[] = 'Enter a valid Last Name';
        }
        elseif( !empty($this->user->last_name)) $this->user->last_name = NULL;


        #Email should be a valid Email
        if( $this->request->has('email')) {
            $input = $this->request->input('email');

            if( $this->user->ldap_uuid != NULL && $input != $this->user->email) $this->errors[] ='I told you you can\'t do this! Leave my Database alone.';

            elseif( empty($input) ) $this->user->email = NULL;

            elseif( !filter_var($input, FILTER_VALIDATE_EMAIL) === false ) $this->user->email = $input;

            else $this->errors[] = 'Enter a valid Email Address';
        }


        #Set admin Password
        if( $this->request->filled('password1') && $this->request->input('password1') != '••••') {
            if ($this->request->input('password1') === $this->request->input('password2', '') ) {
                $this->user->password_hash = password_hash($this->request->input('password1'), PASSWORD_DEFAULT);
            }
            else $this->errors[] = 'Passwords do not match!';
        }
        elseif( $this->request->filled('password2') && $this->request->input('password2') != '••••') $this->errors[] = 'Passwords do not match!';
        #But what if the Password should be deleted?
        elseif( !$this->request->filled('password1') && !$this->request->filled('password2') && $this->user->password_hash != NULL) {
            $this->user->password_hash = NULL;
            $this->warnings[] = 'Password unset';
        }

        #Set user to admin if desired
        if( $this->request->has('is_admin') && filter_var($this->request->input('is_admin'), FILTER_VALIDATE_BOOLEAN)) {
            if( $this->user->is_admin != true) {
                #Dont allow admins with an empty Password
                if( $this->user->password_hash != NULL ) $this->user->is_admin = true;
                else $this->warnings[] = 'Cant enable admin access without valid password!';
            }
        }
        #Dont allow someone to delete his own admin access
        elseif( $this->user->id === $this->request->input('authenticated_admin_id') ) $this->errors[] = 'You can\'t edit your own admin access. Find someone else to do it.';

        elseif( $this->user->is_admin != false) $this->user->is_admin = false;

        #Disable Admin access if no password is set
        if( $this->user->is_admin && empty($this->user->password_hash) ) {
            $this->user->is_admin = false;
            $this->warnings[] = 'Admin access removed because no password is set.';
        }


        #Disable/Enable User
        if( $this->request->has('enabled') && filter_var($this->request->input('enabled'), FILTER_VALIDATE_BOOLEAN) ) {
            $this->user->enabled = true;
        }
        elseif( $this->user->enabled != false) {
            $this->user->enabled = false;
            #Notify that user still has admin access
            if ($this->user->is_admin == true ) $this->warnings[] = 'User was set to disabled but is still admin. Please disable admin access seperately.';
        }



        #Decide whether to save User or not.
        #If there was an error use the ID from the request, otherwise the one from the model. This way a failed User Creation stays on the Create User Page.
        if( empty($this->errors) ) {
            $this->user->save();
            $this->successes[] = 'User saved.';
            return $this->redirectWithAlerts('admin.editUser', ['id' => $this->token->id]);
        }
        else {
            $this->warnings[] = 'User not saved.';
            return $this->editUser($this->request, $id);
        }
    }



}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\User;
use App\Models\Token;

class TokenController extends Controller
{
    #List all Tokens
    public function listTokens() {
        $this->tokens = Token::all();
        $this->tokens->sortBy('user->username');

        return $this->quickView('admin.tokenList',);
    }

    #Render Edit Token Screen
    public function editToken( $id = NULL) {
        if( empty($this->token) ) { #Is this a new request?
            if( $this->request->is('admin/token/new') || $id == 0 ) { #Is this a new token

                $this->token = new Token;
                #To create proper submit Link, set id to zero
                $this->token->id = 0;
                #Was this request for a specific User? Then preset the user for this token
                if ($this->request->filled('user') && filter_var($this->request->input('user'), FILTER_VALIDATE_INT)) $this->token->user = User::find($this->request->input('user'));

            }
            else { #Or an existing Token

                $this->token = Token::findOrFail($id);
            }
        }
        $this->users = User::all();
        $this->users->sortBy('username');
        return $this->quickView('admin.editToken');
    }

    #Disable Token
    public function disableToken($id) {
        $this->token = Token::findOrFail($id);
        $this->token->enabled = false;
        $this->token->save();
        $this->successes[] = 'Token "'.$this->token->name.'" disabled.';
        return $this->redirectWithAlerts('admin.listTokens');
    }

    #Enable Token
    public function enableToken($id) {
        $this->token = Token::findOrFail($id);
        $this->token->enabled = true;
        $this->token->save();
        $this->successes[] = 'Token "'.$this->token->name.'" enabled.';
        return $this->redirectWithAlerts('admin.listTokens');
    }

    #Delete Token
    public function deleteToken($id) {
        $this->token = Token::findOrFail($id);
        $this->token->delete();
        $this->successes[] = 'Token "'.$this->token->name.' " deleted.';
        return $this->redirectWithAlerts('admin.listTokens');
    }

    #Save Token
    public function saveToken($id) {
        if( $id == 0) {
            $this->token = new Token;
        }
        else {
            $this->token = Token::findOrFail($id);
        }

        #Name should be should be characters, numbers dashes and spaces only
        if( $this->request->filled('name')) {
            $input = $this->request->input('name');

            if( preg_match('/^([\wöäüÖÄÜß \-_])+$/', $input) === 1 ) $this->token->name = $input;

            else $this->errors[] = 'Enter a valid name!';
        }
        else $this->errors[] = 'Enter a name for this Token!';

        #Set Token User
        if( $this->request->filled('user')) {
            $input = filter_var($this->request->input('user'), FILTER_SANITIZE_NUMBER_INT);
            #Does the user even exist?
            try {
                $this->user = User::findOrFail($input);
                #Does the User already have a token with this name?
                if ( $this->user->tokens()->where( [ ['name', '', $this->token->name] , ['id', '!=', $this->token->id ] ] )->exists()) {
                    $this->errors[] = 'The selected User already has a Token with this name.';
                }
                else {
                    $this->token->user()->associate($this->user);
                }
            }
            catch (ModelNotFoundException $e) {
                $this->errors[] = 'User not found!';
            }
                }
        else $this->errors[] = 'Specify the user of this Token!';


        #Save Token Identification
        if( !$this->request->filled('token_id') || $this->request->input('token_hash') == "••••" ) {
            if( empty($this->token->hash) ) $this->errors[] = 'Please set an Identifier for this Token!';
        }
        else {
            if( password_verify( $this->request->input('token_id'), $this->token->token_hash) ) {
               $this->warnings[] = 'Identifier was not changed';
            }
            else $this->token->token_hash = password_hash($this->request->input('token_id'), PASSWORD_DEFAULT);
        }

        #Disable/Enable Token
        if( $this->request->has('enabled') && filter_var($this->request->input('enabled'), FILTER_VALIDATE_BOOLEAN) ) {
            $this->token->enabled = true;
        }
        elseif( $this->token->enabled != false) {
            $this->token->enabled = false;
        }


        #Decide whether to save Token or not.
        #If there was an error use the ID from the request, otherwise the one from the model. This way a failed Creation stays on the Create Page.
        if( empty($this->errors) ) {
            $this->token->save();
            $this->successes[] = 'Token saved.';
            return $this->redirectWithAlerts('admin.editToken', ['id' => $this->token->id]);
        }
        else {
            $this->warnings[] = 'Token not saved.';
            return $this->editToken($this->request, $id);
        }

    }
}

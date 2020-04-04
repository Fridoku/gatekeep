<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Gate;
use App\Models\GateManager;
use App\Models\User;


class GateController extends Controller
{
    #List all Gates
    public function listGates() {
        $this->gates = Gate::all();
        $this->gates->sortBy('nice_name');

        return $this->quickView('admin.gateList',['gates' => $this->gates]);
    }

    #Render Edit Gate Screen
    public function editGate( $id = NULL) {
        if( empty($this->gate) ) { #Is this a new request?
            if( $this->request->is('admin/gate/new') || $id == 0 ) { #Is this a new Gate

                $this->gate = new Gate;
                #To create proper submit Link, set id to zero
                $this->gate->id = 0;
                #Was this request for a specific Gatemanager? Then preset the Gatemanager for this Gate
                if ($this->request->filled('gateManager') && filter_var($this->request->input('gateManager'), FILTER_VALIDATE_INT)) $this->gate->gateManager = GateManager::find($this->request->input('gateManager'));

            }
            else { #Or an existing Gate

                $this->gate = Gate::findOrFail($id);

            }
        }

        $this->gateManagers = GateManager::all();
        $this->users = User::all();
        $gateUserIds = $this->gate->userIds();

        return $this->quickView('admin.editGate', ['gateUserIds' => $gateUserIds]);
    }

    #Disable Gate
    public function disableGate($id) {
        $this->gate = Gate::findOrFail($id);
        $this->gate->enabled = false;
        $this->gate->save();
        $this->successes[] = $this->gate->nice_name.' disabled.';
        return $this->redirectWithAlerts('admin.listGates');
    }

    #Enable Gate
    public function enableGate($id) {
        $this->gate = Gate::findOrFail($id);
        $this->gate->enabled = true;
        $this->gate->save();
        $this->successes[] = $this->gate->nice_name.' enabled.';
        return $this->redirectWithAlerts('admin.listGates');
    }

    #Delete Gate
    public function deleteGate($id) {
        $this->gate = Gate::findOrFail($id);
        $this->gate->delete();
        $this->successes[] = $this->gate->nice_name.' deleted.';
        return $this->redirectWithAlerts('admin.listGates');
    }

    #Save Users that user can access this Gate
    public function saveGateUsers($id) {
        $this->gate = Gate::findOrFail($id);

        if ( $this->request->has('user') ) {
            $ids = $this->request->input('user');
            $ids = is_array($ids) ? $ids : array($ids);
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            if( in_array(false, $ids)) $this->errors[] = 'Invalid Values given!';
        }
        else $ids = [];

        if( empty($this->errors) ) {
            $this->gate->users()->sync($ids);
            $this->gate->save();
            $this->successes[] = 'User access saved.';
        }
        return $this->redirectWithAlerts('admin.editGate', ['id' => $this->gate->id]);
    }


    #Save Gate
    public function saveGate($id) {
        if( $id == 0) {
            $this->gate = new Gate;
        }
        else {
            $this->gate = Gate::findOrFail($id);
        }

        #Name should be should be characters, numbers dashes and spaces only
        if( $this->request->filled('nicename')) {
            $input = $this->request->input('nicename');

            if( preg_match('/^([\wöäüÖÄÜß \-_])+$/', $input) === 1 ) $this->gate->nice_name = $input;

            else $this->errors[] = 'Enter a valid name!';
        }
        else $this->errors[] = 'Enter a name for this Gate!';

        #Name should be should be characters, numbers and dashes only
        if( $this->request->filled('name')) {
            $input = $this->request->input('name');

            if( preg_match('/^([\wöäüÖÄÜß\-_])+$/', $input) === 1 ) $this->gate->name = $input;

            else $this->errors[] = 'Enter a valid name!';
        }
        #If no name was set, generate an automatic name
        elseif( $this->request->has('name')) {
             $this->gate->name = str_replace([' ','ä','ö','ü','ß'], ['_','ae','oe','ue','ss'], strtolower($this->gate->nice_name));
        }

        #Set Gatemanager
        if( $this->request->filled('gateManager')) {
            $input = filter_var($this->request->input('gateManager'), FILTER_SANITIZE_NUMBER_INT);
            #Does the GateManager even exist?
            try {
                $this->gateManager = GateManager::findOrFail($input);
                $this->gate->gateManager()->associate($this->gateManager);
            }
            catch (ModelNotFoundException $e) {
                $this->errors[] = 'Gatemanager not found!';
            }
                }
        else $this->errors[] = 'Specify the Gatemanager of this Gate!';

        #Set Notes
        if( $this->request->filled('notes')) {
            $input = $this->request->input('notes');
            if( strlen($input) > 500 ) $this->errors[] = 'Note Text is too long!';
            else $this->gate->notes = $input;
        }

        #Disable/Enable Gate
        if( $this->request->has('enabled') && filter_var($this->request->input('enabled'), FILTER_VALIDATE_BOOLEAN) ) {
            $this->gate->enabled = true;
        }
        elseif( $this->gate->enabled != false) {
            $this->gate->enabled = false;
        }


        #Decide whether to save Gate or not.
        if( empty($this->errors) ) {
            $this->gate->save();
            $this->successes[] = 'Gate saved.';
            #If successful redirect
            return $this->redirectWithAlerts('admin.editGate', ['id' => $this->gate->id]);
        }
        else {
            $this->warnings[] = 'Gate not saved.';
            #If not return with old request
            return $this->editGate($this->request, $id);
        }
    }

}






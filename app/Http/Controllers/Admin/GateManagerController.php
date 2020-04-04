<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Models\GateManager;

class GateManagerController extends Controller
{
    #List all GateManagers
    public function listGateManagers() {
        $this->gateManagers = GateManager::all();
        $this->gateManagers->sortBy('name');

        return $this->quickView('admin.gateManagerList');
    }

    #Render Edit GateManager Screen
    public function editGateManager( $id = NULL) {
        if( empty($this->gateManager) ) { #Is this a new request?
            if( $this->request->is('admin/gatemanager/new') || $id == 0 ) { #Is this a new Gatemanager

                $this->gateManager = new GateManager;
                #To create proper submit Link, set id to zero
                $this->gateManager->id = 0;
            }
            else { #Or an existing Gatemanager

                $this->gateManager = GateManager::findOrFail($id);
            }
        }
        return $this->quickView('admin.editGateManager');
    }

    #Disable GateManager
    public function disableGateManager($id) {
        $this->gateManager = GateManager::findOrFail($id);
        $this->gateManager->enabled = false;
        $this->gateManager->save();
        $this->successes[] = 'GateManager "'.$this->gateManager->name.'" disabled.';
        return $this->redirectWithAlerts('admin.listGateManagers');
    }

    #Enable GateManager
    public function enableGateManager($id) {
        $this->gateManager = GateManager::findOrFail($id);
        $this->gateManager->enabled = true;
        $this->gateManager->save();
        $this->successes[] = 'GateManager "'.$this->gateManager->name.'" enabled.';
        return $this->redirectWithAlerts('admin.listGateManagers');
    }

    #Delete GateManager
    public function deleteGateManager($id) {
        $this->gateManager = GateManager::findOrFail($id);
        if ($this->gateManager->gates->count() != 0) {
            $this->errors[] = 'Can\'t delete Gatemanager because there are(is) still '.$this->gateManager->gates->count().' Gate(s) associated with it.';
        }
        else {
            $this->gateManager->delete();
            $this->successes[] = 'GateManager "'.$this->gateManager->name.' " deleted.';
        }
        return $this->redirectWithAlerts('admin.listGateManagers');
    }

    #Create a new API Key for the GateManager
    public function regenerateGateManager($id) {
        $this->gateManager = GateManager::findOrFail($id);
        $this->gateManager->api_key = bin2hex(random_bytes(16));
        $this->gateManager->save();
        $this->successes[] = 'API Key regenerated';
        return $this->redirectWithAlerts('admin.editGateManager', ['id' =>$id]);

    }

    #Save GateManager
    public function saveGateManager($id) {
        if( $id == 0) {
            $this->gateManager = new GateManager;
        }
        else {
            $this->gateManager = GateManager::findOrFail($id);
        }

        #Name should be should be characters, numbers dashes and spaces only
        if( $this->request->filled('name')) {
            $input = $this->request->input('name');

            if( preg_match('/^([\wöäüÖÄÜß \-_])+$/', $input) === 1 ) $this->gateManager->name = $input;

            else $this->errors[] = 'Enter a valid name!';
        }
        else $this->errors[] = 'Enter a name for this Gatemanager!';

        #Set Notes
        if( $this->request->filled('notes')) {
            $input = $this->request->input('notes');
            if( strlen($input) > 500 ) $this->errors[] = 'Note Text is too long!';
            else $this->gateManager->notes = $input;
        }

        #Disable/Enable Gatemanager
        if( $this->request->has('enabled') && filter_var($this->request->input('enabled'), FILTER_VALIDATE_BOOLEAN) ) {
            $this->gateManager->enabled = true;
        }
        elseif( $this->gateManager->enabled != false) {
            $this->gateManager->enabled = false;
        }

        #Set MAC Address
        if( $this->request->filled('mac')) {
            $input = $this->request->input('mac');
            if( filter_var($input, FILTER_VALIDATE_MAC) ) $this->gateManager->mac = $input;
            else $this->errors[] = 'Please enter a valid MAC Address';
        }
        else $this->gateManager->mac = NULL;

        #Decide whether to save GateManager or not.
        #If there was an error use the ID from the request, otherwise the one from the model. This way a failed Creation stays on the Create Page.
        if( empty($this->errors) ) {
            #If everything was successfull, generate an API key
            $this->gateManager->api_key = bin2hex(random_bytes(16));
            $this->gateManager->save();
            $this->successes[] = 'Gatemanager saved.';
            return $this->redirectWithAlerts('admin.editGateManager', ['id' => $this->gateManager->id]);
        }
        else {
            $this->warnings[] = 'Gatemanager not saved.';
            return $this->editGateManager($this->request, $id);
        }

    }


}


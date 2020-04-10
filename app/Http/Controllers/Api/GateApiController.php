<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Gate;
use App\Models\GateManager;
use App\Models\Token;
use App\Models\Event;

class GateApiController extends Controller
{


    public function __construct(Request $request) {
        parent::__construct($request);

        $gateName = $this->request->route('gate');
        $this->gate = Gate::where('name',  $gateName)->firstOrFail();
        $this->gateManager = GateManager::find($this->request->input('authenticated_gate_manager'));

    }

    public function getStatus() {

        if ($this->gate->gateManager->isNot($this->gateManager) ) {
            return response('Not allowed to access this Gate', 403);
        }

        $this->gate->load('gateManager');

        $response['gate'] = $this->gate;

        return response()->json($response);

    }

    public function authenticate() {

        if ($this->gate->gateManager->isNot($this->gateManager) ) {
            return response('Not allowed to access this Gate', 403);
        }

        $response['authorized'] = false;

        if ($this->gate->enabled) {
            if ($this->request->filled('token_id')) {

                $this->token = Token::where('token_hash', hash("SHA256", $this->request->input('token_id')))->first();

                if(!empty($this->token)) {

                    $this->token->load('user');

                    if ($this->token->enabled) {

                        if($this->token->user->enabled) {

                            if($this->token->user->gates->contains($this->gate)) {

                                $response['authorized'] = true;
                                $response['reason'] = 'Authorized.';
                            }
                            else $response['reason'] = 'Not authorized.';

                        }
                        else $response['reason'] = 'User disabled.';

                    }
                    else $response['reason'] = 'Token disabled.';

                }
                else $response['reason'] = 'Token not found.';


            }
            else $response['reason'] = 'Empty Token not allowed.';
        }
        else $response['reason'] = 'Gate disabled.';

        $response['token'] = $this->token;

        return response()->json($response);
    }

}

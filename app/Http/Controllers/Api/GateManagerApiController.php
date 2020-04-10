<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\GateManager;


class GateManagerApiController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);

        $this->gateManager = GateManager::find($this->request->input('authenticated_gate_manager'));

    }

    public function getStatus() {

        $this->gateManager->load('gates');

        $response['gateManager'] = $this->gateManager->toArray();


        return response()->json($response);

    }

}

<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\GateManager;

class ControllerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( $request->filled('api_key'))
        {
            $gateManager = GateManager::where('api_key', $request->input('api_key'))->firstOrFail();

            if($gateManager !== NULL)
            {
                if (!$gateManager->enabled)
                {
                    return response('Access disabled', 403);
                }
                else {
                    $request->merge(['authenticated_gate_manager' => $gateManager->id]);
                    return $next($request);
                }
            }
        }

        return response('Not authorized ', 401);
    }
}

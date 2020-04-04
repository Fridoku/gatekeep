<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class AdminAuth
{
    private $headers = array('WWW-Authenticate' => 'Basic');

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {


        if ($request->getUser() !== NULL && $request->getPassword() !== NULL)
        {
            $user = User::where('username', $request->getUser())->first();
            if($user !== NULL)
            {
                if ($user->is_admin)
                {
                    if(password_verify($request->getPassword(), $user->password_hash))
                    {
                        $request->merge(['authenticated_admin_id' => $user->id]);
                        return $next($request);
                    }
                }
            }
        }

        return response('Admin Login', 401, $this->headers);
    }
}

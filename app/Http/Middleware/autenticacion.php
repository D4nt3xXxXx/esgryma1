<?php

namespace App\Http\Middleware;

use Caffeinated\Shinobi\Models\Role;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class autenticacion
{
    protected $auth;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if (!$request->ajax()/* && url()->previous() != url('empresa') && !Auth::check()*/) {
                if ($request->ajax()) {
                    return response('Acceso no autorizado.', 401);
                } else {
                    return redirect('/');
                }
            }
        }
        return $next($request);
    }
}

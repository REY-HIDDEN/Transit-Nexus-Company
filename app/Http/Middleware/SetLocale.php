<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App as AppFacade;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->session()->get('locale', config('app.locale'));
        if (in_array($locale, ['en', 'rw'])) {
            AppFacade::setLocale($locale);
        }
        return $next($request);
    }
}

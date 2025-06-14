<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Get locale from header or query parameter
        $locale = $request->header('Accept-Language') ?? $request->query('locale');
        
        // If locale is provided and is supported, set it
        if ($locale && in_array($locale, config('app.available_locales', ['en', 'fr']))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
} 
<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class EnsureMfaForRoles
{
    protected array $requiredRoles = ['admin', 'accountant'];

    public function handle(Request $request, Closure $next): mixed
    {
        $user = Filament::auth()->user();

        if (! $user) {
            return redirect()->guest(Filament::getLoginUrl());
        }

        $hasRequiredRole = $user->roles()->whereIn('name', $this->requiredRoles)->exists();

        if (! $hasRequiredRole) {
            return $next($request);
        }

        foreach (Filament::getMultiFactorAuthenticationProviders() as $provider) {
            if ($provider->isEnabled($user)) {
                return $next($request);
            }
        }

        return redirect()->to(Filament::getSetUpRequiredMultiFactorAuthenticationUrl());
    }
}
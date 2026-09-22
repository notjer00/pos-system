<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedLoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response
    {
        $user = $request->user();

        $redirect = match (true) {
            $user->isAdmin() => route('dashboard'),
            $user->isCashier() => route('cashier.checkout'),
            default => route('home'),
        };

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect($redirect);
    }
}

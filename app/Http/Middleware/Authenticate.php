<?php

namespace App\Http\Middleware;

use App\Enums\ErrorEnum;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Authenticate extends Middleware
{
    public function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new AccessDeniedHttpException(__('auth.no_session'), null, ErrorEnum::ACCESS_DENIED->value);
    }
}

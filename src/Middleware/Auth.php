<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Middleware;

use Closure;
use CodeSinging\PinAdmin\Exceptions\AdminException;
use CodeSinging\PinAdmin\Foundation\Admin;
use Illuminate\Auth\Middleware\Authenticate;

class Auth extends Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        Admin::boot(array_shift($guards));
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * @throws AdminException
     */
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()){
            throw new AdminException('Not authenticated');
        }

        return Admin::link('auth');
    }
}

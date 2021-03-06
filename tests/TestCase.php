<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests;

use CodeSinging\PinAdmin\Foundation\PinAdminServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app): array
    {
        return [
            PinAdminServiceProvider::class,
        ];
    }

    protected function getApplicationTimezone($app): string
    {
        return 'PRC';
    }
}

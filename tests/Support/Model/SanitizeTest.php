<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Model;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class SanitizeTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Factory::clean();
    }

    public function testSanitize()
    {
        Factory::clean();
        $this->artisan('admin:create admin');
        Admin::boot('admin');

        self::assertEquals([
            'username' => 'Name',
            'password' => 'Password'
        ], User::new()->sanitize([
            'username' => 'Name',
            'password' => 'Password',
            'none' => 'None',
        ]));

        Request::merge([
            'username' => 'Name',
            'password' => 'Password',
            'none' => 'None',
        ]);

        self::assertEquals([
            'username' => 'Name',
            'password' => 'Password'
        ], User::new()->sanitize(request()));
    }
}

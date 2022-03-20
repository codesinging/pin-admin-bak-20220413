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
use Tests\TestCase;

class SerializeDateTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Factory::clean();
    }

    public function testSerializeDate()
    {
        Factory::clean();

        $this->artisan('admin:create admin');
        Admin::boot('admin');

        User::new()->create(['username' => 'user1', 'password' => '123']);
        self::assertEquals(date('Y-m-d H:i:s'), User::new()->latest()->first()->toArray()['created_at']);
    }
}

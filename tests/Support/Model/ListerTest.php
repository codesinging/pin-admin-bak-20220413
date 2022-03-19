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

class ListerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Factory::clean();
    }

    public function testLister()
    {
        Factory::clean();
        $this->artisan('admin:create admin');
        Admin::boot('admin');

        $lister = User::new()->lister();

        self::assertArrayHasKey('data', $lister);
        self::assertArrayHasKey('total', $lister);
        self::assertArrayHasKey('page', $lister);
        self::assertEquals(0, $lister['page']);

        Request::merge(['page' => 1, 'size' => 10]);
        $lister = User::new()->lister();

        self::assertArrayHasKey('data', $lister);
        self::assertArrayHasKey('total', $lister);
        self::assertArrayHasKey('page', $lister);
        self::assertEquals(1, $lister['page']);
        self::assertEquals(10, $lister['size']);

    }
}

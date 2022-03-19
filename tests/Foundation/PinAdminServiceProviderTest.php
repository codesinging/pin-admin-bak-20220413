<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\PinAdmin;
use CodeSinging\PinAdmin\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PinAdminServiceProviderTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        Factory::clean();
    }

    public function testCreateApplication()
    {
        Factory::new('admin')->create();
        Admin::load();
        self::assertTrue(Factory::exists('admin'));
    }

    public function testRegisterBinding()
    {
        self::assertInstanceOf(PinAdmin::class, $this->app[PinAdmin::LABEL]);
        self::assertSame(app(PinAdmin::LABEL), app(PinAdmin::LABEL));
    }

    public function testRegisterCommands()
    {
        $this->artisan('admin')
            ->assertSuccessful();
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testLoadRoutes()
    {
        self::assertTrue(Route::getRoutes()->hasNamedRoute(Admin::boot('admin')->name('index', '.')));
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testConfigureAuthentication()
    {
        self::assertArrayHasKey('admin', config('auth.guards'));
        self::assertEquals('admin_users', config('auth.guards.admin.provider'));
        self::assertEquals(User::class, config('auth.providers.admin_users.model'));
    }
}

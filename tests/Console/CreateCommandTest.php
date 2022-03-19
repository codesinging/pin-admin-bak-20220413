<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Console;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    use RefreshDatabase;

    public static function tearDownAfterClass(): void
    {
        Factory::clean();
    }

    public function testCreate()
    {
        $this->artisan('admin:create admin')
            ->expectsOutputToContain('创建 PinAdmin 应用')
            ->assertSuccessful();

        self::assertTrue(Factory::exists('admin'));
        self::assertFileExists(Factory::new('admin')->app()->appPath('Controllers/IndexController.php'));

    }

    public function testVerifyName()
    {
        $this->artisan('admin:create 2admin')
            ->expectsOutputToContain('应用名称[2admin]非法')
            ->assertSuccessful();

        self::assertFalse(Factory::exists('2admin'));
    }

    public function testAppExisted()
    {
        $this->artisan('admin:create admin')
            ->expectsOutputToContain('创建 PinAdmin 应用')
            ->assertSuccessful();

        self::assertTrue(Factory::exists('admin'));

        $this->artisan('admin:create admin')
            ->expectsOutputToContain('应用[admin]已经存在')
            ->assertSuccessful();
    }

    /**
     * @depends testCreate
     * @return void
     */
    public function testRoutes()
    {
        $this->get('/admin')
            ->assertSeeText('Redirecting')
            ->assertSeeText('admin/auth')
            ->assertStatus(302);

        $this->get('/admin/auth')
            ->assertOk();
    }

    /**
     * @depends testCreate
     * @return void
     */
    public function testRunMigrations()
    {
        self::assertTrue(Schema::hasTable(User::new()->getTable()));
    }

    /**
     * @return void
     */
    public function testRunSeeders()
    {
        Factory::clean();
        $this->artisan('admin:create admin');

        $this->assertDatabaseHas(Admin::name('users', '_'), [
            'name' => 'admin'
        ]);
    }
}

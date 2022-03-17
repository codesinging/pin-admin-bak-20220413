<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Application;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\PinAdmin;
use Tests\TestCase;

class PinAdminTest extends TestCase
{
    protected function tearDown(): void
    {
        Factory::clean();
    }

    public function testVersion()
    {
        self::assertEquals(PinAdmin::VERSION, (new PinAdmin())->version());
    }

    public function testBrand()
    {
        self::assertEquals(PinAdmin::BRAND, (new PinAdmin())->brand());
    }

    public function testLabel()
    {
        self::assertEquals(PinAdmin::LABEL, (new PinAdmin())->label());
        self::assertEquals(PinAdmin::LABEL . '_config', (new PinAdmin())->label('config'));
        self::assertEquals(PinAdmin::LABEL . '-config', (new PinAdmin())->label('config', '-'));
    }

    public function testRootDirectory()
    {
        self::assertEquals('admins', (new PinAdmin())->rootDirectory());
        self::assertEquals('admins' . DIRECTORY_SEPARATOR . 'admin', (new PinAdmin())->rootDirectory('admin'));
        self::assertEquals('admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'config', (new PinAdmin())->rootDirectory('admin', 'config'));
    }

    public function testRootPath()
    {
        self::assertEquals(base_path('admins'), (new PinAdmin())->rootPath());
        self::assertEquals(base_path('admins' . DIRECTORY_SEPARATOR . 'admin'), (new PinAdmin())->rootPath('admin'));
        self::assertEquals(base_path('admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'config'), (new PinAdmin())->rootPath('admin', 'config'));
    }

    public function testRootAppDirectory()
    {
        self::assertEquals('Admins', (new PinAdmin())->rootAppDirectory());
        self::assertEquals('Admins' . DIRECTORY_SEPARATOR . 'Admin', (new PinAdmin())->rootAppDirectory('Admin'));
        self::assertEquals('Admins' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'Controllers', (new PinAdmin())->rootAppDirectory('Admin', 'Controllers'));
    }

    public function testRootAppPath()
    {
        self::assertEquals(app_path('Admins'), (new PinAdmin())->rootAppPath());
        self::assertEquals(app_path('Admins' . DIRECTORY_SEPARATOR . 'Admin'), (new PinAdmin())->rootAppPath('Admin'));
        self::assertEquals(app_path('Admins' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'Controllers'), (new PinAdmin())->rootAppPath('Admin', 'Controllers'));
    }

    public function testRootPublicDirectory()
    {
        self::assertEquals('public/admins', (new PinAdmin())->rootPublicDirectory());
        self::assertEquals('public/admins' . DIRECTORY_SEPARATOR . 'admin', (new PinAdmin())->rootPublicDirectory('admin'));
        self::assertEquals('public/admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'js', (new PinAdmin())->rootPublicDirectory('admin', 'js'));
    }

    public function testRootPublicPath()
    {
        self::assertEquals(public_path('admins'), (new PinAdmin())->rootPublicPath());
        self::assertEquals(public_path('admins' . DIRECTORY_SEPARATOR . 'admin'), (new PinAdmin())->rootPublicPath('admin'));
        self::assertEquals(public_path('admins' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'js'), (new PinAdmin())->rootPublicPath('admin', 'js'));
    }

    public function testPackagePath()
    {
        self::assertEquals(dirname(__DIR__), (new PinAdmin())->packagePath('tests'));
        self::assertEquals(__DIR__, (new PinAdmin())->packagePath('tests', 'Foundation'));
    }

    public function testLoad()
    {
        $admin = new PinAdmin();
        self::assertCount(0, $admin->apps());
        Factory::new('admin')->create();
        self::assertCount(0, $admin->apps());
        $admin->load();
        self::assertCount(1, $admin->apps());
    }

    public function testBoot()
    {
        $admin = new PinAdmin();
        Factory::new('admin1')->create();
        Factory::new('admin2')->create();
        $admin->load();

        self::assertNull($admin->app());
        $admin->boot('admin1');
        self::assertInstanceOf(Application::class, $admin->app());
        self::assertEquals('admin1', $admin->app()->name());

        $admin->boot('admin2');
        self::assertEquals('admin2', $admin->app()->name());
    }

    public function testApps()
    {
        $admin = new PinAdmin();
        self::assertIsArray($admin->apps());
        self::assertCount(0, $admin->apps());
        Factory::new('admin1')->create();
        Factory::new('admin2')->create();
        $admin->load();
        self::assertCount(2, $admin->apps());
        self::assertEquals('admin1', $admin->apps()['admin1']->name());
    }

    public function testApp()
    {
        $admin = new PinAdmin();
        self::assertNull($admin->app());
        Factory::new('admin')->create();
        $admin->load();
        self::assertInstanceOf(Application::class, $admin->boot('admin')->app());
        self::assertEquals('admin', $admin->app()->name());
    }

    public function testCall()
    {
        Factory::new('admin')->create();
        $admin = new PinAdmin();
        $admin->boot('admin');

        self::assertEquals('admin', $admin->name());
    }
}

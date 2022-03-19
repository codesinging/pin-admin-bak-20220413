<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Application;
use CodeSinging\PinAdmin\Foundation\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Factory::clean();
    }

    public function testNew()
    {
        self::assertInstanceOf(Factory::class, Factory::new());
        self::assertInstanceOf(Factory::class, Factory::new('admin'));
    }

    public function testExists()
    {
        Factory::clean();

        self::assertFalse(Factory::exists('admin'));

        Factory::new('admin')->create();

        self::assertTrue(Factory::exists('admin'));
    }

    public function testHas()
    {
        Factory::clean();

        self::assertFalse(Factory::has('admin'));

        Factory::new('admin')->create()->load();

        self::assertTrue(Factory::has('admin'));
    }

    public function testRemove()
    {
        Factory::clean();

        Factory::new('admin')->create()->load();

        self::assertTrue(Factory::exists('admin'));
        self::assertTrue(Factory::has('admin'));

        Factory::remove('admin');

        self::assertFalse(Factory::exists('admin'));
        self::assertFalse(Factory::has('admin'));
    }

    public function testClean()
    {
        Factory::clean();

        Factory::new('admin1')->create()->load();
        Factory::new('admin2')->create()->load();

        self::assertTrue(Factory::exists('admin1'));
        self::assertTrue(Factory::exists('admin2'));
        self::assertTrue(Factory::has('admin1'));
        self::assertTrue(Factory::has('admin2'));

        Factory::clean();

        self::assertFalse(Factory::exists('admin1'));
        self::assertFalse(Factory::exists('admin2'));
        self::assertFalse(Factory::has('admin1'));
        self::assertFalse(Factory::has('admin2'));
    }

    public function testApp()
    {
        self::assertInstanceOf(Application::class, Factory::new('admin')->app());
        self::assertNull(Factory::new()->app());
    }

    public function testReplace()
    {
        Factory::clean();

        $factory = Factory::new('admin_user')->create()->load();

        self::assertEquals('admin', $factory->replace('__DUMMY_LABEL__'));
        self::assertEquals('ADMIN', $factory->replace('__DUMMY_UPPER_LABEL__'));
        self::assertEquals('admin_user', $factory->replace('__DUMMY_NAME__'));
        self::assertEquals('AdminUser', $factory->replace('__DUMMY_STUDLY_NAME__'));
        self::assertEquals('adminUser', $factory->replace('__DUMMY_CAMEL_NAME__'));
        self::assertEquals('ADMIN_USER', $factory->replace('__DUMMY_UPPER_NAME__'));
        self::assertEquals('admin_user', $factory->replace('__DUMMY_GUARD__'));
        self::assertEquals('App\\Admins\\AdminUser', $factory->replace('__DUMMY_NAMESPACE__'));
        self::assertEquals('public/admins/admin_user', $factory->replace('__DUMMY_DIST_PATH__'));
        self::assertEquals('admins/admin_user/resources', $factory->replace('__DUMMY_SRC_PATH__'));
        self::assertEquals('/admin_user', $factory->replace('__DUMMY_BASE_URL__'));
        self::assertEquals('/admin_user', $factory->replace('__DUMMY_HOME_URL__'));
        self::assertEquals('http://localhost/admin_user', $factory->replace('__DUMMY_HOME_URL_WITH_DOMAIN__'));
        self::assertEquals('/admins/admin_user', $factory->replace('__DUMMY_ASSET_URL__'));
    }

    public function testStubPath()
    {
        $factory = Factory::new('admin_user')->create();

        self::assertEquals(Admin::packagePath('stubs'), $factory->stubPath());
        self::assertEquals(Admin::packagePath('stubs/routes/web.php'), $factory->stubPath('routes/web.php'));
        self::assertEquals(Admin::packagePath('stubs/routes/web.php'), $factory->stubPath('routes', 'web.php'));
    }

    public function testMakeDirectories()
    {
        Factory::new('admin')->makeDirectories(Admin::rootPath(), Admin::rootAppPath());

        self::assertDirectoryExists(Admin::rootPath());
        self::assertDirectoryExists(Admin::rootAppPath());
    }

    public function testMakeFile()
    {
        $stub = Admin::packagePath('demo.stub');
        $dest = Admin::rootPath('__DUMMY_NAME__.stub');
        File::put($stub, '__DUMMY_NAME__');

        self::assertFileExists($stub);

        Factory::new('admin')->makeFile($stub, $dest);

        self::assertFileExists(Admin::rootPath('admin.stub'));
        self::assertEquals('admin', File::get(Admin::rootPath('admin.stub')));

        File::delete($stub);
    }

    public function testMakeFiles()
    {
        $stub = Admin::packagePath('tests/stubs');
        File::ensureDirectoryExists($stub);

        $stub1 = Admin::packagePath('tests/stubs/__DUMMY_NAME__1.stub');
        $stub2 = Admin::packagePath('tests/stubs/__DUMMY_NAME__2.stub');

        $dest = Admin::rootPath('stubs');

        File::put($stub1, '__DUMMY_NAME__1');
        File::put($stub2, '__DUMMY_NAME__2');

        self::assertFileExists($stub1);
        self::assertFileExists($stub2);

        Factory::new('admin')->makeFiles($stub, $dest);

        self::assertFileExists(Admin::rootPath('stubs/admin1.stub'));
        self::assertFileExists(Admin::rootPath('stubs/admin2.stub'));
        self::assertEquals('admin1', File::get(Admin::rootPath('stubs/admin1.stub')));
        self::assertEquals('admin2', File::get(Admin::rootPath('stubs/admin2.stub')));

        File::deleteDirectory($stub);
    }

    public function testCreate()
    {
        $app = Factory::new('admin')->create()->boot()->app();

        self::assertDirectoryExists(Admin::rootPath());
        self::assertDirectoryExists(Admin::rootAppPath());
        self::assertDirectoryExists(Admin::rootPublicPath());

        self::assertDirectoryExists($app->path());
        self::assertDirectoryExists($app->appPath());
        self::assertDirectoryExists($app->publicPath());

        self::assertDirectoryExists($app->path('routes'));
        self::assertFileExists($app->path('routes/web.php'));

        self::assertDirectoryExists($app->path('config'));
        self::assertFileExists($app->path('config/app.php'));

        self::assertDirectoryExists($app->path('migrations'));
        self::assertFileExists($app->path('migrations/2022_03_18_000000_create_admin_users_table.php'));

        self::assertDirectoryExists($app->path('resources'));
        self::assertFileExists($app->path('resources/views/public/page.blade.php'));
    }

    public function testLoad()
    {
        Factory::new('admin')->create()->load();

        self::assertTrue(Factory::has('admin'));
    }

    public function testBoot()
    {
        Factory::new('admin')->create()->boot();

        self::assertEquals(Admin::app(), Admin::app('admin'));
        self::assertEquals('admin', Admin::app()->name());
    }
}

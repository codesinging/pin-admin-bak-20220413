<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Application;
use CodeSinging\PinAdmin\Foundation\Factory;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        Factory::clean();
    }

    public function testName()
    {
        self::assertEquals('admin', (new Application('admin'))->name());
        self::assertEquals('admin_users', (new Application('admin'))->name('users'));
        self::assertEquals('admin-config', (new Application('admin'))->name('config', '-'));
    }

    public function testStudlyName()
    {
        self::assertEquals('Admin', (new Application('admin'))->studlyName());
        self::assertEquals('AdminUser', (new Application('admin'))->studlyName('user'));
    }

    public function testGuard()
    {
        self::assertEquals('admin', (new Application('admin'))->guard());
    }

    public function testDirectory()
    {
        self::assertEquals('admins/user', (new Application('user'))->directory());
        self::assertEquals('admins/user/config', (new Application('user'))->directory('config'));
        self::assertEquals('admins/user/config/app.php', (new Application('user'))->directory('config', 'app.php'));
    }

    public function testPath()
    {
        self::assertEquals(base_path('admins/user'), (new Application('user'))->path());
        self::assertEquals(base_path('admins/user/config'), (new Application('user'))->path('config'));
        self::assertEquals(base_path('admins/user/config/app.php'), (new Application('user'))->path('config', 'app.php'));
    }

    public function testAppDirectory()
    {
        self::assertEquals('Admins/Admin', (new Application('admin'))->appDirectory());
        self::assertEquals('Admins/Admin/Controllers', (new Application('admin'))->appDirectory('Controllers'));
        self::assertEquals('Admins/Admin/Controllers/Controller.php', (new Application('admin'))->appDirectory('Controllers', 'Controller.php'));
    }

    public function testAppPath()
    {
        self::assertEquals(app_path('Admins/Admin'), (new Application('admin'))->appPath());
        self::assertEquals(app_path('Admins/Admin/Controllers'), (new Application('admin'))->appPath('Controllers'));
        self::assertEquals(app_path('Admins/Admin/Controllers/Controller.php'), (new Application('admin'))->appPath('Controllers', 'Controller.php'));
    }

    public function testPublicDirectory()
    {
        self::assertEquals('public/admins/admin', (new Application('admin'))->publicDirectory());
        self::assertEquals('public/admins/admin/js', (new Application('admin'))->publicDirectory('js'));
        self::assertEquals('public/admins/admin/js/app.js', (new Application('admin'))->publicDirectory('js', 'app.js'));
    }

    public function testPublicPath()
    {
        self::assertEquals(public_path('admins/admin'), (new Application('admin'))->publicPath());
        self::assertEquals(public_path('admins/admin/js'), (new Application('admin'))->publicPath('js'));
        self::assertEquals(public_path('admins/admin/js/app.js'), (new Application('admin'))->publicPath('js', 'app.js'));
    }

    public function testGetNamespace()
    {
        self::assertEquals('App\\Admins\\Admin', (new Application('admin'))->getNamespace());
        self::assertEquals('App\\Admins\\Admin\\Controllers', (new Application('admin'))->getNamespace('Controllers'));
    }

    public function testConfig()
    {
        $app = new Application('admin');

        $app->config(['title' => 'Title']);

        self::assertInstanceOf(Repository::class, $app->config());
        self::assertIsArray($app->config()->all());
        self::assertEquals('Title', $app->config('title'));
        self::assertNull($app->config('key_not_exists'));
        self::assertEquals('Default', $app->config('key_not_exists', 'Default'));
    }

    public function testRoutePrefix()
    {
        $app = new Application('admin');

        self::assertEquals('admin', $app->routePrefix());

        $app->config(['route_prefix' => 'admin123']);
        self::assertEquals('admin123', $app->routePrefix());
    }

    public function testUrl()
    {
        self::assertEquals(url('admin'), (new Application('admin'))->url());
        self::assertEquals(url('admin/home'), (new Application('admin'))->url('home'));
    }

    public function testLink()
    {
        self::assertEquals('/admin', (new Application('admin'))->link());
        self::assertEquals('/admin/home', (new Application('admin'))->link('home'));
        self::assertEquals('/admin/home?id=1', (new Application('admin'))->link('home', ['id' => 1]));
    }

    public function testAsset()
    {
        self::assertEquals('/admins/admin', (new Application('admin'))->asset());
        self::assertEquals('/admins/admin/js/app.js', (new Application('admin'))->asset('js/app.js'));
    }

    /**
     * @throws Exception
     */
    public function testMix()
    {
        $application = new Application('admin');

        File::ensureDirectoryExists($application->publicPath(), 0755, true);
        File::put($application->publicPath('mix-manifest.json'), '{"/js/app.js":"/js/app.js"}');

        self::assertEquals('/admins/admin/js/app.js', $application->mix('js/app.js'));

        Factory::clean();
    }

    public function testTemplate()
    {
        self::assertEquals('admin_admin::layout.app', (new Application('admin'))->template('layout.app'));
    }

    public function testCreateApplication()
    {
        Factory::clean();
        Factory::new('admin')->create();
        Factory::new('shop')->create();
        self::assertTrue(Factory::exists('admin'));
        self::assertTrue(Factory::exists('shop'));
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testView()
    {
        self::assertEquals(view('admin_admin::public.page'), (new Application('admin'))->view('public.page'));
        self::assertEquals(view('admin_shop::public.page'), (new Application('shop'))->view('public.page'));
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testPage()
    {
        self::assertEquals(view('admin_admin::public.page', ['path' => 'index']), (new Application('admin'))->page('index'));
        self::assertEquals(view('admin_shop::public.page', ['path' => 'index']), (new Application('shop'))->page('index'));
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testAuth()
    {
        $admin = Admin::app('admin');
        $shop = Admin::app('shop');

        self::assertEquals(Auth::guard($admin->guard()), $admin->auth());
        self::assertEquals(Auth::guard($shop->guard()), $shop->auth());
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testUser()
    {
        $admin = Admin::app('admin');
        $shop = Admin::app('shop');

        self::assertEquals(Auth::guard($admin->guard())->user(), $admin->user());
        self::assertEquals(Auth::guard($shop->guard())->user(), $shop->user());
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testRouteGroup()
    {
        $originRoutes = Route::getRoutes()->getRoutes();

        Admin::boot('admin')->routeGroup(function () {
            Route::get('test123', fn() => 'test')->name('test123');
        });

        $routes = Route::getRoutes()->getRoutes();

        self::assertCount(count($originRoutes) + 1, $routes);

        $route = end($routes);

        self::assertEquals('test123', $route->getName());
        self::assertEquals(Admin::routePrefix(), $route->getPrefix());
        self::assertEquals(Admin::config('middlewares.auth'), $route->getAction('middleware'));
        self::assertEquals('admin/test123', $route->uri());

        Admin::boot('admin')->routeGroup(function () {
            Route::get('test234', fn() => 'test')->name('test234');
        }, false);

        $routes = Route::getRoutes()->getRoutes();

        self::assertCount(count($originRoutes) + 2, $routes);

        $route = end($routes);

        self::assertEquals('test234', $route->getName());
        self::assertEquals(Admin::routePrefix(), $route->getPrefix());
        self::assertEquals(Admin::config('middlewares.guest'), $route->getAction('middleware'));
        self::assertEquals('admin/test234', $route->uri());
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testDefaultRoutes()
    {
        $routes = Route::getRoutes()->getRoutes();

        self::assertGreaterThan(0, count($routes));
        self::assertTrue(Route::getRoutes()->hasNamedRoute(Admin::boot('admin')->name('index', '.')));
        self::assertTrue(Route::getRoutes()->hasNamedRoute(Admin::boot('shop')->name('index', '.')));
        self::assertTrue(Route::getRoutes()->hasNamedRoute(Admin::boot('admin')->name('auth', '.')));
        self::assertTrue(Route::getRoutes()->hasNamedRoute(Admin::boot('shop')->name('auth', '.')));
    }
}

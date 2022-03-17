<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\PinAdmin;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        Factory::clean();
    }

    public function testAdmin()
    {
        self::assertInstanceOf(PinAdmin::class, admin());

        Factory::new('admin')->create();
        Admin::load();
        self::assertEquals('admin', admin('admin')->name());
        Factory::clean();
    }

    public function testAdminConfig()
    {
        Factory::new('admin')->create();
        Admin::load();

        admin('admin')->config(['title' => 'Title']);

        self::assertInstanceOf(Repository::class, admin_config());
        self::assertIsArray(admin_config()->all());
        self::assertEquals('Title', admin_config('title'));
        self::assertNull(admin_config('key_not_exists'));
        self::assertEquals('Default', admin_config('key_not_exists', 'Default'));
        Factory::clean();
    }

    public function testAdminUrl()
    {
        Factory::new('admin')->create();
        Admin::load()->boot('admin');

        self::assertEquals(url('admin'), admin_url());
        self::assertEquals(url('admin/home'), admin_url('home'));

        Factory::clean();
    }

    public function testAdminLink()
    {
        Factory::new('admin')->create();
        Admin::load()->boot('admin');

        self::assertEquals('/admin', admin_link());
        self::assertEquals('/admin/home', admin_link('home'));
        self::assertEquals('/admin/home?id=1', admin_link('home', ['id' => 1]));

        Factory::clean();
    }

    public function testAdminAsset()
    {
        Factory::new('admin')->create();
        Admin::load()->boot('admin');

        self::assertEquals('/static/app.js', admin_asset('/static/app.js'));
        self::assertEquals('/admins/admin', admin_asset());
        self::assertEquals('/admins/admin/js/app.js', admin_asset('js/app.js'));

        Factory::clean();
    }

    /**
     * @throws Exception
     */
    public function testAdminMix()
    {
        Factory::new('admin')->create();
        Admin::load()->boot('admin');

        File::ensureDirectoryExists(admin()->publicPath(), 0755, true);
        File::put(admin()->publicPath('mix-manifest.json'), '{"/js/app.js":"/js/app.js"}');

        self::assertEquals('/admins/admin/js/app.js', admin_mix('js/app.js'));

        File::deleteDirectory(Admin::rootPublicPath());

        Factory::clean();
    }

    /**
     * @throws Exception
     */
    public function testAdminTemplate()
    {
        Factory::new('admin')->create();
        Admin::load()->boot('admin');

        self::assertEquals('admin_admin::layout.app', admin_template('layout.app'));

        Factory::clean();
    }

    public function testCreateApplication()
    {
        Factory::new('admin')->create();
        Admin::load()->boot('admin');
        self::assertTrue(Factory::exists('admin'));
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testAdminView()
    {
        self::assertEquals(view('admin_admin::public.page'), admin_view('public.page'));
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testAdminPage()
    {
        self::assertEquals(view('admin_admin::public.page', ['path' => 'index']), admin_page('index'));
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testAdminAuth()
    {
        self::assertEquals(Auth::guard(admin()->guard()), admin_auth());
    }

    /**
     * @depends testCreateApplication
     * @return void
     */
    public function testAdminUser()
    {
        self::assertEquals(Auth::guard(admin()->guard())->user(), admin_user());
    }

    public function testSuccess()
    {
        self::assertInstanceOf(JsonResponse::class, success());
        self::assertEquals(200, success()->status());
        self::assertEquals('message', success('message')->getData(true)['message']);
        self::assertEquals(0, success('message')->getData(true)['code']);
        self::assertEquals(['id' => 1], success(['id' => 1])->getData(true)['data']);
    }

    public function testError()
    {
        self::assertInstanceOf(JsonResponse::class, error());
        self::assertEquals(200, error()->status());
        self::assertEquals('message', error('message')->getData(true)['message']);
        self::assertEquals(-1, error('message')->getData(true)['code']);
        self::assertEquals(10010, error('message', 10010)->getData(true)['code']);
    }
}

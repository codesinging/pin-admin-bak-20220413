<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Controllers;

use CodeSinging\PinAdmin\Exceptions\AdminError;
use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ActingAs;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use ActingAs;

    public static function tearDownAfterClass(): void
    {
        Factory::clean();
    }

    public function testCreate()
    {
        Factory::new('admin')->create()->boot();
        self::assertTrue(Factory::has('admin'));
    }

    /**
     * @depends testCreate
     * @return void
     */
    public function testLogin()
    {
        $data = [
            'username' => 'admin',
            'password' => 'admin.123'
        ];

        User::new()->create($data);

        $this->postJson('admin/auth/login', $data)
            ->assertJsonPath('code', 0)
            ->assertOk();

        $this->postJson('admin/auth/login', [])
            ->assertJsonStructure(['errors' => ['username', 'password']])
            ->assertStatus(422);

        $this->postJson('admin/auth/login', ['username' => 'admin'])
            ->assertJsonStructure(['errors' => ['password']])
            ->assertStatus(422);

        $this->postJson('admin/auth/login', ['username' => 'admin', 'password' => 'admin'])
            ->assertJsonPath('code', AdminError::AUTH__NAME_AND_PASSWORD_NOT_MATCHED)
            ->assertOk();

        User::new()->where('username', 'admin')->update(['status' => false]);

        $this->postJson('admin/auth/login', $data)
            ->assertJsonPath('code', AdminError::AUTH__USER_STATUS_ERROR)
            ->assertOk();
    }

    /**
     * @depends testCreate
     * @return void
     */
    public function testLogout()
    {
        $this->actingAsUser('admin')
            ->postJson('admin/auth/logout')
            ->assertJsonPath('code', 0)
            ->assertOk();
    }

    /**
     * @depends testCreate
     * @return void
     */
    public function testUser()
    {
        $this->actingAsUser('admin')
            ->getJson('admin/auth/user')
            ->assertJsonPath('data.username', 'admin')
            ->assertJsonPath('code', 0)
            ->assertOk();
    }
}

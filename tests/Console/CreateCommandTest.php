<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Console;

use CodeSinging\PinAdmin\Console\CreateCommand;
use CodeSinging\PinAdmin\Foundation\Factory;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Factory::clean();
    }

    public function testCreate()
    {
        $this->artisan('admin:create admin')
            ->expectsOutputToContain('创建 PinAdmin 应用')
            ->assertSuccessful();

        self::assertTrue(Factory::exists('admin'));
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
}

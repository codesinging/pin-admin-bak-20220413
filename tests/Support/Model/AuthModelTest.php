<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Model;

use CodeSinging\PinAdmin\Support\Model\AuthModel;
use CodeSinging\PinAdmin\Support\Model\HelperTraits;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Tests\TestCase;

class AuthModelTest extends TestCase
{
    public function testExtends()
    {
        self::assertInstanceOf(User::class, new AuthModel());
    }

    public function testTraits()
    {
        self::assertArrayHasKey(HelperTraits::class, class_uses(AuthModel::class));
        self::assertArrayHasKey(Notifiable::class, class_uses(AuthModel::class));
    }
}

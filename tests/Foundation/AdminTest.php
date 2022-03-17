<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Foundation;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Foundation\PinAdmin;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function testVersion()
    {
        self::assertEquals(PinAdmin::VERSION, Admin::version());
    }
}

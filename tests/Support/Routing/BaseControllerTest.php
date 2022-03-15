<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Routing;

use CodeSinging\PinAdmin\Support\Routing\BaseController;
use Illuminate\Routing\Controller;
use Tests\TestCase;

class BaseControllerTest extends TestCase
{
    public function testExtend()
    {
        self::assertInstanceOf(Controller::class, new BaseController());
    }

    public function testUseTraits()
    {
        self::assertTrue(method_exists(new BaseController(), 'authorize'));
        self::assertTrue(method_exists(new BaseController(), 'dispatch'));
        self::assertTrue(method_exists(new BaseController(), 'validate'));
        self::assertTrue(method_exists(new BaseController(), 'success'));
        self::assertTrue(method_exists(new BaseController(), 'error'));
    }
}

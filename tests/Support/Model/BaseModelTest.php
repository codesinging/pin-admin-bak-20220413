<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Model;

use CodeSinging\PinAdmin\Support\Model\BaseModel;
use CodeSinging\PinAdmin\Support\Model\HelperTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseModelTest extends TestCase
{
    public function testExtends()
    {
        self::assertInstanceOf(Model::class, new BaseModel());
    }

    public function testTraits()
    {
        self::assertArrayHasKey(HelperTraits::class, class_uses(BaseModel::class));
    }
}

<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Model;

use CodeSinging\PinAdmin\Support\Model\BaseModel;
use Tests\TestCase;

class NewInstanceTest extends TestCase
{
    public function testNewInstance()
    {
        self::assertInstanceOf(BaseModel::class, BaseModel::new());
    }
}

<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests\Support\Model;

use CodeSinging\PinAdmin\Support\Model\HelperTraits;
use CodeSinging\PinAdmin\Support\Model\Lister;
use CodeSinging\PinAdmin\Support\Model\NewInstance;
use CodeSinging\PinAdmin\Support\Model\Sanitize;
use CodeSinging\PinAdmin\Support\Model\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tests\TestCase;

class HelperTraitsTest extends TestCase
{
    public function testTraits()
    {
        self::assertArrayHasKey(NewInstance::class, class_uses(HelperTraits::class));
        self::assertArrayHasKey(SerializeDate::class, class_uses(HelperTraits::class));
        self::assertArrayHasKey(Sanitize::class, class_uses(HelperTraits::class));
        self::assertArrayHasKey(Lister::class, class_uses(HelperTraits::class));
        self::assertArrayHasKey(HasFactory::class, class_uses(HelperTraits::class));
    }
}

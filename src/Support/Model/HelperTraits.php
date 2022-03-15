<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
trait HelperTraits
{
    use NewInstance;
    use SerializeDate;
    use Sanitize;
    use Lister;

    use HasFactory;
}

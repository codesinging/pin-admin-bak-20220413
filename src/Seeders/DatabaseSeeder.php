<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected array $seeders = [
        UserSeeder::class,
    ];

    public function run()
    {
        $this->callSilent($this->seeders);
    }
}

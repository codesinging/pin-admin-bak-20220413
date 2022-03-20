<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Seeders;

use CodeSinging\PinAdmin\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::new()->truncate();

        User::new()->create([
            'username' => 'admin',
            'password' => 'admin.123',
        ]);
    }
}

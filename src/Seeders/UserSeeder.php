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

        $user = User::new()->create([
            'name' => 'admin',
            'password' => 'admin.123',
        ]);
    }
}

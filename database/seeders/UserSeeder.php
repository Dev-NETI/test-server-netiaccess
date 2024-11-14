<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id = 335;
        $user = [
            'f_name' => 'Mollee',
            'm_name' => 'Mystic',
            'l_name' => 'Francisco',
            'email' => 'sherwin.roxas@neti.com.ph',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            // 'hash_id' => Crypt::encrypt($id),
            'is_active' => true
        ];

        $user = User::create($user);
    }
}

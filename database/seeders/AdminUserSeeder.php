<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'              => 'administrator',
            'email'             => 'admin@gmail.com',
            'email_verified_at' => date('Y-m-d H:i:s', time()),
            'password'          => Hash::make('password123'),
            'is_admin'          => true,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User([
            'firstname' => 'john kevin',
            'lastname' => 'paunel',
            'email' => 'johnkevinpaunel@gmail.com',
            'username' => 'kevinpauneljohn',
            'password' => 123
        ]);

        $user->assignRole('super admin');
        $user->save();

    }
}

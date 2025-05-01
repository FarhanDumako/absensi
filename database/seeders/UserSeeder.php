<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat pengguna admin jika belum ada
        User::firstOrCreate(
            ['email' => 'farhan@gmail.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('123456789'), // Gantilah dengan password yang sesuai
                
            ]
        )->assignRole('admin');



    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DataAwal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@kasir.com';
        $user->password = bcrypt('12345678');
        $user->peran = 'admin';
        $user->save();

    }
}

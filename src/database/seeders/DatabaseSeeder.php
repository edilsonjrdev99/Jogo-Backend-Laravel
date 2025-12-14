<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('123456admin'),
            'is_admin' => true,
        ]);

        // Teste
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@email.com',
            'password' => bcrypt('123456teste'),
            'is_admin' => false,
        ]);
    }
}

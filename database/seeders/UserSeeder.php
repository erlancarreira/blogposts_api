<?php

namespace Database\Seeders;

use App\Models\User;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nome'     => 'João Silva',
            'email'    => 'joao.silva@example.com',
            'password' => Hash::make('senha123'),
            'telefone' => '(11) 98765-4321',
            'is_valid' => true,
        ]);

        User::create([
            'nome'     => 'Maria Oliveira',
            'email'    => 'maria.oliveira@example.com',
            'password' => Hash::make('senha123'),
            'telefone' => '(21) 91234-5678',
            'is_valid' => false,
        ]);

        User::create([
            'nome'     => 'Pedro Souza',
            'email'    => 'pedro.souza@example.com',
            'password' => Hash::make('senha123'),
            'telefone' => '(31) 99876-5432',
            'is_valid' => true,
        ]);

        User::factory(7)->create();
    }
}

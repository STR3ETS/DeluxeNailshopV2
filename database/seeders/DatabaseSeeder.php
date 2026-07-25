<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@deluxenailshop.nl'],
            [
                'name'     => 'Beheerder',
                'password' => 'wachtwoord123',
                'role'     => 'admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'klant@deluxenailshop.nl'],
            [
                'name'     => 'Test Klant',
                'password' => 'wachtwoord123',
                'role'     => 'klant',
            ],
        );

        $this->call(ProductSeeder::class);
    }
}

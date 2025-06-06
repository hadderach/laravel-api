<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        Ticket::factory(100)->recycle($users)->create();


        User::factory()->create([
            'name' => 'Test Manager',
            'email' => 'manager@manager.com',
            'password' => bcrypt('password'),
            'is_manager' => true
        ]);
    }
}

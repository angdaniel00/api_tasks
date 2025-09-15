<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create([
            'name' => 'Equipo 1',
            'removed' => false
        ]);

        Team::create([
            'name' => 'Equipo 2',
            'removed' => false
        ]);

        User::create([
            'name' => 'Admin System',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('.123456.'),
            'is_superuser' => true,
            'removed' => false,
            'team_id' => 1
        ]);

        User::create([
            'name' => 'Usuario System',
            'email' => 'user@gmail.com',
            'password' => bcrypt('.123456.'),
            'is_superuser' => false,
            'removed' => false,
            'team_id' => 1
        ]);

        User::create([
            'name' => 'Usuario System 2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('.123456.'),
            'is_superuser' => false,
            'removed' => false,
            'team_id' => 1
        ]);

        User::create([
            'name' => 'Usuario System 3',
            'email' => 'user3@gmail.com',
            'password' => bcrypt('.123456.'),
            'is_superuser' => false,
            'removed' => false,
            'team_id' => 2
        ]);

        User::create([
            'name' => 'Usuario System 4',
            'email' => 'user4@gmail.com',
            'password' => bcrypt('.123456.'),
            'is_superuser' => false,
            'removed' => false,
            'team_id' => 2
        ]);

        User::create([
            'name' => 'Usuario System 5',
            'email' => 'user5@gmail.com',
            'password' => bcrypt('.123456.'),
            'is_superuser' => false,
            'removed' => false,
            'team_id' => 2
        ]);
    }
}

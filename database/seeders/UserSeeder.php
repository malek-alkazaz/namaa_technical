<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 test Admins
        User::factory()
            ->count(3)
            ->state(function () {
                return [
                    'type_id' => Type::where('type','admin')->first()->id,
                    'role_id' => Role::where('name','admin')->first()->id,
                ];
            })
            ->create();
        // Create 7 test Users
        User::factory()
            ->count(7)
            ->state(function () {
                return [
                    'type_id' => Type::where('type','user')->first()->id,
                    'role_id' => Role::where('name','user')->first()->id,
                ];
            })
            ->create();

    }
}

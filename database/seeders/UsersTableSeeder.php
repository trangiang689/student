<?php

namespace Database\Seeders;

use App\Models\Students;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(100)->has(Students::factory()->count(1)->state(function (array $attributers, User $user){
            return ['full_name' => $user->name];
        }), 'student')->create();
    }
}

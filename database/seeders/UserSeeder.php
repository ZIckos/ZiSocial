<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'pseudo' => 'zick',
            'password'=> Hash::make('C02fd541!'),
            'email'=> 'tzickos@gmail.com',
            'remember_token' => Str::random(10),
            'role_id' => 2
        ]);

        User::factory()->count(rand(5, 18))->create();
    }
}

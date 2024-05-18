<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Super Admin',
        //     'email' => 'admin@gmail.com',
        //     'role' => 'ADMIN',
        //     'password' => Hash::make('admin123'),
        // ]);

        User::factory()->create([
            'name' => 'Abghi Fareihan',
            'email' => 'abghi@gmail.com',
            'role' => 'USER',
            'password' => Hash::make('abghi123'),
        ]);

        User::factory()->create([
            'name' => 'Agoy Elkontolero',
            'email' => 'agoy@gmail.com',
            'role' => 'USER',
            'password' => Hash::make('agoy123'),
        ]);

        User::factory(8)->create();
        Product::factory(20)->create();
        ProductGallery::factory(60)->create();
        Address::factory(30)->create();
    }
}

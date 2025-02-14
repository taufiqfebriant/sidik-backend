<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    DB::table('roles')->insert([
      ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'User', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $adminRole = DB::table('roles')->where('name', 'Admin')->first();

    DB::table('users')->insert([
      'name' => 'Admin User',
      'email' => 'admin@example.com',
      'password' => Hash::make('12345678'),
      'role_id' => $adminRole->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    Product::create([
      'name' => 'Dummy Product',
      'description' => 'This is a dummy product for testing.',
      'price' => 100.00,
      'image' => null,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}

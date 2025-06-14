<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            DepartmentPositionSeeder::class,
            EmployeeSeeder::class
        ]);

        $positions = Position::pluck('id')->toArray();

        User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => bcrypt('admin')
        ]);

        Employee::firstOrCreate([
            'name' => 'Ahmad Rivaldi',
            'phone' => '081285878276',
            'email' => 'rivaldi19122@gmail.com',
            'code' => fake()->numerify('##########'),
            'gender' => 'male',
            'position_id' => fake()->randomElement($positions),
            'join_date' => now()->subYears(fake()->randomElement([1, 2, 3, 4, 5, 6])),
            'status' => 'active',
            'base_salary' => 300000,
        ]);

        Employee::firstOrCreate([
            'name' => 'Abdul Rasyid Wicaksono',
            'phone' => null,
            'email' => null,
            'code' => fake()->numerify('##########'),
            'gender' => 'male',
            'position_id' => fake()->randomElement($positions),
            'join_date' => now()->subYears(fake()->randomElement([1, 2, 3, 4, 5, 6])),
            'status' => 'active',
            'base_salary' => 300000,
        ]);

        Employee::firstOrCreate([
            'name' => 'Vera Yunita',
            'phone' => null,
            'email' => null,
            'code' => fake()->numerify('##########'),
            'gender' => 'female',
            'position_id' => fake()->randomElement($positions),
            'join_date' => now()->subYears(fake()->randomElement([1, 2, 3, 4, 5, 6])),
            'status' => 'active',
            'base_salary' => 300000,
        ]);
    }
}

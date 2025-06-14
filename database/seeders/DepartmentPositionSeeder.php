<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Finance',
            'Human Resources',
            'Engineering',
            'IT',
            'Marketing',
            'Sales',
            'Customer Support',
        ];


        $positions = [
            'Finance' => [
                'Accountant',
                'Financial Analyst',
                'Finance Manager',
            ],
            'Human Resources' => [
                'HR Manager',
                'Recruiter',
                'HR Assistant',
            ],
            'Engineering' => [
                'Software Engineer',
                'Web Developer',
                'Mobile Developer',
                'QA Engineer',
                'DevOps Engineer',
                'Backend Developer',
                'Frontend Developer',
            ],
            'IT' => [
                'IT Support',
                'System Administrator',
                'Network Engineer',
                'IT Security Specialist',
                'Database Administrator',
            ],
            'Marketing' => [
                'Marketing Manager',
                'Content Writer',
                'SEO Specialist',
                'Digital Marketer',
                'Social Media Manager',
            ],
            'Sales' => [
                'Sales Executive',
                'Sales Manager',
                'Account Manager',
                'Business Development',
            ],
            'Customer Support' => [
                'Customer Support Agent',
                'Support Manager',
                'Technical Support',
            ],
        ];


        foreach ($positions as $departmentName => $positionNames) {
            $department = Department::firstOrCreate(['name' => $departmentName]);

            foreach ($positionNames as $positionName) {
                Position::firstOrCreate([
                    'name' => $positionName,
                    'department_id' => $department->id,
                ]);
            }
        }
    }
}

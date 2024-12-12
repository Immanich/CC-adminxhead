<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Office;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = Office::all();

        $employees = [
            'Accounting Office' => [
                ['name' => 'John Doe', 'position' => 'Accountant', 'image' => 'images/employees/john_doe.jpg'],
                ['name' => 'Jane Smith', 'position' => 'Accounting Clerk', 'image' => 'images/employees/jane_smith.jpg'],
            ],
            "Assessor's Office" => [
                ['name' => 'Michael Brown', 'position' => 'Assessor', 'image' => 'images/employees/michael_brown.jpg'],
                ['name' => 'Emily Davis', 'position' => 'Clerk', 'image' => 'images/employees/emily_davis.jpg'],
            ],
            'Business Permits & Licensing Office' => [
                ['name' => 'Sarah Johnson', 'position' => 'Permit Specialist', 'image' => 'images/employees/sarah_johnson.jpg'],
            ],
            'Engineering Office' => [
                ['name' => 'James Wilson', 'position' => 'Engineer', 'image' => 'images/employees/james_wilson.jpg'],
                ['name' => 'Olivia Martinez', 'position' => 'Technician', 'image' => 'images/employees/olivia_martinez.jpg'],
            ],
            'Human Resource & Management Office' => [
                ['name' => 'Sophia Garcia', 'position' => 'HR Manager', 'image' => 'images/employees/sophia_garcia.jpg'],
            ],
            "Mayor's Office" => [
                ['name' => 'Robert Miller', 'position' => 'Executive Assistant', 'image' => 'images/employees/robert_miller.jpg'],
            ],
            'MDRMMO' => [
                ['name' => 'David Lee', 'position' => 'Disaster Officer', 'image' => 'images/employees/david_lee.jpg'],
            ],
            // Add employees for other offices similarly...
        ];

        foreach ($offices as $office) {
            if (isset($employees[$office->office_name])) {
                foreach ($employees[$office->office_name] as $employeeData) {
                    Employee::create(array_merge($employeeData, ['office_id' => $office->id]));
                }
            }
        }
    }
}

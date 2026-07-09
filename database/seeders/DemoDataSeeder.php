<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Holiday::create([
            'holiday_date' => '2026-07-03',
            'title' => 'Founders Day'
        ]);

       
        $emp1 = Employee::create([
            'employee_code' => 'EMP-001',
            'name' => 'Alice Perfect',
            'department_id' => 1,
            'joining_date' => '2026-07-01',
            'status' => 'Active'
        ]);

        $dates1 = ['2026-07-01', '2026-07-02', '2026-07-03', '2026-07-06', '2026-07-07', '2026-07-08'];
        foreach ($dates1 as $date) {
            Attendance::create(['employee_id' => $emp1->id, 'attendance_date' => $date, 'status' => 'Present']);
        }


        $emp2 = Employee::create([
            'employee_code' => 'EMP-002',
            'name' => 'Bob Smith',
            'department_id' => 1,
            'joining_date' => '2026-07-01',
            'status' => 'Active'
        ]);

        $dates2 = ['2026-07-01', '2026-07-02', '2026-07-06', '2026-07-07', '2026-07-08'];
        foreach ($dates2 as $date) {
            Attendance::create(['employee_id' => $emp2->id, 'attendance_date' => $date, 'status' => 'Present']);
        }


        $emp3 = Employee::create([
            'employee_code' => 'EMP-003',
            'name' => 'Charlie Brown',
            'department_id' => 2,
            'joining_date' => '2026-07-01',
            'status' => 'Active'
        ]);

        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-07-01', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-07-02', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-07-03', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-07-06', 'status' => 'Leave']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-07-07', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-07-08', 'status' => 'Present']);

      
        $emp4 = Employee::create([
            'employee_code' => 'EMP-004',
            'name' => 'David Lee',
            'department_id' => 2,
            'joining_date' => '2026-07-01',
            'status' => 'Active'
        ]);

        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-07-01', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-07-02', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-07-03', 'status' => 'Present']);
        // NO RECORD FOR JUL 6 (Monday) - treated as Absent!
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-07-07', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-07-08', 'status' => 'Present']);

       
        $emp5 = Employee::create([
            'employee_code' => 'EMP-005',
            'name' => 'Eve Wilson',
            'department_id' => 3,
            'joining_date' => '2026-07-01',
            'status' => 'Inactive'
        ]);

        $dates5 = ['2026-07-01', '2026-07-02', '2026-07-03', '2026-07-06', '2026-07-07', '2026-07-08'];
        foreach ($dates5 as $date) {
            Attendance::create(['employee_id' => $emp5->id, 'attendance_date' => $date, 'status' => 'Present']);
        }

      
        $emp6 = Employee::create([
            'employee_code' => 'EMP-006',
            'name' => 'Frank Davis',
            'department_id' => 3,
            'joining_date' => '2026-07-01',
            'status' => 'Active'
        ]);

        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-07-01', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-07-02', 'status' => 'Half Day']);
        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-07-03', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-07-06', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-07-07', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-07-08', 'status' => 'Present']);

        
        $emp7 = Employee::create([
            'employee_code' => 'EMP-007',
            'name' => 'Grace Miller',
            'department_id' => 1,
            'joining_date' => '2026-07-01',
            'status' => 'Active'
        ]);

        Attendance::create(['employee_id' => $emp7->id, 'attendance_date' => '2026-07-01', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp7->id, 'attendance_date' => '2026-07-02', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp7->id, 'attendance_date' => '2026-07-03', 'status' => 'Absent']);
        Attendance::create(['employee_id' => $emp7->id, 'attendance_date' => '2026-07-06', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp7->id, 'attendance_date' => '2026-07-07', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp7->id, 'attendance_date' => '2026-07-08', 'status' => 'Present']);
    }
}

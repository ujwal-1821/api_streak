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
       
        $baseDate = Carbon::parse('2026-06-01');
        $holidayDate = $baseDate->copy()->addDays(9); 

        Holiday::create([
            'holiday_date' => $holidayDate->format('Y-m-d'),
            'title' => 'Mid-Summer Festival'
        ]);

      
        $emp1 = Employee::create([
            'employee_code' => 'EMP-001',
            'name' => 'Alice Ideal',
            'department_id' => 1,
            'joining_date' => '2026-06-01',
            'status' => 'Active'
        ]);

        $dates1 = ['2026-06-01', '2026-06-02', '2026-06-03', '2026-06-04', '2026-06-05', '2026-06-08'];
        foreach ($dates1 as $date) {
            Attendance::create(['employee_id' => $emp1->id, 'attendance_date' => $date, 'status' => 'Present']);
        }


        
        $emp2 = Employee::create([
            'employee_code' => 'EMP-002',
            'name' => 'Bob Holiday',
            'department_id' => 1,
            'joining_date' => '2026-06-08',
            'status' => 'Active'
        ]);

        $dates2 = ['2026-06-08', '2026-06-09', '2026-06-11', '2026-06-12', '2026-06-15'];
        foreach ($dates2 as $date) {
            Attendance::create(['employee_id' => $emp2->id, 'attendance_date' => $date, 'status' => 'Present']);
        }


      
        $emp3 = Employee::create([
            'employee_code' => 'EMP-003',
            'name' => 'Charlie Broken',
            'department_id' => 2,
            'joining_date' => '2026-06-01',
            'status' => 'Active'
        ]);

        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-06-01', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-06-02', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-06-03', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-06-04', 'status' => 'Leave']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-06-05', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-06-08', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp3->id, 'attendance_date' => '2026-06-09', 'status' => 'Present']);


       
        $emp4 = Employee::create([
            'employee_code' => 'EMP-004',
            'name' => 'David Missing',
            'department_id' => 2,
            'joining_date' => '2026-06-01',
            'status' => 'Active'
        ]);

        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-01', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-02', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-03', 'status' => 'Present']);
        
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-05', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-08', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-09', 'status' => 'Present']);


       
        $emp5 = Employee::create([
            'employee_code' => 'EMP-005',
            'name' => 'Eve Inactive',
            'department_id' => 3,
            'joining_date' => '2026-06-01',
            'status' => 'Inactive'
        ]);

        for ($i = 0; $i < 10; $i++) {
            $date = Carbon::parse('2026-06-01')->addWeekdays($i)->format('Y-m-d');
            Attendance::create(['employee_id' => $emp5->id, 'attendance_date' => $date, 'status' => 'Present']);
        }



        $emp6 = Employee::create([
            'employee_code' => 'EMP-006',
            'name' => 'Frank Multi',
            'department_id' => 3,
            'joining_date' => '2026-06-01',
            'status' => 'Active'
        ]);

        
        $dates6_1 = ['2026-06-01', '2026-06-02', '2026-06-03', '2026-06-04', '2026-06-05'];
        foreach ($dates6_1 as $date) {
            Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => $date, 'status' => 'Present']);
        }
        
        
        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-06-08', 'status' => 'Absent']);


        $dates6_2 = ['2026-06-09', '2026-06-11', '2026-06-12', '2026-06-15', '2026-06-16']; 
            Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => $date, 'status' => 'Present']);
        }
    }
}

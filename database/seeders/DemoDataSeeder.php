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
        // 1. Setup a Public Holiday
        $baseDate = Carbon::parse('2026-06-01'); // A Monday
        $holidayDate = $baseDate->copy()->addDays(9); // June 10, 2026 (Wednesday)

        Holiday::create([
            'holiday_date' => $holidayDate->format('Y-m-d'),
            'title' => 'Mid-Summer Festival'
        ]);

        // Employee 1: The Ideal Case (Qualifies, 6 days)
        // Present: June 1, 2, 3, 4, 5 (Fri), 8 (Mon)
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


        // Employee 2: The Holiday Case (Qualifies, 5 days spanning over holiday)
        // Present: June 8 (Mon), June 9 (Tue), June 10 is Holiday, June 11 (Thu), June 12 (Fri), June 15 (Mon)
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


        // Employee 3: The Broken Streak Case (Does NOT Qualify)
        // Present: June 1, 2, 3. Leave: June 4. Present: June 5, 8, 9
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


        // Employee 4: The Missing Attendance Case (Does NOT Qualify)
        // Present: June 1, 2, 3. Missing: June 4. Present: June 5, 8, 9
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
        // Missing June 4 entirely
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-05', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-08', 'status' => 'Present']);
        Attendance::create(['employee_id' => $emp4->id, 'attendance_date' => '2026-06-09', 'status' => 'Present']);


        // Employee 5: Inactive Employee (Does NOT Qualify)
        // Present for 10 days, but inactive
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


        // Employee 6: Multiple Streaks (Qualifies)
        // Week 1: Present 5 days. Week 2: Mon absent. Week 2: Tue-Fri (4 days). Week 3: Mon (1 day) -> Total 5
        $emp6 = Employee::create([
            'employee_code' => 'EMP-006',
            'name' => 'Frank Multi',
            'department_id' => 3,
            'joining_date' => '2026-06-01',
            'status' => 'Active'
        ]);

        // Streak 1
        $dates6_1 = ['2026-06-01', '2026-06-02', '2026-06-03', '2026-06-04', '2026-06-05'];
        foreach ($dates6_1 as $date) {
            Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => $date, 'status' => 'Present']);
        }
        
        // Broken
        Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => '2026-06-08', 'status' => 'Absent']);

        // Streak 2 (4 days + 1 day = 5 days total)
        $dates6_2 = ['2026-06-09', '2026-06-11', '2026-06-12', '2026-06-15', '2026-06-16']; // skipping holiday on 10th
        foreach ($dates6_2 as $date) {
            Attendance::create(['employee_id' => $emp6->id, 'attendance_date' => $date, 'status' => 'Present']);
        }
    }
}

<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;

class AttendanceStreakService
{
    /**
     * Get employees who have maintained a minimum attendance streak.
     *
     * @param int $minStreak
     * @return \Illuminate\Support\Collection
     */
    public function getEmployeesWithMinStreak(int $minStreak = 5)
    {
       
        $holidays = Holiday::pluck('holiday_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

      
        $employees = Employee::where('status', 'Active')
            ->with('attendances')
            ->get();

        $qualifyingEmployees = collect();

        foreach ($employees as $employee) {
            $longestStreak = 0;
            $currentStreak = 0;

            
            $attendancesByDate = $employee->attendances->keyBy(function ($attendance) {
                return $attendance->attendance_date->format('Y-m-d');
            });

            
            $startDate = $employee->joining_date->copy();
            
            
            $endDate = Carbon::today();

           
            if ($startDate->gt($endDate)) {
                continue;
            }

           
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
               
                if ($date->isWeekend()) {
                    continue;
                }

                $dateString = $date->format('Y-m-d');

               
                if (in_array($dateString, $holidays)) {
                    continue;
                }

               
                if ($attendancesByDate->has($dateString)) {
                    $attendance = $attendancesByDate->get($dateString);

                    if ($attendance->status === 'Present') {
                        $currentStreak++;
                        if ($currentStreak > $longestStreak) {
                            $longestStreak = $currentStreak;
                        }
                    } else {
                       
                        $currentStreak = 0;
                    }
                } else {
                  
                    $currentStreak = 0;
                }
            }

           
            if ($longestStreak >= $minStreak) {
               
                $employee->longest_streak = $longestStreak;
                $employee->current_streak = $currentStreak;
                
                $qualifyingEmployees->push($employee);
            }
        }

        return $qualifyingEmployees;
    }
}

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
        // 1. Fetch holidays as a fast lookup array of 'Y-m-d' strings
        $holidays = Holiday::pluck('holiday_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        // 2. Fetch only active employees with their attendances
        $employees = Employee::where('status', 'Active')
            ->with('attendances')
            ->get();

        $qualifyingEmployees = collect();

        foreach ($employees as $employee) {
            $longestStreak = 0;
            $currentStreak = 0;

            // Key attendances by date for O(1) lookup
            $attendancesByDate = $employee->attendances->keyBy(function ($attendance) {
                return $attendance->attendance_date->format('Y-m-d');
            });

            // Start date is employee joining date
            $startDate = $employee->joining_date->copy();
            
            // End date is today (or we could use the latest attendance date, but today is standard)
            $endDate = Carbon::today();

            // If joining date is in the future, skip
            if ($startDate->gt($endDate)) {
                continue;
            }

            // Iterate day by day
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // Ignore Weekends
                if ($date->isWeekend()) {
                    continue;
                }

                $dateString = $date->format('Y-m-d');

                // Ignore Public Holidays
                if (in_array($dateString, $holidays)) {
                    continue;
                }

                // Check attendance record
                if ($attendancesByDate->has($dateString)) {
                    $attendance = $attendancesByDate->get($dateString);

                    if ($attendance->status === 'Present') {
                        $currentStreak++;
                        if ($currentStreak > $longestStreak) {
                            $longestStreak = $currentStreak;
                        }
                    } else {
                        // Absent, Leave, or Half Day break the streak
                        $currentStreak = 0;
                    }
                } else {
                    // No attendance record on a working day = Absent (breaks streak)
                    $currentStreak = 0;
                }
            }

            // Check if employee meets the minimum streak requirement
            if ($longestStreak >= $minStreak) {
                // Attach the calculated streaks to the employee object dynamically
                $employee->longest_streak = $longestStreak;
                $employee->current_streak = $currentStreak;
                
                $qualifyingEmployees->push($employee);
            }
        }

        return $qualifyingEmployees;
    }
}

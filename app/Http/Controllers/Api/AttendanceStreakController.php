<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeStreakResource;
use App\Services\AttendanceStreakService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttendanceStreakController extends Controller
{
    public function __construct(
        protected AttendanceStreakService $streakService
    ) {}

    /**
     * Display a listing of employees who meet the minimum attendance streak.
     */
    public function index(Request $request)
    {
        try {
            $minStreak = (int) $request->input('min_streak', 5);
            
            $employees = $this->streakService->getEmployeesWithMinStreak($minStreak);

            if ($employees->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No employees found with the specified minimum attendance streak.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Employees with attendance streak retrieved successfully.',
                'data' => EmployeeStreakResource::collection($employees)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the attendance streak.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

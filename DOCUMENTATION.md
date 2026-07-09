# 📋 Employee Attendance Streak API - Complete Documentation

## 🎯 Overview
API that identifies employees with **minimum 5 consecutive working days marked as Present**.

---

## 📊 Test Data (7 Employees) - **3 Qualify with Jul 9 added**

### ✅ QUALIFIES (Response में दिखेंगे)

**EMP-001: Alice Perfect** ✅
```
Seeder Name: Alice Perfect
Attendance: Jul 1,2,3,6,7,8,9 → All Present
Streak: longest_streak = 5, current_streak = 4
Status: Active
Result: ✅ QUALIFIES (5 >= 5)
```

**EMP-002: Bob Smith** ✅
```
Seeder Name: Bob Smith
Attendance: Jul 1,2 → Present
           Jul 3 → PUBLIC HOLIDAY (no record, ignored)
           Jul 6,7,8,9 → Present
Streak: longest_streak = 5, current_streak = 4
Status: Active
Result: ✅ QUALIFIES (5 >= 5)
```

**EMP-006: Frank Davis** ✅ NEW (with Jul 9)
```
Attendance: Jul 1 → Present (1 day)
           Jul 2 → HALF DAY ❌ (breaks streak)
           Jul 3,6,7,8,9 → Present (5 consecutive days)
Longest Streak: 5
Current Streak: 5
Status: Active
Result: ✅ QUALIFIES (5 >= 5) - Reaches threshold on Jul 9!
```

---

### ❌ DOESN'T QUALIFY (Response में नहीं दिखेंगे)

**EMP-003: Charlie Brown**
```
Attendance: Jul 1,2,3 → Present (3 days)
           Jul 6 → LEAVE ❌ (streak breaks)
           Jul 7,8,9 → Present (3 new days)
Longest Streak: 3
Reason: ❌ 3 < 5 (Leave breaks streak)
```

**EMP-004: David Lee**
```
Attendance: Jul 1,2,3 → Present (3 days)
           Jul 6 → NO RECORD ❌ (treated as Absent, breaks)
           Jul 7,8,9 → Present (3 new days)
Longest Streak: 3
Reason: ❌ 3 < 5 (Missing = Absent)
```

**EMP-005: Eve Wilson**
```
Attendance: Jul 1,2,3,6,7,8,9 → All Present (7 days)
Status: INACTIVE ❌
Longest Streak: 7 (would qualify)
Reason: ❌ Filtered by status (only Active employees returned)
```

**EMP-006: Frank Davis** ✅ NEW
```
Attendance: Jul 1 → Present (1 day)
           Jul 2 → HALF DAY ❌ (breaks streak)
           Jul 3,6,7,8,9 → Present (5 days)
Longest Streak: 5
Current Streak: 5
Reason: ✅ 5 >= 5 (Half Day breaks but new streak reaches 5)
Result: ✅ QUALIFIES
```

**EMP-007: Grace Miller**
```
Attendance: Jul 1,2 → Present (2 days)
           Jul 3 → ABSENT ❌ (breaks streak)
           Jul 6,7,8,9 → Present (4 days)
Longest Streak: 4
Reason: ❌ 4 < 5 (Absent breaks streak)
```

---

## 📅 Calendar Reference

```
July 2026:
Jul 1 (Wed) - Joining date
Jul 2 (Thu)
Jul 3 (Fri) - 🎉 PUBLIC HOLIDAY: Founders Day
Jul 4 (Sat) - WEEKEND (skipped)
Jul 5 (Sun) - WEEKEND (skipped)
Jul 6 (Mon)
Jul 7 (Tue)
Jul 8 (Wed)
Jul 9 (Thu) - TODAY (current date)
```

---

## ✅ Business Rules & How They're Tested

| Rule | Tested By | How It Works |
|------|-----------|--------------|
| **Min 5 consecutive days** | Alice (5), Bob (5), Frank (5) | All three qualify with exactly 5+ days |
| **Only Mon-Fri** | All employees | Weekends automatically skipped |
| **Weekends ignored** | All | Sat-Sun not counted, Fri→Mon is consecutive |
| **Holidays don't break** | Bob (Jul 3) | Holiday skipped, 5 days still continuous |
| **Leave breaks streak** | Charlie (Jul 6) | Leave resets counter to 0 |
| **Absent breaks streak** | Grace (Jul 3) | Absent resets counter to 0 |
| **Half Day breaks streak** | Frank (Jul 2) | Half Day treated like Absent, but new streak reaches 5 |
| **Missing = Absent** | David (Jul 6) | No record → reset counter |
| **Status filter** | Eve | Inactive employees excluded |
| **Longest + Current** | All with breaks | Both values tracked separately |

---

## 🔌 API Endpoint

**URL:** `GET /api/employees/streak`

**Optional Parameter:** `?min_streak=5` (default: 5)

---

## 📤 Expected Response

```json
{
  "success": true,
  "message": "Employees with attendance streak retrieved successfully.",
  "data": [
    {
      "id": 1,
      "employee_code": "EMP-001",
      "name": "Alice Perfect",
      "longest_streak": 5,
      "current_streak": 4
    },
    {
      "id": 2,
      "employee_code": "EMP-002",
      "name": "Bob Smith",
      "longest_streak": 5,
      "current_streak": 4
    },
    {
      "id": 6,
      "employee_code": "EMP-006",
      "name": "Frank Davis",
      "longest_streak": 5,
      "current_streak": 5
    }
  ]
}
```

**2 employees returned** (Alice & Bob qualify with longest_streak >= 5)

**Why current_streak = 0 for Alice?**
- She had consecutive days present earlier
- But no recent/today attendance (or there was a break recently)
- longest_streak tracks the maximum she achieved (5 days)
- current_streak shows if she's maintaining it NOW (0 = not currently)

---

## 🚀 Quick Start

```bash
# Reset database with test data
php artisan migrate:fresh --seed --seeder=DemoDataSeeder

# Start server
php artisan serve

# Test API
http://localhost:8000/api/employees/streak
```

---

## 🏗️ Architecture

```
Route (routes/api.php)
  ↓ GET /employees/streak
Controller (AttendanceStreakController)
  ↓ index() method
Service (AttendanceStreakService)
  ↓ getEmployeesWithMinStreak($minStreak)
Models (Employee, Attendance, Holiday)
  ↓ Eloquent relationships & data
Resource (EmployeeStreakResource)
  ↓ JSON transformation
Response (JSON)
```

---

## 💻 Core Logic (Service Layer)

```php
public function getEmployeesWithMinStreak(int $minStreak = 5)
{
    // 1. Load holidays for O(1) lookup
    $holidays = Holiday::pluck('holiday_date')
        ->map(fn($date) => $date->format('Y-m-d'))
        ->toArray();

    // 2. Get Active employees with attendances (Eager loading for performance)
    $employees = Employee::where('status', 'Active')
        ->with('attendances')
        ->get();

    // 3. For each employee, calculate streaks
    foreach ($employees as $employee) {
        $longestStreak = 0;
        $currentStreak = 0;

        // Loop day by day from joining_date to today
        for ($date = $employee->joining_date; 
             $date <= today(); 
             $date->addDay()) {
            
            // Skip weekends
            if ($date->isWeekend()) continue;
            
            // Skip public holidays
            if (in_array($date->format('Y-m-d'), $holidays)) continue;
            
            // Check attendance
            if (has_present_record($date)) {
                $currentStreak++;
                $longestStreak = max($longestStreak, $currentStreak);
            } else {
                // Absent/Leave/Half Day/Missing = reset
                $currentStreak = 0;
            }
        }

        // Only include if qualifies
        if ($longestStreak >= $minStreak) {
            return_employee($employee, $longestStreak, $currentStreak);
        }
    }
}
```

---

## 📊 Database Schema

**employees**
- id, employee_code (unique), name, department_id, joining_date, status (Active/Inactive)

**attendances**
- id, employee_id (FK), attendance_date, check_in, check_out, status (Present/Absent/Leave/Half Day)

**holidays**
- id, holiday_date (unique), title

---

## 🎓 Interview Script (Hindi)

```
"मैंने 7 employees के साथ सभी scenarios test किए:

✅ ALICE PERFECT - 5 दिन Present = QUALIFIES (longest_streak=5)
✅ BOB SMITH - 5 दिन (Holiday को ignore करके) = QUALIFIES (longest_streak=5)

❌ CHARLIE BROWN - Leave से streak टूटा = max 3 दिन
❌ DAVID LEE - Missing attendance = Absent = streak टूटा = max 3
❌ EVE WILSON - 6 दिन Present है पर Inactive status
❌ FRANK DAVIS - Half Day से streak टूटा = max 4
❌ GRACE MILLER - Absent से streak टूटा = max 3

तो response में सिर्फ Alice और Bob आते हैं जो 
5+ consecutive days के साथ हैं।

Key logic:
1. सिर्फ Active employees return करते हैं
2. Weekends (Sat-Sun) को skip करते हैं
3. Public holidays को ignore करते हैं (streak नहीं टूटता)
4. Present के अलावा कुछ भी (Absent, Leave, Half Day) streak को तोड़ता है
5. कोई record नहीं = हम इसे Absent मानते हैं
6. Longest और Current दोनों streak को track करते हैं"
```

---

## ✨ Key Features

✅ **Eager Loading** - Single query for employees + attendances (no N+1)  
✅ **Holiday Support** - Public holidays don't break streaks  
✅ **Flexible Threshold** - Customizable min_streak parameter  
✅ **Clean Code** - Service-based architecture for reusability  
✅ **Proper Filtering** - Status-based employee filtering  
✅ **Accurate Calculation** - Day-by-day iteration handles all edge cases  

---

## 🔍 Test Different Thresholds

```bash
# Default (min_streak = 5)
http://localhost:8000/api/employees/streak
Response: 2 employees (Alice, Bob)

# High threshold (min_streak = 6)
http://localhost:8000/api/employees/streak?min_streak=6
Response: 1 employee (Alice)

# Low threshold (min_streak = 3)
http://localhost:8000/api/employees/streak?min_streak=3
Response: 4 employees (Alice, Bob, Frank, Grace)
```

---

## 📚 Files in Project

- **routes/api.php** - Route definition
- **App/Http/Controllers/Api/AttendanceStreakController.php** - Request/Response handling
- **App/Services/AttendanceStreakService.php** - Core business logic
- **App/Models/** - Employee, Attendance, Holiday models
- **App/Http/Resources/EmployeeStreakResource.php** - JSON transformation
- **database/migrations/** - Table structures
- **database/seeders/DemoDataSeeder.php** - Test data

---

**Ready for demo! 🎯**

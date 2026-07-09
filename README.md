<div align="center">
  <h1>🚀 Employee Attendance Streak API</h1>
  <p>A Laravel-based robust API solution to calculate and identify employees maintaining an attendance streak, handling complex business logic, weekends, holidays, and edge cases.</p>
</div>

---

## 📌 Project Overview
This project is an API developed in Laravel that determines which **Active** employees have maintained a minimum consecutive attendance streak (default: 5 days). It is built with a focus on **clean code, reusability, and performance optimization**.

---

## 🛠️ System Architecture & Design Patterns
To ensure scalability and maintainability, the project implements the following industry-standard design patterns:

1. **Service Pattern (`AttendanceStreakService`)**
   - **Why?** Keeps the Controller extremely thin. All complex time-series calculation, weekend-skipping, and streak logic is isolated in a reusable Service class.
2. **API Resources (`EmployeeStreakResource`)**
   - **Why?** Encapsulates the JSON response structure. It ensures the API output is clean, hiding database schema details (like `created_at`, `department_id`) from the end client.
3. **Eager Loading (`with('attendances')`)**
   - **Why?** Solves the notorious **N+1 Query Problem**. Instead of running a new query for every employee's attendance, it pulls all relevant data in highly optimized batches.
4. **Database Indexing**
   - **Why?** Added composite indexes on `(employee_id, attendance_date, status)` to ensure lookups are lightning-fast even with millions of attendance rows.

---

## 🔌 API Documentation

### **Endpoint:** Get Qualifying Employees
Returns a list of active employees who meet the minimum streak requirement, along with their longest and current streaks.

- **URL:** `/api/employees/streak`
- **Method:** `GET`
- **Headers:** `Accept: application/json`

#### **Query Parameters (Optional)**
| Parameter | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `min_streak` | `integer` | `5` | The minimum number of consecutive days required. |

#### **Example Request**
```bash
curl -X GET "http://127.0.0.1:8000/api/employees/streak?min_streak=5" -H "Accept: application/json"
```

#### **Success Response (200 OK)**
```json
{
    "success": true,
    "message": "Employees with attendance streak retrieved successfully.",
    "data": [
        {
            "id": 1,
            "employee_code": "EMP-001",
            "name": "Alice Ideal",
            "longest_streak": 6,
            "current_streak": 0
        },
        {
            "id": 2,
            "employee_code": "EMP-002",
            "name": "Bob Holiday",
            "longest_streak": 5,
            "current_streak": 0
        }
    ]
}
```

---

## 🧠 Business Rules & Edge Cases Handled

The `AttendanceStreakService` algorithm is robust and handles the following scenarios automatically:
1. **Weekends Ignored:** Saturdays and Sundays do not break streaks. Friday $\rightarrow$ Monday is considered consecutive.
2. **Public Holidays Ignored:** Public holidays (stored in the `holidays` table) do not break the streak. They are simply skipped.
3. **Streak Breakers:** Any status of `Absent`, `Leave`, or `Half Day` immediately resets the streak to `0`.
4. **Missing Records = Absent:** If an employee has no attendance record on a valid working day, the algorithm treats it as an absence and breaks the streak.
5. **Inactive Employees Filtered:** Employees with a status of `Inactive` are entirely ignored at the database query level.

---

## 🚀 How to Setup and Run for Demo

Follow these steps to run the project and populate it with pre-configured edge-case demo data:

### 1. Install Dependencies
*(Assuming Composer and PHP are installed)*
```bash
composer install
```

### 2. Setup the Database & Seed Demo Data
The project uses SQLite for rapid demonstration. The provided seeder automatically injects 6 specific test cases into the database to prove the logic handles all business rules perfectly.
```bash
php artisan migrate:fresh --seed
```
*Note: The seeder inserts `Alice` (Ideal), `Bob` (Holiday skip), `Charlie` (Broken streak), `David` (Missing record), `Eve` (Inactive), and `Frank` (Multiple streaks).*

### 3. Start the Server
```bash
php artisan serve
```

### 4. Test the API
Hit the following URL in Postman or your browser:
**`http://127.0.0.1:8000/api/employees/streak`**

---

<div align="center">
  <i>Developed to showcase clean Laravel architecture and complex algorithmic problem solving.</i>
</div>

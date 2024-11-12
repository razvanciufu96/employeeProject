# Employee API Documentation

**Base URL**: `/api/employees`

---

### 1. Get All Employees
- **Endpoint**: `GET /api/employees`
- **Description**: Retrieves a list of all employees.

#### Sample Request
```http
Endpoint: GET /api/employees

Sample Response
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "phone_number": "123-456-7890",
        "job_title": "Developer",
        "salary": 60000
    }
]

### 2. Get Single Employee

Endpoint: GET /api/employees/{id}

Sample Response

{
    "id": 1,
    "name": "John Doe",
    "email": "johndoe@example.com",
    "phone_number": "123-456-7890",
    "job_title": "Developer",
    "salary": 60000
}

### 3. Create Employee

Endpoint: POST /api/employees
Content-Type: application/json

{
    "name": "Alice Brown",
    "email": "alicebrown@example.com",
    "phone_number": "555-123-4567",
    "job_title": "Project Manager",
    "salary": 75000
}

Sample Response

{
    "message": "Employee created successfully.",
    "data": {
        "id": 3,
        "name": "Alice Brown",
        "email": "alicebrown@example.com",
        "phone_number": "555-123-4567",
        "job_title": "Project Manager",
        "salary": 75000
    }
}

### 4. Update Employee

Endpoint: PUT /api/employees/{id}

{
    "message": "Employee updated successfully.",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@example.com",
        "phone_number": "123-456-7890",
        "job_title": "Senior Developer",
        "salary": 70000
    }
}

### 5. Delete an Employee

DELETE /api/employees/{id}

{
    "message": "Employee deleted successfully."
}

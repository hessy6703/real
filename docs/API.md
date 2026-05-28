# Employee Performance Evaluation System - API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
All API endpoints (except peer review) require authentication via JWT token in the Authorization header:
```
Authorization: Bearer {token}
```

## Response Format
```json
{
  "status": "success|error",
  "message": "Description",
  "data": {}
}
```

---

## Authentication Endpoints

### Login
```http
POST /auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "status": "success",
  "token": "eyJhbGc...",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "role": "SUPERVISOR",
    "name": "John Doe"
  }
}
```

### Logout
```http
POST /auth/logout
Authorization: Bearer {token}
```

---

## Evaluation Endpoints

### Get Evaluation Form
```http
GET /evaluations/{employeeId}/form
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "employee": {
      "id": 1,
      "employee_id": "EMP001",
      "full_name": "Jane Smith",
      "department": "Clinical",
      "job_title": "Nurse"
    },
    "scoring_categories": {
      "performance": {
        "max": 30,
        "label": "Performance: Job effectiveness, productivity, quality of work"
      },
      "patient_care": {
        "max": 20,
        "label": "Patient Care: Quality of service/care delivery"
      }
    }
  }
}
```

### Submit Evaluation
```http
POST /evaluations
Authorization: Bearer {token}
Content-Type: application/json

{
  "employee_id": 1,
  "evaluation_period": "Q1",
  "scores": {
    "performance": 28,
    "patient_care": 19,
    "teamwork": 18,
    "reliability": 14,
    "initiative": 14
  },
  "overall_comments": "Excellent performer",
  "strengths": "Strong leadership",
  "areas_for_improvement": "Time management"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Evaluation submitted successfully",
  "evaluation_id": 5,
  "calculations": {
    "supervisor_total": 93,
    "supervisor_weighted": 37.2,
    "peer_average": 85,
    "peer_weighted": 51,
    "final_score": 88.2,
    "rating": "Very Good",
    "status": "Reviewed"
  }
}
```

### Get Evaluation
```http
GET /evaluations/{evaluationId}
Authorization: Bearer {token}
```

### List Evaluations (with filters)
```http
GET /evaluations?period=Q1&status=SUBMITTED&department=Clinical
Authorization: Bearer {token}
```

---

## Peer Review Endpoints (Anonymous)

### Get Peer Review Form
```http
GET /peer-reviews/form
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "scoring_categories": { },
    "employees": [
      {
        "id": 1,
        "full_name": "Jane Smith",
        "department": "Clinical"
      }
    ]
  }
}
```

### Submit Peer Review (Anonymous)
```http
POST /peer-reviews
Content-Type: application/json

{
  "employee_id": 1,
  "reviewer_employee_id": 2,
  "evaluation_period": "Q1",
  "scores": {
    "performance": 27,
    "patient_care": 18,
    "teamwork": 17,
    "reliability": 13,
    "initiative": 14
  },
  "comments": "Great team player",
  "suggestions": "Could improve communication"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Peer review submitted successfully",
  "review_id": 12
}
```

### Get Peer Reviews for Employee
```http
GET /peer-reviews/employee/{employeeId}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 12,
      "evaluation_period": "Q1",
      "total_score": 89,
      "comments": "Great team player"
    }
  ],
  "count": 1,
  "average_score": 89
}
```

---

## Report Endpoints

### Get Individual Report
```http
GET /reports/individual/{employeeId}?period=Q1
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "employee": { },
    "evaluation": {
      "final_score": 88.2,
      "performance_rating": "Very Good",
      "supervisor_total": 93,
      "peer_average": 85
    },
    "trends": [
      {
        "period": "Q4",
        "score": 82,
        "change": 6.2,
        "change_percent": 7.6
      }
    ]
  }
}
```

### Get Department Report
```http
GET /reports/department/{department}?period=Q1
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "department": "Clinical",
    "employee_count": 15,
    "evaluated_count": 14,
    "average_score": 82.5,
    "distribution": {
      "Outstanding": 2,
      "Very Good": 5,
      "Good": 4,
      "Fair": 2,
      "Needs Improvement": 1
    },
    "evaluations": [ ]
  }
}
```

### Export Report as PDF
```http
POST /reports/export/pdf
Authorization: Bearer {token}
Content-Type: application/json

{
  "type": "individual",
  "id": 1,
  "period": "Q1"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "PDF exported successfully",
  "file": "report_2026-05-28_14-30-00.pdf",
  "download_url": "/downloads/report_2026-05-28_14-30-00.pdf"
}
```

---

## Employee Endpoints

### Get All Employees
```http
GET /employees
Authorization: Bearer {token}
```

### Get Employee
```http
GET /employees/{employeeId}
Authorization: Bearer {token}
```

### Get Employees by Department
```http
GET /employees/department/{department}
Authorization: Bearer {token}
```

### Create Employee (HR/Admin only)
```http
POST /employees
Authorization: Bearer {token}
Content-Type: application/json

{
  "employee_id": "EMP101",
  "full_name": "New Employee",
  "email": "new@example.com",
  "department": "Clinical",
  "job_title": "Nurse",
  "supervisor_id": 1
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "status": "error",
  "message": "Unauthorized: Invalid or missing token"
}
```

### 403 Forbidden
```json
{
  "status": "error",
  "message": "Forbidden: You do not have permission to access this resource"
}
```

### 404 Not Found
```json
{
  "status": "error",
  "message": "Not found: Resource does not exist"
}
```

### 422 Unprocessable Entity
```json
{
  "status": "error",
  "message": "Validation failed",
  "details": {
    "performance": "Performance must be between 0 and 30",
    "email": "Email is invalid"
  }
}
```

### 500 Internal Server Error
```json
{
  "status": "error",
  "message": "Internal server error"
}
```

---

## Rate Limiting

API requests are rate limited:
- **General**: 100 requests per minute per user
- **Authentication**: 5 failed login attempts per 15 minutes
- **Export**: 10 PDF exports per hour per user

**Rate Limit Headers:**
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1622332800
```

---

## Pagination

List endpoints support pagination:
```
GET /evaluations?page=1&limit=25
```

**Response includes:**
```json
{
  "status": "success",
  "data": [],
  "pagination": {
    "page": 1,
    "limit": 25,
    "total": 150,
    "pages": 6
  }
}
```

---

## Status Codes

| Code | Meaning |
|------|----------|
| 200 | OK - Request succeeded |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request data |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Permission denied |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation failed |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Internal Server Error - Server error |

---

**Last Updated**: May 28, 2026  
**Version**: 1.0.0

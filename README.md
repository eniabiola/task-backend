# 📝 Laravel Task Management API

A simple and test-driven Laravel API for managing tasks, updating their statuses, and tracking their status history.
Built with clean architecture principles, role-based access control, token-based authentication, 
API resources for structured responses, and comprehensive feature testing.

---

## 🚀 Features

- ✅ User Registration & Login (Token-based via Laravel Sanctum)
- ✅ Create, View, and Delete Tasks
- ✅ Status Assignment & Valid Transitions
- ✅ Task Status History Logging
- ✅ Role-Based Permissions (User/Admin)
- ✅ Audit Trail for Status Updates
- ✅ Fully Tested with Feature & Unit Tests

---

## 🔧 Tech Stack

- **Framework**: Laravel 10+
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel-Permission
- **Database**: MySQL / SQLite (for testing)
- **Testing**: PHPUnit, Laravel TestCase
- **Architecture**: Service Layer, Form Requests, API Resources

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── API/       
│   ├── Resources/     
│   ├── Requests/   
│   ├── DTO/       
│   ├── Service/       
├── Models/       
├── Rules/       
├── Traits/            

database/
├── factories/         
├── migrations/        
├── seeders/           

routes/
└── api.php            

tests/
├── Feature/           
├── Unit/              
└── TestHelperTrait.php 
```

---

## 🔐 API Authentication

Laravel Sanctum is used for API token management.

### Public Endpoints
- `POST /api/register` — Register new users
- `POST /api/login` — Authenticate users

### Protected Endpoints (Require Bearer Token)
- `POST /api/logout`
- `GET /api/tasks`
- `POST /api/tasks`
- `GET /api/tasks/{id}`
- `PATCH /api/tasks/{id}/status`
- `DELETE /api/tasks/{id}`
- `GET /api/task-statuses`
- `GET /api/task-status-histories/{taskId}`

---

## 🧪 Testing

Run all tests:

```bash
php artisan test
```

### ✅ Feature Tests

### ✅ Unit Tests


---

## 📦 Setup

1. Clone the repository
2. Install dependencies:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```
---
Seeded data
<br>username: cila@mailinator.com
<br>password: password

---

3. Serve the app:

```bash
php artisan serve
```

---

## 💡 Future Improvements

- Task comments or attachments
- Due dates & reminders
- Notifications for actions
- Role-based permissions (e.g., admin vs user)
- API rate limiting and throttling

---


##  API Documentation

https://documenter.getpostman.com/view/8700481/2sB2j999pp

## 👤 Author

Made by [Enitan Awosanya] — built with testing, clarity, and maintainability in mind.

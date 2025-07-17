# 📝 Laravel Task Management API

A simple and test-driven Laravel API for managing tasks, tracking their statuses, and maintaining a status change history. Built with clean architecture principles, token-based authentication, and API resources for structured responses.

---

## 🚀 Features

- ✅ User Registration & Login (Token-based via Laravel Sanctum)
- ✅ Create, View, Update, and Delete Tasks
- ✅ Assign and Change Task Status
- ✅ View Available Task Statuses
- ✅ Track and Retrieve Status History for Tasks
- ✅ Fully Tested with Feature

---

## 🔧 Tech Stack

- **Framework**: Laravel 10+
- **Authentication**: Laravel Sanctum
- **Database**: MySQL / SQLite (for testing)
- **Testing**: PHPUnit with Laravel's testing tools
- **Formatting**: API Resources

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── API/       
│   ├── Resources/     
├── Models/            

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

- **AuthTest**: Register, login, logout
- **TaskTest**: Full CRUD & permission tests
- **TaskStatusTest**: View all statuses
- **TaskStatusHistoryTest**: View history, check access

### ✅ Unit Tests

- **TaskResourceTest**: Validates structure and logic of JSON responses
- Can be extended for other resources or custom logic

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
- Role-based permissions (e.g., admin vs user)
- API rate limiting and throttling

---


##  API Documentation

https://documenter.getpostman.com/view/8700481/2sB2j999pp

## 👤 Author

Made by [Enitan Awosanya] — built with testing, clarity, and maintainability in mind.

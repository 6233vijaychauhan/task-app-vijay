# Task Management App

A Laravel-based task management application with Vue.js frontend and API endpoints for managing tasks.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL
- WAMP/XAMPP or similar local development environment

## Project Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd task-app-vijay
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Set up environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database**
   - Open `.env` file
   - Set your database connection details:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

6. **Install Node.js dependencies**
   ```bash
   npm install
   ```

7. **Build frontend assets**
   ```bash
   npm run dev
   ```

## Running Migrations & Seeders

### Run Migrations
To create the database tables:
```bash
php artisan migrate
```

### Run Seeders
To populate the database with sample data:
```bash
php artisan db:seed --class=TaskSeeder
```

This will create  20 sample tasks with random data

## Running Tests

Execute the test suite using PHPUnit:
```bash
php artisan test --filter=TaskTest
```

## Development Server

To start the development server:
```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000/tasks`

And The application API will be available at `http://127.0.0.1:8000/api/tasks`

For a complete development environment with all services:
```bash
npm run dev
```


## API Endpoints

The application provides RESTful API endpoints for task management. All task endpoints return data in JSON format.

### Task Endpoints

#### List Tasks
```
GET /api/tasks
```
Returns a paginated list of tasks ordered by creation date (newest first).

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Sample Task",
      "description": "Task description",
      "status": "pending",
      "due_date": "2026-01-15",
      "created_at": "2026-01-09T10:00:00.000000Z"
    }
  ],
  "links": {...},
  "meta": {...}
}
```

#### Create Task
```
POST /api/tasks
```

**Request Body:**
```json
{
  "title": "New Task Title",
  "description": "Task description (optional)",
  "status": "pending",
  "due_date": "2026-01-15"
}
```

**Validation Rules:**
- `title`: required, string, max 255 characters
- `description`: optional, string
- `status`: required, must be one of: `pending`, `in_progress`, `completed`
- `due_date`: optional, valid date format

**Response:** Returns the created task resource (201 status)

#### Show Task
```
GET /api/tasks/{id}
```
Returns a single task by ID.

**Response:** Task resource (200 status) or 404 if not found

#### Update Task
```
PUT /api/tasks/{id}
```

**Request Body:** (same as create, all fields optional for partial updates)
```json
{
  "title": "Updated Task Title",
  "status": "completed"
}
```

**Response:** Returns the updated task resource (200 status)

#### Delete Task
```
DELETE /api/tasks/{id}
```
Soft deletes the task.

**Response:**
```json
{
  "message": "Task deleted"
}
```
## Task Status Values

Tasks can have the following status values:
- `pending`: Task has been created but not started
- `in_progress`: Task is currently being worked on
- `completed`: Task has been finished

## Database Schema

### Tasks Table
- `id`: Primary key
- `title`: Task title (string, max 255)
- `description`: Task description (text, nullable)
- `status`: Task status (enum: pending, in_progress, completed)
- `due_date`: Due date (datetime, nullable)
- `created_at`: Creation timestamp
- `updated_at`: Update timestamp
- `deleted_at`: Soft delete timestamp (nullable)

## Technologies Used

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Vue.js 3, Vite, Tailwind CSS
- **Database:** MySQL (or compatible)
- **API Authentication:** Laravel Sanctum
- **Testing:** PHPUnit
- **Development Tools:** Composer, npm
# Team Task Manager API

## Overview
You’ve been contracted to build a backend-only API for a lightweight Team Task Manager platform. The platform helps small teams manage internal tasks and assignments.

There are two user roles:
Admin – manages users and tasks
Member – can view and update tasks assigned to them

Your goal is to create a clean, RESTful API backend that handles user authentication, role-based access, task management, and supports importing/exporting tasks via Excel.

### API Implementation
As part of the project requirements, the following functionalities have been implemented:

- **Roles**: Implemented two roles, admin and member, using policies
- **Admin Task Management**: Admins can effortlessly create, read, update, and delete posts using dedicated API routes. Admins can assign tasks to members, and can view all tasks.
- **Member Task Management**: Member can view/see all tasks assigned to them, and also can only update/modify the status of those tasks.
- **Soft Delete**: Tasks must support soft deletes. Soft-deleted tasks should be excluded from default task listings. Admins must be able to: View all soft-deleted tasks, Restore a soft-deleted task, Permanently delete a task (force delete). Members should not interact with deleted tasks in any way.
- **JWT Authentication**: Users are required to register and log in to generate Bearer tokens, ensuring secure access to protected routes. Laravel Sanctum was used.
- **Registration and Login Validations**: Input data for registration and login is validated to ensure data integrity and security.
- **Task Import from Excel**: Provided an endpoint for admins only to upload and import Excel files where multiple tasks data are in. Validated required fields. Ensure the the assigned user exists by email. This process was done synchronously.
- **Task Export to Excel**: Provide an endpoint to download a user’s visible tasks as an Excel file. Admin role users should be able to export all tasks, while the members roles should be able to export only the tasks assigned to them.
- **API Documentation**: Comprehensive API documentation has been generated in this README, providing insights into available endpoints and their usage.

This backend API has been meticulously designed to fulfill these requirements. By adhering to RESTful API principles, clean code architecture, Service design pattern, the API ensures seamless communication and data exchange between the frontend and backend components.

## Table of Contents
- **Getting Started**
  - Prerequisites
  - Installation
- **Usage**
  - Creating a Task
  - Reading Task
  - Updating a Task
  - Deleting a Task
  - And a lot more
- **Thumbnail Handling**
- **API Documentation**
- **Contributing**

## Getting Started

### Prerequisites
- PHP (>= 8.0)
- Composer (for dependency management)
- MySQL or compatible database
- XAMMP Server installed
- Go to xammp/php/php.ini and enable these two extensions, `extension=gd` and `extension=zip` and save

NB: Open powershell and run these two commands to double-check if truly you've enabled those two extensions successfully,
`php -i | findstr Zip` and `php -i | findstr gd`

Expected output should be something like these,
```
PS C:\Users\Nwokorie Alex> php -i | findstr Zip
BZip2 Support => Enabled
BZip2 Version => 1.0.8, 13-Jul-2019
Zip => enabled
Zip version => 1.21.1


PS C:\Users\Nwokorie Alex> php -i | findstr gd
gd
gd.jpeg_ignore_warning => On => On
```

### Installation

1. Make sure you have PHP installed on your system. And pls, ensure you have enabled those two extensions outlined above, very important. Do that, before starting your XAMMP Server.
2. Start the phpMyAdmin Apache and MySQL server in XAMMP
3. Clone the repository to your local machine, inside your xampp/htdocs directory:
   ```
   git clone https://github.com/lexiscode/task-manager-api.git
   cd task-manager-api
   ```
4. Open the repository with your VS Code IDE (or any other IDEs). Locate a file named `.env.example`, rename the file to `.env`.
5. Open terminal and run this command: `composer install`. 
6. Next, run this migration command, still from within the project directory: `php artisan migrate --seed`
7. (Optional) Next, generate your own APP_KEY by running this command still from within the project directory: `php artisan key:generate`
8. Run Laravel server: `php artisan serve`

## Usage

### API Testing with Postman

The API can be tested using Postman. But note that you will need to first create a JWT authentication token (by creating an account and logging in) in order to gain access to the task resources. 

I have created a default admin login details using seeders, so you can just login directly with these credentials below:

Use this endpoint to login,
```
POST /api/login
```

Default Admin login details:
```json
{
  "email": "admin@admin.com",
  "password": "Password123",
}
```
Default Member login details:
```json
{
  "email": "member@member.com",
  "password": "Password123",
}
```

Use the following endpoint to create a new member user account and also login in order to generate an authorization "Bearer Token":
```
POST /api/register
POST /api/login
```

Sample JSON request body for both the registeration and login (for members), only email and password fields:
```json
{
  "email": "email@example.com",
  "password": "password",
}

```

NB: You logged in? If yes! Go to the Authentication tab, and select type "Bearer Token", then copy and paste the bearer token given to you (from inside the response output) to the input field you will see at the right side (still within the Authentication tab of Postman). You have a limit of 1 hour maximum, to gain access to the task resources via this token.

## ALL ADMIN FEATURES FOR TESTING

### Creating a Task (as Admin)
Use the following endpoint to create a new blog post:
```
POST /api/tasks
```

Sample JSON request body:
```json
{
  "title": "Sample Title",
  "description": "Some description",
  "status": "In Progress",
  "due_date": "2025-07-15"
}

```

### Reading Tasks (as Admin)
- Retrieve all tasks:
  ```
  GET /api/tasks
  ```

- Retrieve a task by ID:
  ```
  GET /api/tasks/{id}
  ```

### Create new Task (as Admin)
Use the following endpoint to update a task:
```
POST /api/tasks
```

### Updating a Task (as Admin)
Use the following endpoint to update a task:
```
PUT /api/tasks/edit/{id}
```

### Soft Deleting a Task (as Admin)
Use the following endpoint to delete a task:
```
DELETE /api/posts/{id}
```

### Assigning Task to Member (as Admin)
Use the following endpoint to assign task:

Enter the task id you want to assign to a member,
```
POST /api/tasks/{id}/assign
```
In the request body, add the member's user id whom you wish to assign the task to:
```json
{
  "user_id": 2,
}
```

### Task Import from Excel (as Admin)
Use the following endpoint to import tasks:
```
POST /api/import/tasks
```
In the request body, set the key input field as "file", and then change the value from text to file in Postman (then upload an excel file from your PC). You will find a sample excel file in this root project directory for your use (sample_tasks.xlsx).

### Task Export to Excel (as Admin)
Use the following endpoint to import tasks:
```
GET /api/export/tasks
```
You will get 200 OK response. At the top right-corner of the response section, you will see three dots, click on it, and then click on "Save response". This will save/download the Excel file from Postman.

### Managing Soft Deletes (as Admin)
- To view all soft deleted tasks:
  ```
  GET /api/tasks/trashed
  ```

- To restore a soft deleted task:
  ```
  POST /api/tasks/{id}/restore
  ```

- To permanently delete a soft deleted task:
  ```
  DELETE /api/tasks/{id}/force
  ```

## ALL MEMBER FEATURES FOR TESTING

### Reading Tasks Assigned to them (as Member)
- Retrieve all tasks:
  ```
  GET /api/tasks
  ```

- Retrieve a task by ID:
  ```
  GET /api/tasks/{id}
  ```

### Updating the status of their Task (as Member)
Use the following endpoint to update a task:
```
PUT /api/tasks/edit/{id}
```
In the request body:
```json
{
  "status": "Completed",
}
```
### Task Export to Excel (as Admin or Member)
Use the following endpoint to import tasks:
```
GET /api/export/tasks
```
You will get 200 OK response. At the top right-corner of the response section, you will see three dots, click on it, and then click on "Save response". This will save/download the Excel file from Postman.

# Notifier App

Event-driven notification service built with Laravel, DDD-style architecture, Jobs, and idempotency support.

---

## 🚀 Project Setup

### 1. Start Docker environment

```bash
docker-compose up -d
```

---

### 2. Enter application container

```bash
docker exec -it notifier_app bash
```

---

### 3. Install dependencies

```bash
composer install
```

---

### 4. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 5. Generate API documentation

```bash
php artisan l5-swagger:generate
```

---

## 📦 Architecture Overview

This project follows a **clean DDD-inspired architecture** with Command/Handler pattern and async processing via Jobs.

---

## 🔁 Request Flow

### 1. HTTP Layer (Controller)

Each request enters through a controller:

* Validates request via `FormRequest`
* Extracts data
* Creates a **Command object**

---

### 2. Application Layer (Command → Handler)

Controller sends data to a **Command Handler**:

```
Controller → Command → Handler
```

The handler:

* Executes business logic
* Creates Notification Batch
* Stores messages in DB

---

### 3. Async Processing (Jobs)

After saving notification messages:

* A **Job is dispatched per message**
* Each job processes a single notification

```
NotificationMessageJob
```

---

## ⚙️ Job Processing Logic

Each job:

* Loads message by ID
* Checks status
* Processes sending (email/sms/push)
* Updates status

---

## 🔁 Retry & Reliability

We use Laravel Job retries with backoff strategy:

```php
public array $backoff = [5, 15, 60, 300];
```

### Meaning:

* 1st retry: after 5 sec
* 2nd retry: after 15 sec
* 3rd retry: after 60 sec
* 4th retry: after 300 sec

---

## 🧠 Idempotency

To prevent duplicate requests:

* Each request must include `X-Idempotency-Key`
* Middleware validates it
* Same key prevents duplicate processing

---

## 📡 Swagger API

Swagger UI available after generation:

```
/api/documentation
```

Generated via:

```bash
php artisan l5-swagger:generate
```

---

## 🧱 Key Design Principles

* Command/Handler separation
* Domain-driven structure
* Async processing via queues
* Retry-safe jobs
* Idempotent API design
* Clear separation of infrastructure & domain

---

## 🏁 Summary

Flow:

```
HTTP Request
   ↓
Controller
   ↓
Command
   ↓
Handler
   ↓
DB (Notification Batch + Messages)
   ↓
Jobs (async processing)
   ↓
External delivery (email/sms/push)
```

## 🧪 Code Quality & Static Analysis

Although CI automation is not fully configured in GitHub Actions yet, the project relies on strict local quality tools to maintain architecture and code consistency.

### 📊 Deptrac (Architecture Rules)

Used to enforce architectural boundaries between layers (Domain / Application / Infrastructure):

```bash id="dpt1"
vendor/bin/deptrac
```

It ensures that:

* Domain layer has no external dependencies
* Application layer depends only on Domain
* Infrastructure depends on Application/Domain rules only where allowed

---

### 🔍 Psalm (Static Analysis)

Used for deep static type checking and early bug detection:

```bash id="ps1"
vendor/bin/psalm
```

It helps to:

* Detect type mismatches
* Find unreachable or unsafe code
* Improve overall type safety

---

### 🎨 Pint (Code Style)

Laravel Pint is used for automatic code formatting:

```bash id="pt1"
vendor/bin/pint
```

It ensures:

* Consistent code style
* PSR-12 compliance
* Clean diff in pull requests

---

## 🚧 CI Status

GitHub Actions pipeline is **not yet configured**, but the project is designed to be CI-ready.

When enabled, CI will run:

* Deptrac (architecture validation)
* Psalm (static analysis)
* Pint (formatting checks)
* PHPUnit (tests, if enabled)

---

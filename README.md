PHP-Based API Gateway – README
==============================

📦 Project Overview
--------------------
This is a simple API Gateway built in PHP using Laragon. It demonstrates:
- Basic API routing
- API key authentication
- File-based rate limiting
- Centralized logging
- API documentation

Backend microservices (service_users.php and service_products.php) simulate responses from user and product services.

🛠️ Setup Instructions
---------------------
1. Requirements:
   - Laragon (or any Apache + PHP stack)
   - PHP 7.4+ recommended

2. Project Folder Structure:
   Place this project in Laragon’s www directory:
   laragon/www/my_api_gateway/

3. Enable Apache Modules:
   Make sure mod_rewrite and mod_headers are enabled in Apache.
   In httpd.conf (Laragon: laragon/etc/apache2/httpd.conf), ensure these lines are uncommented:
     LoadModule rewrite_module modules/mod_rewrite.so
     LoadModule headers_module modules/mod_headers.so

4. Writable Folders:
   Ensure the following folders are writable:
   - logs/
   - ratelimit_data/

5. Start Laragon, then access the gateway at:
   http://localhost/my_api_gateway/

🔑 Valid API Keys
------------------
| API Key   | User  |
|-----------|--------|
| key123    | franz |
| key456    | Bob   |

🧪 Testing with Postman or curl
-------------------------------
1. GET /api/users
   URL: http://localhost/my_api_gateway/api/users
   Header: X-API-Key: key123
   Response:
   [
     { "id": 1, "name": "franz" },
     { "id": 2, "name": "Bob" }
   ]

2. GET /api/products
   URL: http://localhost/my_api_gateway/api/products
   Header: X-API-Key: key456
   Response:
   [
     { "sku": "A123", "productName": "Selpon" },
     { "sku": "B456", "productName": "Bomber" }
   ]

3. Rate Limiting Test
   - Send more than 10 requests in 60 seconds using a valid API key.
   - Response after limit:
     429 Too Many Requests
     { "error": "Rate limit exceeded" }

4. Unauthorized or Missing API Key
   - Response:
     401 Unauthorized
     { "error": "Invalid or missing API Key" }

5. Access API Docs
   - Open in browser:
     http://localhost/my_api_gateway/docs.html

❗ Challenges Faced & Assumptions
-------------------------------
- getallheaders() is inconsistent on Windows/Laragon. Used $_SERVER['HTTP_X_API_KEY'] instead.
- Assumes single-user usage for rate limiting (no file locking for JSON).
- Assumes mod_rewrite and mod_headers are properly enabled.
- Simple backend services are simulated with static JSON in PHP.

✨ Bonus Tasks Attempted
------------------------
✅ Centralized Logging
- Every request is logged to logs/gateway.log.
- Log Format:
  [YYYY-MM-DD HH:MM:SS] - IP: [Client IP] - API Key: [Key] - Path: [users/products] - Status: [HTTP Code]

How to test:
- Make valid/invalid requests.
- Open logs/gateway.log to view entries.

📁 File Summary
----------------
my_api_gateway/
├── api/                   # Virtual path via .htaccess
├── services/
│   ├── service_users.php
│   └── service_products.php
├── logs/                  # Log file goes here
├── ratelimit_data/        # Rate limiting files stored here
├── config.php             # (Optional config, unused if keys are in gateway.php)
├── docs.html              # API documentation
├── gateway.php            # Main gateway logic
├── .htaccess              # Routing rules

📬 Questions?
-------------
For help or support, open an issue or reach out in no way.

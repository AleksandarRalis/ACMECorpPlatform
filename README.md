# ACME Corp CSR Platform

A Corporate Social Responsibility (CSR) platform built for ACME Corp to enable employee engagement and community involvement through fundraising campaigns and donations.

## 🚀 Features

- **Employee Authentication**: Secure login/registration with Laravel Sanctum
- **Campaign Management**: Create, manage, and search fundraising campaigns
- **Donation System**: Make donations with payment processing abstraction
- **Admin Panel**: Restricted administration section for application management
- **Role-Based Access**: Admin and Employee roles with appropriate permissions
- **Modern UI**: Vue.js 3 frontend with Tailwind CSS

## 🛠️ Tech Stack

### Backend
- **PHP 8.2+** with Laravel 11
- **MySQL** database
- **Laravel Sanctum** for API authentication
- **Composer** for dependency management
- **PHPStan** (Level 8) for static analysis

### Frontend
- **Vue.js 3** with Composition API
- **Vue Router** for client-side routing
- **Pinia** for state management
- **Tailwind CSS** for styling
- **Vite** for build tooling
- **Axios** for HTTP requests

### Development Tools
- **Laravel Sail** for Docker-based development environment
- **Pest** for testing
- **PHPStan** for static analysis
- **Composer** for PHP dependencies
- **NPM** for Node.js dependencies

## 📋 Prerequisites

### Option 1: Laravel Sail (Recommended)
- **Docker Desktop** installed and running
- **Git**

### Option 2: Local Development
- PHP 8.2 or higher
- Composer
- Node.js 18+ and NPM
- MySQL 8.0+
- Git

## 🚀 Quick Start with Laravel Sail (Recommended)

Laravel Sail provides a Docker-based development environment that includes all necessary services (PHP, MySQL, Redis, Mailpit) out of the box.

### 1. Clone the Repository
```bash
git clone <your-repo-url>
cd AcmeCORP
```

### 2. Start Laravel Sail
```bash
# Start all services (Laravel, MySQL, Redis, Mailpit)
./vendor/bin/sail up -d
```

**Note**: If you encounter port conflicts (e.g., port 80 or 3306 already in use), you may need to stop local services:
```bash
# Stop local MySQL if running
sudo systemctl stop mysql

# Stop local Apache/Nginx if running
sudo systemctl stop apache2
# or
sudo systemctl stop nginx
```

### 3. Install Dependencies
```bash
# Install PHP dependencies
./vendor/bin/sail composer install

# Install Node.js dependencies
./vendor/bin/sail npm install
```

### 4. Environment Setup
```bash
# Copy environment file (if not already done)
cp .env.example .env

# Generate application key
./vendor/bin/sail artisan key:generate
```

### 5. Database Setup
```bash
# Run migrations
./vendor/bin/sail artisan migrate

# Seed the database with sample data
./vendor/bin/sail artisan db:seed
```

### 6. Build Frontend Assets
```bash
# Build for production
./vendor/bin/sail npm run build

# Or run in development mode with hot reload
./vendor/bin/sail npm run dev
```

### 7. Access the Application

Your application is now running at:
- **Main Application**: http://localhost
- **Vite Dev Server**: http://localhost:5173 (if running `npm run dev`)
- **Mailpit (Email Testing)**: http://localhost:8025

## 🐳 Laravel Sail Commands

### Basic Commands
```bash
# Start all services
./vendor/bin/sail up -d

# Stop all services
./vendor/bin/sail down

# View running containers
./vendor/bin/sail ps

# View logs
./vendor/bin/sail logs
```

### Development Commands
```bash
# Run Laravel Artisan commands
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan make:controller MyController

# Run Composer commands
./vendor/bin/sail composer install
./vendor/bin/sail composer update

# Run NPM commands
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
./vendor/bin/sail npm run build

# Run tests
./vendor/bin/sail test
./vendor/bin/sail artisan test

# Run PHPStan
./vendor/bin/sail phpstan analyse
```

### Container Access
```bash
# Access Laravel container shell
./vendor/bin/sail shell

# Access MySQL
./vendor/bin/sail mysql

# Access Redis CLI
./vendor/bin/sail redis
```

## 🚀 Quick Start (Local Development)

If you prefer to run the application locally without Docker:

### 1. Clone the Repository
```bash
git clone <your-repo-url>
cd AcmeCORP
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ACMECorp
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 6. Build Frontend Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start the Application
```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite dev server (if needed)
npm run dev
```

Visit `http://localhost:8000` to access the application.

## 👥 Default Users

After running the seeder, you'll have these default users:

### Admin User
- **Email**: `admin@acmecorp.com`
- **Password**: `password`
- **Role**: Admin

### Employee Users
- **Email**: `john.doe@acmecorp.com`
- **Password**: `password`
- **Role**: Employee

- **Email**: `jane.smith@acmecorp.com`
- **Password**: `password`
- **Role**: Employee

- **Email**: `mike.johnson@acmecorp.com`
- **Password**: `password`
- **Role**: Employee

## 🏗️ Architecture

### Backend Architecture
- **MVC Pattern**: Models, Views (API), Controllers
- **Repository Pattern**: Service layer for business logic
- **Strategy Pattern**: Payment processing abstraction
- **Eloquent ORM**: Database relationships and queries
- **API Resources**: Structured JSON responses

### Frontend Architecture
- **Component-Based**: Reusable Vue components
- **State Management**: Pinia stores for global state
- **Route Guards**: Authentication-based navigation
- **API Integration**: Axios for backend communication

### Database Design
- **Users**: Employee information and authentication
- **Roles**: Role-based access control
- **Campaigns**: Fundraising campaign details
- **Donations**: Donation records
- **Donation Details**: Payment processing information

## 🔐 Authentication & Authorization

- **Laravel Sanctum**: Token-based API authentication
- **Role-Based Access**: Admin and Employee roles
- **Route Protection**: Middleware-based route guards
- **Token Management**: Automatic token cleanup on logout

## 💳 Payment System

The platform uses a **Strategy Pattern** for payment processing:

- **PaymentProcessorInterface**: Abstract payment processor
- **DummyPaymentProcessor**: MVP implementation for testing
- **PaymentStrategy**: Strategy pattern implementation
- **PaymentResult**: DTO for payment responses

This design allows easy integration of real payment gateways (Stripe, PayPal, etc.) in the future.

## 🧪 Testing

### With Laravel Sail
```bash
# Run PHP tests with Pest
./vendor/bin/sail test

# Run PHPStan static analysis
./vendor/bin/sail phpstan analyse
```

### Local Development
```bash
# Run PHP tests with Pest
./vendor/bin/pest

# Run PHPStan static analysis
./vendor/bin/phpstan analyse
```

## 📁 Project Structure

```
AcmeCORP/
├── app/
│   ├── Http/Controllers/Api/    # API Controllers
│   ├── Models/                  # Eloquent Models
│   ├── Providers/              # Service Providers
│   └── Services/Payments/      # Payment Services
├── database/
│   ├── migrations/             # Database Migrations
│   └── seeders/               # Database Seeders
├── resources/
│   ├── js/
│   │   ├── components/        # Vue Components
│   │   ├── router/           # Vue Router
│   │   ├── stores/           # Pinia Stores
│   │   └── views/            # Vue Views
│   └── views/                # Blade Templates
├── routes/
│   ├── api.php               # API Routes
│   └── web.php               # Web Routes
└── tests/                    # Test Files
```

## 🔧 Configuration

### Environment Variables
Key environment variables in `.env`:

```env
APP_NAME="ACME Corp CSR Platform"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ACMECorp
DB_USERNAME=sail
DB_PASSWORD=password

SANCTUM_STATEFUL_DOMAINS=localhost
SESSION_DOMAIN=localhost
```

### Sail-Specific Configuration
When using Laravel Sail, the following services are available:
- **Laravel App**: http://localhost
- **MySQL**: localhost:3306
- **Redis**: localhost:6379
- **Mailpit**: http://localhost:8025

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Run `npm run build` to compile assets
3. Configure your web server (Apache/Nginx)
4. Set up SSL certificates
5. Configure database for production

### Docker (Optional)
```bash
# Build and run with Docker
docker-compose up -d
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is proprietary software developed for ACME Corp.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

## 🔄 Version History

- **v1.0.0**: Initial release with core CSR functionality
  - Employee authentication
  - Campaign management
  - Donation system
  - Admin panel
  - Role-based access control

---

**Built with ❤️ for ACME Corp's Corporate Social Responsibility Initiative**

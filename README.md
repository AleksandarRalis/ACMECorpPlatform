# ACME Corp CSR Platform

A Corporate Social Responsibility (CSR) donation platform for employee engagement and community involvement.

## 🎯 Project Overview

ACME Corp is an international company with 20K+ employees implementing a CSR initiative to enhance employee engagement through charitable donations and community involvement.

### Key Features
- **Employee Authentication** - Secure login for employees
- **Campaign Management** - Create, manage, and search fundraising campaigns
- **Donation System** - Donate to causes with confirmation
- **Admin Panel** - Dashboard and application management
- **Modern UI** - Built with Vue.js 3 and Tailwind CSS

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (Latest PHP framework)
- **Frontend**: Vue.js 3 with Composition API
- **Database**: MySQL/MariaDB
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **State Management**: Pinia
- **Routing**: Vue Router

## 🚀 Quick Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/MariaDB

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd AcmeCORP
   ```

2. **Run the setup script**
   ```bash
   ./setup.sh
   ```

   This script will:
   - Install all dependencies
   - Configure MySQL database
   - Set up environment variables
   - Run database migrations
   - Build frontend assets

3. **Start the servers**
   ```bash
   # Terminal 1: Start Laravel server
   php artisan serve
   
   # Terminal 2: Start Vite dev server
   npm run dev
   ```

4. **Open your browser**
   Navigate to `http://127.0.0.1:8000`

## 📁 Project Structure

```
AcmeCORP/
├── app/                    # Laravel backend
│   ├── Http/Controllers/   # API controllers
│   ├── Models/            # Database models
│   └── ...
├── resources/
│   ├── js/               # Vue.js frontend
│   │   ├── views/        # Vue components
│   │   ├── router/       # Vue Router
│   │   └── App.vue       # Main Vue app
│   └── ...
├── database/
│   └── migrations/       # Database migrations
├── setup.sh             # Setup script
└── README.md           # This file
```

## 🗄️ Database Schema

The platform uses the following main tables:
- `users` - Employee accounts
- `campaigns` - Fundraising campaigns
- `donations` - Employee donations
- `categories` - Campaign categories

## 🔧 Development

### Available Commands

```bash
# Laravel commands
php artisan serve          # Start development server
php artisan migrate        # Run database migrations
php artisan migrate:fresh  # Reset database and run migrations
php artisan make:model     # Create new model
php artisan make:controller # Create new controller

# Frontend commands
npm run dev               # Start Vite dev server
npm run build            # Build for production
npm run preview          # Preview production build
```

### Code Quality Tools

- **PHPStan** - Static analysis (Level 8)
- **Pest** - Testing framework
- **Laravel Pint** - Code styling

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run with Pest
./vendor/bin/pest
```

## 📦 Production Deployment

1. Set environment variables for production
2. Run `npm run build` to compile assets
3. Configure web server (Apache/Nginx)
4. Set up production database
5. Run `php artisan migrate --force`

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is proprietary software for ACME Corp.

## 🆘 Support

For technical issues or questions, please contact the development team.

---

**Built with ❤️ for ACME Corp's CSR initiative**

#!/bin/bash

echo "🚀 Setting up ACME Corp CSR Platform..."
echo "======================================"

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL is not installed. Please install MySQL first:"
    echo "   sudo apt update && sudo apt install mysql-server"
    exit 1
fi

# Check if MySQL is running
if ! sudo systemctl is-active --quiet mysql; then
    echo "🔄 Starting MySQL..."
    sudo systemctl start mysql
fi

# Create database
echo "🗄️  Creating database..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS ACMECorp;"

# Configure MySQL root user for Laravel
echo "🔧 Configuring MySQL authentication..."
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''; FLUSH PRIVILEGES;"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Install additional CSS dependencies
echo "📦 Installing CSS dependencies..."
npm install autoprefixer postcss --save-dev

# Create Tailwind config
echo "⚙️  Creating Tailwind configuration..."
cat > tailwind.config.js << 'EOF'
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
EOF

# Copy environment file
if [ ! -f .env ]; then
    echo "📋 Creating .env file..."
    cp .env.example .env
fi

# Configure database in .env
echo "⚙️  Configuring database settings..."
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env
sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=ACMECorp/' .env
sed -i 's/# DB_USERNAME=root/DB_USERNAME=root/' .env
sed -i 's/# DB_PASSWORD=/DB_PASSWORD=/' .env

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Run migrations
echo "🗃️  Setting up database tables..."
php artisan migrate

# Build assets
echo "🏗️  Building frontend assets..."
npm run build

echo ""
echo "✅ Setup complete!"
echo "=================="
echo "To start the application:"
echo "1. Start Laravel server: php artisan serve"
echo "2. Start Vite dev server: npm run dev"
echo "3. Open http://127.0.0.1:8000 in your browser"
echo ""
echo "Happy coding! 🎉" 
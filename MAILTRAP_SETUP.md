# Mailtrap Setup Guide

## 1. Get Mailtrap Credentials

1. Go to [Mailtrap.io](https://mailtrap.io) and create a free account
2. Create a new inbox for your project
3. Go to the inbox settings and copy the SMTP credentials

## 2. Update Environment Variables

Add these variables to your `.env` file:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@acmecorp.com"
MAIL_FROM_NAME="${APP_NAME}"

# Queue Configuration (for async email sending)
QUEUE_CONNECTION=database
```

## 3. Create Queue Tables

Run the following command to create queue tables:

```bash
php artisan queue:table
php artisan migrate
```

## 4. Start Queue Worker

For development, start the queue worker:

```bash
php artisan queue:work
```

For production, use a process manager like Supervisor.

## 5. Test Email Sending

After making a donation, check your Mailtrap inbox to see the confirmation email.

## 6. Production Setup

For production, replace Mailtrap with a real email service like:
- SendGrid
- Mailgun
- Amazon SES
- Postmark

Update the MAIL_* variables accordingly. 
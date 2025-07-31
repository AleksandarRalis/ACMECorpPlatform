<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Donation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
        }
        .title {
            color: #1f2937;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .donation-amount {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #059669;
        }
        .campaign-info {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .campaign-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .transaction-details {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .transaction-id {
            font-family: monospace;
            background-color: #ffffff;
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }
        .message-box {
            background-color: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #059669;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #047857;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">ACME Corp CSR Platform</div>
            <h1 class="title">Thank You for Your Donation!</h1>
        </div>

        <p>Dear {{ $donor->name }},</p>

        <p>Thank you for your generous donation! Your contribution will make a real difference in our community.</p>

        <div class="donation-amount">
            <div class="amount">${{ number_format($donation->amount, 2) }}</div>
            <p>Your donation amount</p>
        </div>

        <div class="campaign-info">
            <div class="campaign-title">{{ $campaign->title }}</div>
            <p>{{ $campaign->description }}</p>
            <p><strong>Campaign Goal:</strong> ${{ number_format($campaign->goal_amount, 2) }}</p>
            <p><strong>Current Progress:</strong> {{ number_format(($campaign->current_amount / $campaign->goal_amount) * 100, 1) }}%</p>
        </div>

        @if($donation->message)
        <div class="message-box">
            <strong>Your Message:</strong><br>
            "{{ $donation->message }}"
        </div>
        @endif

        <div class="transaction-details">
            <strong>Transaction Details:</strong><br>
            <strong>Transaction ID:</strong> <span class="transaction-id">{{ $donation->details->transaction_id ?? 'N/A' }}</span><br>
            <strong>Date:</strong> {{ $donation->created_at->format('F j, Y \a\t g:i A') }}<br>
            <strong>Payment Method:</strong> {{ $donation->details->payment_method ?? 'Credit Card' }}
        </div>

        <p>Your donation has been successfully processed and will be used to support this important cause. We'll keep you updated on the campaign's progress and the impact of your contribution.</p>

        <div style="text-align: center;">
            <a href="{{ url('/campaigns/' . $campaign->id) }}" class="button">View Campaign Progress</a>
        </div>

        <p>If you have any questions about your donation, please don't hesitate to contact us.</p>

        <div class="footer">
            <p>Thank you for making a difference!</p>
            <p>ACME Corp CSR Platform<br>
            Email: support@acmecorp.com<br>
            Phone: (555) 123-4567</p>
        </div>
    </div>
</body>
</html> 
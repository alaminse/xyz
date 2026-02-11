<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: #2563eb;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-header h2 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .info-row {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #2563eb;
            display: block;
            margin-bottom: 5px;
        }
        .value {
            color: #374151;
        }
        .message-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #2563eb;
        }
        .email-footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>📧 New Contact Form Submission</h2>
        </div>

        <div class="email-body">
            <p style="margin-bottom: 20px;">You have received a new message from your website contact form.</p>

            <div class="info-row">
                <span class="label">Name:</span>
                <span class="value">{{ $data['name'] }}</span>
            </div>

            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">
                    <a href="mailto:{{ $data['email'] }}" style="color: #2563eb; text-decoration: none;">
                        {{ $data['email'] }}
                    </a>
                </span>
            </div>

            <div class="info-row">
                <span class="label">Phone:</span>
                <span class="value">
                    <a href="tel:{{ $data['phone'] }}" style="color: #2563eb; text-decoration: none;">
                        {{ $data['phone'] }}
                    </a>
                </span>
            </div>

            <div class="info-row">
                <span class="label">Message:</span>
                <div class="message-box">
                    {{ $data['message'] }}
                </div>
            </div>
        </div>

        <div class="email-footer">
            <p style="margin: 0;">This email was sent from your website contact form.</p>
            <p style="margin: 5px 0 0 0;">{{ config('app.name') }} © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #0d1117; color: #e8edf4; padding: 20px; }
        .container { background-color: #161b24; border: 1px solid #2a3340; border-radius: 12px; padding: 40px; max-width: 600px; margin: auto; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { background: linear-gradient(135deg, #f0c040, #c8930a); width: 60px; height: 60px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 900; font-size: 24px; color: #000; }
        h1 { color: #f0c040; font-size: 24px; margin-top: 20px; }
        p { line-height: 1.6; color: #7a8899; }
        .details { background: #1e2530; border-radius: 8px; padding: 20px; margin: 25px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #2a3340; }
        .detail-row:last-child { border-bottom: none; }
        .label { color: #7a8899; font-size: 13px; }
        .value { color: #00e5ff; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #7a8899; margin-top: 30px; }
        .btn { display: inline-block; background-color: #00e5ff; color: #000; padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">A</div>
            <h1>Registration Successful!</h1>
            <p>Welcome to the Apex League. Your team has been successfully registered and is now under review.</p>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="label">Team Name</span>
                <span class="value">{{ $team->team_name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Reference Code</span>
                <span class="value">{{ $team->reference_code }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Division</span>
                <span class="value">{{ strtoupper($team->division) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Registration Status</span>
                <span class="value">{{ strtoupper($team->registration_status) }}</span>
            </div>
        </div>

        <p>Our admin team will review your application and approve it within 48 hours. You will receive another notification once your team is fully approved to participate in the upcoming season.</p>

        <div style="text-align: center;">
            <a href="{{ config('app.url') }}" class="btn">Go to Dashboard</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Apex League. All rights reserved.
        </div>
    </div>
</body>
</html>

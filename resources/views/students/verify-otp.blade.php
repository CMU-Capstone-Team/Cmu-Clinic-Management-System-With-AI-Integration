<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Access | CMU Alaga</title>
    
    <!-- ✅ EXTERNAL CSS FILE -->
    @vite(['resources/css/auth.css'])
</head>
<body>
<main class="shell">
    <section class="card" aria-label="CMU Alaga account access">
        <aside class="story">
            <div class="brand">
                <img src="{{ asset('images/cmu-alaga-logo.png') }}" alt="City of Malabon University logo">
                <div><strong>CMU Alaga</strong><small>City of Malabon University</small></div>
            </div>
            <div class="story-copy">
                <div class="eyebrow"><span class="dot" aria-hidden="true"></span> Student portal</div>
                <h2>Your campus care,<br>in one place.</h2>
                <p>A simple way to access your CMU Alaga account. Sign in and stay connected with your campus clinic.</p>
            </div>
            <div class="story-note"><strong>A little care goes a long way.</strong>For the students. For a healthier campus.</div>
        </aside>
        
        <div class="content">
            <p class="topline">CMU Alaga / Account access</p>
            
<h1>Check your email</h1>
<p class="intro">Enter the 6-digit code sent to <strong>{{ $email }}</strong>. The code expires in 10 minutes.</p>
@if (session('status'))<div class="notice" role="status">{{ session('status') }}</div>@endif
@if ($errors->any())<div class="notice" role="alert">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<form action="{{ route('student.otp.verify') }}" method="POST">
@csrf
<label for="code">Verification code</label>
<input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus style="width:100%;padding:16px;margin:10px 0 20px;border:1px solid #cbd5e1;border-radius:12px;font-size:28px;letter-spacing:10px;text-align:center">
<button type="submit" style="width:100%;padding:14px;border:0;border-radius:12px;background:#2746c7;color:white;font-weight:700">Verify email</button>
</form>
<form action="{{ route('student.otp.resend') }}" method="POST" style="margin-top:18px;text-align:center">@csrf
<button type="submit" style="border:0;background:transparent;color:#2746c7;cursor:pointer">Resend code</button>
<p style="font-size:12px;color:#64748b;margin-top:8px">Wait 60 seconds between requests. Check your spam folder too.</p>
</form>
<p style="margin-top:24px;text-align:center"><a href="{{ route('login') }}">Back to sign in</a></p>
</div></section></main></body></html>

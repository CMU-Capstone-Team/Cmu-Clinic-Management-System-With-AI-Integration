<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot password | CMU Alaga</title>
    <style>
        :root{color-scheme:light;--ink:#152441;--muted:#68758b;--blue:#2449bf;--line:#dce3ee}
        *{box-sizing:border-box}body{margin:0;background:#071226;color:var(--ink);font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;font-size:14px;line-height:1.6}
        button,input{font:inherit}a{color:var(--blue);text-decoration:none}a:hover{text-decoration:underline}button,a,input{-webkit-tap-highlight-color:transparent}
        :focus-visible{outline:3px solid #7598f3;outline-offset:4px}
        .shell{min-height:100svh;padding:40px 24px;display:grid;place-items:center;background:radial-gradient(ellipse at 15% 10%,#153567 0,transparent 55%),radial-gradient(ellipse at 95% 90%,#16374c 0,transparent 45%)}
        .card{display:grid;grid-template-columns:0.9fr 1.1fr;width:100%;max-width:980px;min-height:650px;border-radius:28px;overflow:hidden;background:#fff;box-shadow:0 30px 90px #0005;animation:arrive .35s ease-out}
        .story{padding:44px;display:flex;flex-direction:column;justify-content:space-between;gap:48px;background:linear-gradient(145deg,#163567,#10274e 65%,#184963);color:#fff;position:relative;overflow:hidden}
        .story::after{content:"+";position:absolute;right:-35px;bottom:15px;font-size:280px;line-height:1;font-weight:200;color:#ffffff06;pointer-events:none}
        .brand{display:flex;align-items:center;gap:13px}.brand img{width:52px;height:56px;object-fit:contain;background:#fff;padding:5px;border-radius:12px}.brand strong{display:block;font-size:20px;letter-spacing:-.5px}.brand small{color:#c0cee4;font-size:11px;letter-spacing:1px;text-transform:uppercase}
        .eyebrow{font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#b9d8ff;display:flex;align-items:center;gap:8px}.dot{height:7px;width:7px;border-radius:50%;background:#85dbca}
        .story h2{font-size:clamp(32px,4vw,43px);line-height:1.17;letter-spacing:-1.6px;font-weight:650;margin:20px 0}.story p{max-width:300px;color:#c3d1e5;font-size:14px;margin:0;line-height:1.85}
        .story-note{position:relative;z-index:1;border-top:1px solid #ffffff26;padding-top:22px;color:#c3d1e5;font-size:12px}.story-note strong{display:block;color:#fff;font-size:13px;margin-bottom:5px}
        .content{padding:42px 48px;display:flex;flex-direction:column;justify-content:center;min-width:0}.topline{font-size:11px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:var(--muted);margin:0 0 22px}
        .tabs{display:grid;grid-template-columns:1fr 1fr;gap:4px;padding:5px;border-radius:12px;background:#f0f3f9;margin-bottom:28px}.tabs a{text-align:center;padding:9px;border-radius:8px;color:#6b7790;font-weight:600;transition:background .18s,color .18s,box-shadow .18s}.tabs a:hover{text-decoration:none;color:var(--ink);background:#e7edf8}.tabs a[aria-current="page"]{background:#fff;color:#2449bf;box-shadow:0 2px 6px #18325b12}
        h1{font-size:28px;letter-spacing:-.9px;line-height:1.25;margin:0 0 9px} .intro{color:var(--muted);margin:0 0 25px;font-size:13px;line-height:1.7}
        .form{display:grid;gap:18px}.field label{display:block;font-weight:600;font-size:12px;margin-bottom:7px}.field input{display:block;width:100%;min-height:48px;border:1px solid var(--line);border-radius:10px;padding:11px 13px;background:#fff;color:var(--ink);outline:none;transition:border-color .18s,box-shadow .18s}.field input::placeholder{color:#929bb0}.field input:focus{border-color:#5277db;box-shadow:0 0 0 3px #2449bf15}.field input[aria-invalid="true"]{border-color:#ce5467}.field input[readonly]{background:#f5f7fb}
        .password-wrap{position:relative}.password-wrap input{padding-right:68px}.reveal{position:absolute;right:6px;top:5px;bottom:5px;border:0;background:transparent;color:#596c8d;font-size:11px;font-weight:700;padding:0 9px;cursor:pointer;border-radius:6px}.reveal:hover{background:#f0f3f9}.hint{font-size:11px;color:var(--muted);margin:6px 0 0}.error{font-size:12px;color:#a52a40;margin:6px 0 0}
        .options{display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:12px;flex-wrap:wrap}.remember{display:flex;gap:8px;align-items:center;color:#59667d;cursor:pointer}.remember input{accent-color:var(--blue);width:16px;height:16px;margin:0}
        .primary{display:flex;align-items:center;justify-content:center;gap:12px;width:100%;min-height:48px;padding:12px 20px;border:0;border-radius:10px;background:var(--blue);color:#fff;font-weight:650;font-size:13px;cursor:pointer;box-shadow:0 4px 10px #2449bf18;transition:background .18s,transform .18s,box-shadow .18s}.primary:hover{background:#1c3ca3;transform:translateY(-1px);box-shadow:0 6px 14px #2449bf25}.primary:active{transform:translateY(0)}.primary:disabled{opacity:.7;cursor:wait;transform:none}.primary svg{width:16px;height:16px}
        .foot{font-size:12px;color:var(--muted);text-align:center;margin:23px 0 0}.foot a{font-weight:600}.smallprint{font-size:10px;line-height:1.7;text-align:center;color:#8690a3;margin:28px 0 0;padding-top:18px;border-top:1px solid #edf0f5}
        .notice{padding:12px 14px;border-radius:10px;margin-bottom:20px;font-size:12px;background:#eff9f4;color:#25644c;border:1px solid #cce9db}.notice.bad{background:#fff4f5;border-color:#f0d2d8;color:#a02f42}.notice ul{padding-left:17px;margin:4px 0 0}.back{display:inline-block;margin-bottom:25px;font-size:12px;font-weight:600}.portal-box{padding:18px;border:1px solid var(--line);border-radius:12px;margin:0 0 18px}.portal-box p{margin:4px 0;color:var(--muted);overflow-wrap:anywhere}.portal-box strong{font-size:13px}.pill{display:inline-block;padding:4px 9px;border-radius:6px;background:#edf3ff;color:#2449bf;font-size:10px;font-weight:700;margin-bottom:14px}
        @keyframes arrive{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        @media(max-width:760px){.shell{padding:20px 16px}.card{max-width:480px;grid-template-columns:1fr;min-height:0;border-radius:22px}.story{padding:23px 28px;gap:0}.story-copy,.story-note,.story::after{display:none}.brand img{width:42px;height:46px}.brand strong{font-size:18px}.brand small{font-size:9px}.content{padding:28px}.topline{margin-bottom:16px}h1{font-size:26px}.smallprint{margin-top:23px}}
        @media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important;transform:none!important}}
    </style>
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
            <a class="back" href="{{ route('login') }}">&larr; Back to log in</a>

            <h1>Forgot your password?</h1>
            <p class="intro">Enter your account email and we will send you a password reset link.</p>
            @if (session('status'))
                <div class="notice" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice bad" role="alert" tabindex="-1" id="error-summary">
                    <strong>Please check the following:</strong>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @unless(\Illuminate\Support\Facades\Route::has('password.email'))
<div class="notice" role="status">Password recovery is not available yet. Please contact the campus clinic for assistance.</div>
@endunless
<form method="POST" action="{{ \Illuminate\Support\Facades\Route::has('password.email') ? route('password.email') : '#' }}" class="form" data-busy="Sending request…">@csrf
<div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="email-error"><div id="email-error">@error('email')<p class="error">{{ $message }}</p>@enderror</div></div><button type="submit" class="primary" @disabled(!\Illuminate\Support\Facades\Route::has('password.email'))>Send reset link<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5"/></svg></button></form><p class="foot">Remember your password? <a href="{{ route('login') }}">Log in</a></p>

            <p class="smallprint">City of Malabon University &middot; CMU Alaga<br>Keep your password private. Sign out on shared devices.</p>
        </div>
    </section>
</main>
<script>
    document.querySelectorAll('[data-password-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            button.textContent = visible ? 'Hide' : 'Show';
            button.setAttribute('aria-pressed', String(visible));
            button.setAttribute('aria-label', `${visible ? 'Hide' : 'Show'} ${button.dataset.label || 'password'}`);
        });
    });
    document.querySelectorAll('form[data-busy]').forEach(form => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button) return;
            button.dataset.original = button.innerHTML;
            button.disabled = true;
            button.textContent = form.dataset.busy;
            form.setAttribute('aria-busy', 'true');
        });
    });
    window.addEventListener('pageshow', () => {
        document.querySelectorAll('button[data-original]').forEach(button => {
            button.innerHTML = button.dataset.original;
            button.disabled = false;
            button.closest('form').removeAttribute('aria-busy');
        });
    });
    document.getElementById('error-summary')?.focus();
</script>
</body>
</html>

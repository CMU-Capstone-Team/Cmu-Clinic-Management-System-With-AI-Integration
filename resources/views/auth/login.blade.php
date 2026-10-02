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
            
            <!-- ✅ TABS NAVIGATION WITH JS HANDLERS -->
            <nav class="tabs" aria-label="Account access">
                <a href="#" id="tab-login" onclick="switchTab('login'); return false;" aria-current="page">Log in</a>
                <a href="#" id="tab-register" onclick="switchTab('register'); return false;">Register</a>
            </nav>

            <!-- ✅ LOGIN FORM CONTAINER (Hidden by default via JS logic if needed, but we'll handle visibility) -->
            <div id="container-login">
                <h1>Welcome back</h1>
                <p class="intro">Sign in to your CMU Alaga student account.</p>
                
                @if (session('status'))
                    <div class="notice" role="status">{{ session('status') }}</div>
                @endif
                
                @if ($errors->any() && !request()->is('login*screen=register')) 
                    {{-- Show errors only if not on register tab --}}
                    <div class="notice bad" role="alert">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="form" data-busy="Signing in…">
                    @csrf
                    <div class="field">
                        <label for="login-email">Email address</label>
                        <input id="login-email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
                        @error('email')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="login-password">Password</label>
                        <div class="password-wrap">
                            <input id="login-password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password">
                            <button class="reveal" type="button" data-password-toggle="login-password">Show</button>
                        </div>
                        @error('password')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="primary">Sign In</button>
                </form>
                <p class="foot">Don't have an account? <a href="#" onclick="switchTab('register'); return false;">Create one now</a></p>
            </div>

            <!-- ✅ REGISTER FORM CONTAINER (Hidden initially) -->
            <div id="container-register" style="display: none;">
                <h1>Student pre-registration</h1>
                <p class="intro">Get started with your CMU Alaga student account.</p>
                
                @if ($errors->any() && request()->is('login*screen=register'))
                    <div class="notice bad" role="alert" tabindex="-1" id="error-summary">
                        <strong>Please check the following:</strong>
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('pre-register.store') }}" class="form" data-busy="Creating account…">
                    @csrf
                    
                    <div class="field">
                        <label for="name">Full name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" placeholder="Enter your full name">
                        @error('name')<p class="error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="student_number">Student Number</label>
                        <input id="student_number" name="student_number" type="text" value="{{ old('student_number') }}" required placeholder="e.g., 202400047">
                        @error('student_number')<p class="error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="email">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
                        @error('email')<p class="error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="password-wrap">
                            <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="At least 8 characters" minlength="8">
                            <button class="reveal" type="button" data-password-toggle="password">Show</button>
                        </div>
                        @error('password')<p class="error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm password</label>
                        <div class="password-wrap">
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Re-enter your password" minlength="8">
                            <button class="reveal" type="button" data-password-toggle="password_confirmation">Show</button>
                        </div>
                        @error('password_confirmation')<p class="error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="primary">
                        Create student account
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5"/></svg>
                    </button>
                </form>
                <p class="foot">Already have an account? <a href="#" onclick="switchTab('login'); return false;">Sign in here</a></p>
            </div>

            <p class="smallprint">City of Malabon University &middot; CMU Alaga<br>Keep your password private. Sign out on shared devices.</p>
        </div>
    </section>
</main>

<script>
    // ✅ TAB SWITCHING LOGIC
    function switchTab(tab) {
        const loginContainer = document.getElementById('container-login');
        const registerContainer = document.getElementById('container-register');
        const loginTab = document.getElementById('tab-login');
        const registerTab = document.getElementById('tab-register');

        if (tab === 'login') {
            loginContainer.style.display = 'block';
            registerContainer.style.display = 'none';
            loginTab.setAttribute('aria-current', 'page');
            registerTab.removeAttribute('aria-current');
        } else {
            loginContainer.style.display = 'none';
            registerContainer.style.display = 'block';
            registerTab.setAttribute('aria-current', 'page');
            loginTab.removeAttribute('aria-current');
        }
    }

    // Password Toggle Logic
    document.querySelectorAll('[data-password-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const inputId = button.dataset.passwordToggle;
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.textContent = isPassword ? 'Hide' : 'Show';
            button.setAttribute('aria-pressed', String(isPassword));
        });
    });

    // Busy State Logic
    document.querySelectorAll('form[data-busy]').forEach(form => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button) return;
            button.dataset.original = button.innerHTML;
            button.disabled = true;
            button.textContent = form.dataset.busy;
        });
    });

    // Auto-switch to Register tab if there are registration errors
    @if($errors->any() && old('student_number'))
        switchTab('register');
    @endif
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'M2Way') }} - Login</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            *, *::before, *::after {
                box-sizing: border-box;
            }

            :root {
                --bg: #f6f7fb;
                --panel: #ffffff;
                --text: #172033;
                --muted: #657084;
                --line: #dfe4ed;
                --primary: #1b4dff;
                --primary-dark: #173fd1;
                --danger: #d92d20;
                --danger-bg: #fff1f0;
                --success: #067647;
                --success-bg: #ecfdf3;
                --shadow: 0 24px 70px rgba(15, 23, 42, 0.12);
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            }

            body {
                min-height: 100vh;
                margin: 0;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(27, 77, 255, 0.14), transparent 34rem),
                    linear-gradient(135deg, #f8fbff 0%, var(--bg) 48%, #eef4ff 100%);
                font-family: var(--font-sans, 'Instrument Sans', ui-sans-serif, system-ui, sans-serif);
            }

            .login-page {
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 32px 18px;
            }

            .login-shell {
                width: min(100%, 1020px);
                display: grid;
                grid-template-columns: minmax(0, 1fr) 430px;
                overflow: hidden;
                border: 1px solid rgba(223, 228, 237, 0.9);
                border-radius: 28px;
                background: rgba(255, 255, 255, 0.76);
                box-shadow: var(--shadow);
                backdrop-filter: blur(14px);
            }

            .brand-panel {
                position: relative;
                min-height: 640px;
                padding: 56px;
                color: #ffffff;
                background:
                    linear-gradient(145deg, rgba(11, 31, 85, 0.92), rgba(27, 77, 255, 0.82)),
                    url("data:image/svg+xml,%3Csvg width='720' height='720' viewBox='0 0 720 720' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='rgba(255,255,255,.18)' stroke-width='1'%3E%3Cpath d='M60 530C180 360 270 310 430 338s210-42 250-190'/%3E%3Cpath d='M30 230c170-86 290-94 404-24s198 58 256-34'/%3E%3Cpath d='M106 650c92-92 184-138 276-138s174-42 246-126'/%3E%3C/g%3E%3C/svg%3E");
                background-size: cover;
            }

            .brand-panel::after {
                content: "";
                position: absolute;
                inset: auto 44px 44px auto;
                width: 190px;
                height: 190px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.14);
                filter: blur(2px);
            }

            .brand-mark {
                width: 52px;
                height: 52px;
                display: grid;
                place-items: center;
                border-radius: 16px;
                background: #ffffff;
                color: var(--primary);
                font-size: 22px;
                font-weight: 700;
                letter-spacing: -0.04em;
            }

            .brand-copy {
                position: relative;
                z-index: 1;
                max-width: 420px;
                margin-top: 132px;
            }

            .brand-copy h1 {
                margin: 0 0 18px;
                font-size: clamp(34px, 5vw, 54px);
                line-height: 0.98;
                letter-spacing: -0.05em;
            }

            .brand-copy p {
                margin: 0;
                color: rgba(255, 255, 255, 0.78);
                font-size: 16px;
                line-height: 1.7;
            }

            .trust-row {
                position: absolute;
                z-index: 1;
                left: 56px;
                right: 56px;
                bottom: 52px;
                display: flex;
                gap: 14px;
                flex-wrap: wrap;
            }

            .trust-chip {
                padding: 9px 13px;
                border: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: 999px;
                color: rgba(255, 255, 255, 0.82);
                background: rgba(255, 255, 255, 0.1);
                font-size: 13px;
            }

            .form-panel {
                display: flex;
                align-items: center;
                background: var(--panel);
                padding: 52px 44px;
            }

            .form-wrap {
                width: 100%;
            }

            .form-header {
                margin-bottom: 30px;
            }

            .eyebrow {
                margin: 0 0 10px;
                color: var(--primary);
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .form-header h2 {
                margin: 0;
                color: var(--text);
                font-size: 30px;
                line-height: 1.1;
                letter-spacing: -0.04em;
            }

            .form-header p {
                margin: 10px 0 0;
                color: var(--muted);
                font-size: 15px;
                line-height: 1.55;
            }

            .alert {
                margin-bottom: 18px;
                padding: 12px 14px;
                border-radius: 14px;
                font-size: 14px;
                line-height: 1.45;
            }

            .alert-error {
                border: 1px solid #fecdca;
                color: var(--danger);
                background: var(--danger-bg);
            }

            .alert-success {
                border: 1px solid #abefc6;
                color: var(--success);
                background: var(--success-bg);
            }

            .field {
                margin-bottom: 18px;
            }

            .field-top {
                display: flex;
                align-items: baseline;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 8px;
            }

            label {
                color: var(--text);
                font-size: 14px;
                font-weight: 600;
            }

            .input-control {
                width: 100%;
                min-height: 48px;
                border: 1px solid var(--line);
                border-radius: 14px;
                background: #fbfcff;
                color: var(--text);
                font: inherit;
                font-size: 15px;
                outline: none;
                padding: 0 14px;
                transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
            }

            .input-control::placeholder {
                color: #9aa4b2;
            }

            .input-control:focus {
                border-color: var(--primary);
                background: #ffffff;
                box-shadow: 0 0 0 4px rgba(27, 77, 255, 0.12);
            }

            .input-control[aria-invalid="true"] {
                border-color: #f97066;
                background: #fffafa;
            }

            .password-wrap {
                position: relative;
            }

            .password-wrap .input-control {
                padding-right: 78px;
            }

            .password-toggle {
                position: absolute;
                top: 50%;
                right: 8px;
                transform: translateY(-50%);
                border: 0;
                border-radius: 10px;
                background: transparent;
                color: var(--primary);
                cursor: pointer;
                font: inherit;
                font-size: 13px;
                font-weight: 700;
                padding: 8px 9px;
            }

            .password-toggle:hover {
                background: rgba(27, 77, 255, 0.08);
            }

            .field-error {
                margin: 7px 0 0;
                color: var(--danger);
                font-size: 13px;
            }

            .options-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin: 4px 0 24px;
            }

            .remember-label {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                color: var(--muted);
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
            }

            .remember-label input {
                width: 17px;
                height: 17px;
                accent-color: var(--primary);
            }

            .submit-button {
                width: 100%;
                min-height: 50px;
                border: 0;
                border-radius: 15px;
                color: #ffffff;
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                cursor: pointer;
                font: inherit;
                font-size: 15px;
                font-weight: 700;
                box-shadow: 0 14px 28px rgba(27, 77, 255, 0.22);
                transition: transform 160ms ease, box-shadow 160ms ease, filter 160ms ease;
            }

            .submit-button:hover {
                transform: translateY(-1px);
                box-shadow: 0 18px 34px rgba(27, 77, 255, 0.28);
                filter: saturate(1.08);
            }

            .submit-button:focus-visible,
            .password-toggle:focus-visible,
            .remember-label input:focus-visible {
                outline: 3px solid rgba(27, 77, 255, 0.24);
                outline-offset: 3px;
            }

            .form-note {
                margin: 22px 0 0;
                color: var(--muted);
                font-size: 13px;
                line-height: 1.5;
                text-align: center;
            }

            @media (max-width: 820px) {
                .login-shell {
                    grid-template-columns: 1fr;
                    border-radius: 22px;
                }

                .brand-panel {
                    min-height: auto;
                    padding: 34px;
                }

                .brand-copy {
                    margin-top: 54px;
                }

                .trust-row {
                    position: static;
                    margin-top: 34px;
                }

                .form-panel {
                    padding: 36px 24px;
                }
            }

            @media (max-width: 520px) {
                .login-page {
                    padding: 14px;
                }

                .brand-panel {
                    display: none;
                }

                .login-shell {
                    border-radius: 18px;
                }

                .form-panel {
                    padding: 32px 20px;
                }

                .form-header h2 {
                    font-size: 26px;
                }
            }
        </style>
    </head>
    <body>
        <main class="login-page">
            <section class="login-shell" aria-label="Login to {{ config('app.name', 'M2Way') }}">
                <aside class="brand-panel" aria-hidden="true">
                    <div class="brand-mark">M2</div>

                    <div class="brand-copy">
                        <h1>{{ __('app.welcome_back') }} {{ __('app.to') }} {{ config('app.name', 'M2Way') }}.</h1>
                        <p>{{ __('app.access_your_workspace') }}</p>
                    </div>

                    <div class="trust-row">
                        <span class="trust-chip">{{ __('app.secure_session') }}</span>
                        <span class="trust-chip">{{ __('app.team_workspace') }}</span>
                        <span class="trust-chip">{{ __('app.fast_access') }}</span>
                    </div>
                </aside>

                <div class="form-panel">
                    <div class="form-wrap">
                        <header class="form-header">
                            <p class="eyebrow">{{ config('app.name', 'M2Way') }}</p>
                            <h2>{{ __('app.welcome_back') }}</h2>
                            <p>{{ __('app.login_subtitle') }}</p>
                        </header>

                        @if (session('status'))
                            <div class="alert alert-success" role="status">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->has('email') && ! old('email'))
                            <div class="alert alert-error" role="alert">
                                {{ $errors->first('email') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.submit') }}" novalidate>
                            @csrf

                            <div class="field">
                                <div class="field-top">
                                    <label for="email">{{ __('app.email_address') }}</label>
                                </div>
                                <input
                                    id="email"
                                    class="input-control"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    inputmode="email"
                                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                    aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                                    placeholder="{{ __('app.email_placeholder') }}"
                                >
                                @error('email')
                                    <p class="field-error" id="email-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <div class="field-top">
                                    <label for="password">{{ __('app.password') }}</label>
                                </div>
                                <div class="password-wrap">
                                    <input
                                        id="password"
                                        class="input-control"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                        aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                                        placeholder="{{ __('app.password_placeholder') }}"
                                    >
                                    <button class="password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="{{ __('app.show_password') }}">
                                        {{ __('app.show_password') }}
                                    </button>
                                </div>
                                @error('password')
                                    <p class="field-error" id="password-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="options-row">
                                <label for="remember" class="remember-label">
                                    <input
                                        id="remember"
                                        type="checkbox"
                                        name="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                    >
                                    {{ __('app.remember_me') }}
                                </label>
                            </div>

                            <button class="submit-button" type="submit">
                                {{ __('app.login_button') }}
                            </button>
                        </form>

                        <p class="form-note">{{ __('app.use_registered_credentials', ['app' => config('app.name', 'M2Way')]) }}</p>
                    </div>
                </div>
            </section>
        </main>

        <script>
            const passwordToggle = document.querySelector('[data-password-toggle]');
            const passwordInput = document.getElementById('password');
            const showLabel = '{{ __("app.show_password") }}';
            const hideLabel = '{{ __("app.hide_password") }}';

            passwordToggle?.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';
                passwordToggle.textContent = isHidden ? hideLabel : showLabel;
                passwordToggle.setAttribute('aria-label', isHidden ? hideLabel : showLabel);
            });
        </script>
    </body>
</html>

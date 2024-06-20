<!doctype html>
<html class="fixed">

<head>
    <title>Login - Teltex Tecnologia</title>
    <!-- Basic -->
    <meta charset="UTF-8">
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light"
        rel="stylesheet" type="text/css">
    <!-- Vendor CSS -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/css/theme.css', 'resources/js/app.js', 'resources/js/theme.js', 'resources/js/custom.js'])
</head>

<body style="background: #123b67">
    <!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <div class="panel card-sign">
                <div class="card-title-sign mt-3 mb-4 text-end" style="display: flex">
                    <a href="#" class="logo float-start teltex-logo">
                        <img src="{{ asset('images/logos/logo-white.png') }}" height="70" alt="Teltex" />
                    </a>
                </div>
                <div class="card-body">
                    <input type="hidden" name="session" :status="session('status')">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="email" :value="__('Email')">E-mail</label>
                            <div class="input-group">
                                <input id="email" name="email" type="email" :value="old('email')"
                                    class="form-control form-control-lg" required autofocus autocomplete="username" />
                                <span class="input-group-text">
                                    <i class="bx bx-user text-4"></i>
                                </span>
                            </div>
                            @if ($errors->has('email'))
                                <span class="mt-2">
                                    @foreach ($errors->get('email') as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <div class="clearfix">
                                <label class="float-start" for="password" :value="__('Password')">Senha</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="float-end">Esqueci minha senha</a>
                                @endif
                            </div>
                            <div class="input-group">
                                <input id="password" class="form-control form-control-lg" type="password"
                                    name="password" required autocomplete="current-password" />
                                <span class="input-group-text">
                                    <i class="bx bx-lock text-4"></i>
                                </span>
                            </div>
                            @if ($errors->has('password'))
                                <span class="mt-2">
                                    @foreach ($errors->get('password') as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="checkbox-custom checkbox-default">
                                    <input id="RememberMe" name="remember" type="checkbox" />
                                    <label for="RememberMe">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-sm-4 text-end">
                                <button type="submit" class="btn btn-primary mt-2">Entrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- end: page -->

    <!-- Vendor -->
    @include('components.footer')
</body>

</html>
